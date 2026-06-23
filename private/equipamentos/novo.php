<?php
require_once __DIR__ . '/../includes/funcoes.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

redirect_if_not_logged();
redirect_if_no_permission('equipamentos', 'criar');

$pagina_atual = 'equipamentos';



$erros = [];
$sucesso_validacao = '';


$ligacao = ligar_bd();




$tipos_entrada = [];

if ($ligacao) {
    $stmt_tipos_entrada = $ligacao->query("
        SELECT id, nome
        FROM tipos_entrada
        ORDER BY nome
    ");

    $tipos_entrada = $stmt_tipos_entrada->fetchAll();
}
$prioridades_manutencao = [];

if ($ligacao) {
    $stmt_prioridades_manutencao = $ligacao->query("
        SELECT id, nome
        FROM prioridades_manutencao
        ORDER BY nome
    ");

    $prioridades_manutencao = $stmt_prioridades_manutencao->fetchAll();
}
$equipamentos_principais = [];

if ($ligacao) {
    $stmt_equipamentos_principais = $ligacao->query("
        SELECT id, designacao, marca, modelo
        FROM equipamentos
        WHERE ativo = 1
          AND (observacoes IS NULL OR observacoes <> 'RASCUNHO_REGISTO')
        ORDER BY designacao, marca, modelo
    ");

    $equipamentos_principais = $stmt_equipamentos_principais->fetchAll();
}
$ordem_etapas = ['info', 'localizacao', 'fornecedor', 'documentacao', 'garantia'];

$dados_etapas = [
    'info' => [
        'texto' => 'Etapa 1 de 5: Informação geral',
        'percentagem' => 20
    ],
    'localizacao' => [
        'texto' => 'Etapa 2 de 5: Localização',
        'percentagem' => 40
    ],
    'fornecedor' => [
        'texto' => 'Etapa 3 de 5: Fornecedor',
        'percentagem' => 60
    ],
    'documentacao' => [
        'texto' => 'Etapa 4 de 5: Documentação',
        'percentagem' => 80
    ],
    'garantia' => [
        'texto' => 'Etapa 5 de 5: Contratos',
        'percentagem' => 100
    ]
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    limpar_rascunho_equipamento();
    limpar_sessao_novo_equipamento();

    if (isset($_GET['cancelar'])) {
        header('Location: index.php');
        exit;
    }
}

if (!isset($_SESSION['novo_equipamento_etapas_ok'])) {
    $_SESSION['novo_equipamento_etapas_ok'] = [];
}

$etapa_atual = $_POST['etapa_atual'] ?? 'info';

if (!in_array($etapa_atual, $ordem_etapas, true)) {
    $etapa_atual = 'info';
}

if (!function_exists('h')) {
    function h($valor)
    {
        return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
    }
}


function limpar_rascunho_equipamento($equipamento_id = null)
{
    global $ligacao;

    if (!$ligacao) {
        return;
    }

    if ($equipamento_id === null) {
        $equipamento_id = isset($_SESSION['novo_equipamento_id']) ? (int) $_SESSION['novo_equipamento_id'] : 0;
    }

    if ($equipamento_id <= 0) {
        return;
    }

    try {
        $stmt = $ligacao->prepare("
            SELECT id
            FROM equipamentos
            WHERE id = :id
              AND ativo = 0
              AND observacoes = 'RASCUNHO_REGISTO'
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $equipamento_id
        ]);

        if (!$stmt->fetch()) {
            return;
        }

        $ligacao->beginTransaction();

        $stmt = $ligacao->prepare("
            DELETE FROM contratos
            WHERE equipamento_id = :id
        ");
        $stmt->execute([':id' => $equipamento_id]);

        $stmt = $ligacao->prepare("
            DELETE FROM equipamento_fornecedores
            WHERE equipamento_id = :id
        ");
        $stmt->execute([':id' => $equipamento_id]);

        $stmt = $ligacao->prepare("
            DELETE FROM documentos
            WHERE equipamento_id = :id
        ");
        $stmt->execute([':id' => $equipamento_id]);

        $stmt = $ligacao->prepare("
            DELETE FROM equipamentos
            WHERE id = :id
              AND ativo = 0
              AND observacoes = 'RASCUNHO_REGISTO'
        ");
        $stmt->execute([':id' => $equipamento_id]);

        $ligacao->commit();
    } catch (PDOException $erroBD) {
        if ($ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
    }
}

function limpar_sessao_novo_equipamento()
{
    $_SESSION['novo_equipamento_etapas_ok'] = [];

    unset(
        $_SESSION['novo_equipamento_id'],
        $_SESSION['novo_localizacao_id'],
        $_SESSION['novo_fornecedor_ids'],
        $_SESSION['novo_documento_ids'],
        $_SESSION['novo_contrato_id']
    );
}

function campo($nome)
{
    return h($_POST[$nome] ?? '');
}

function selecionado($nome, $valor)
{
    return (($_POST[$nome] ?? '') == $valor) ? 'selected' : '';
}

function valor_post($nome)
{
    return trim((string) ($_POST[$nome] ?? ''));
}

function data_valida($data)
{
    if ($data === '') {
        return false;
    }

    $partes = explode('-', $data);

    if (count($partes) !== 3) {
        return false;
    }

    return checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0]);
}

function telefone_valido($telefone)
{
    $telefone = trim($telefone);
    $quantidade_numeros = strlen(preg_replace('/[^0-9]/', '', $telefone));

    return preg_match('/^[0-9+\s]+$/', $telefone)
        && $quantidade_numeros >= 9
        && $quantidade_numeros <= 15;
}

function website_valido($website)
{
    return (bool) preg_match('/^(https?:\/\/)?([\w-]+\.)+[\w-]{2,}(\/.*)?$/i', trim($website));
}

function letras_validas($valor)
{
    return (bool) preg_match('/^[A-Za-zÀ-ÿ\s]+$/u', trim($valor));
}

function ficheiro_pdf_valido($nome)
{
    if (!isset($_FILES[$nome]) || $_FILES[$nome]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $nome_ficheiro = $_FILES[$nome]['name'] ?? '';

    return (bool) preg_match('/\.pdf$/i', $nome_ficheiro);
}

function obrigatorio($nome, $mensagem)
{
    global $erros;

    if (valor_post($nome) === '') {
        $erros[] = $mensagem;
        return false;
    }

    return true;
}

function validar_informacao_geral()
{
    global $erros;

    $codigo = strtoupper(preg_replace('/\s+/', '', valor_post('codigo')));
    $_POST['codigo'] = $codigo;

    if ($codigo === '') {
        $erros[] = 'O código interno é obrigatório.';
    } elseif (!preg_match('/^EQ[0-9]{3}$/', $codigo)) {
        $erros[] = 'O código interno deve seguir o formato EQ006.';
    }

    obrigatorio('designacao', 'A designação é obrigatória.');
    obrigatorio('categoria_equipamento_id', 'A categoria é obrigatória.');
    obrigatorio('marca', 'A marca é obrigatória.');
    obrigatorio('modelo', 'O modelo é obrigatório.');
    obrigatorio('numero_serie', 'O número de série é obrigatório.');
    obrigatorio('fabricante', 'O fabricante é obrigatório.');

    $ano_fabrico = valor_post('ano_fabrico');

    if ($ano_fabrico === '') {
        $erros[] = 'O ano de fabrico é obrigatório.';
    } elseif (!ctype_digit($ano_fabrico) || (int) $ano_fabrico < 1990 || (int) $ano_fabrico > ((int) date('Y') + 1)) {
        $erros[] = 'O ano de fabrico deve ser válido.';
    }

    obrigatorio('estado_equipamento_id', 'O estado atual é obrigatório.');
    obrigatorio('criticidade_id', 'A criticidade é obrigatória.');
    obrigatorio('prioridade_manutencao_id', 'A prioridade de manutenção é obrigatória.');
    obrigatorio('utilizacao_clinica', 'A utilização clínica é obrigatória.');

    $data_aquisicao = valor_post('data_aquisicao');

    if ($data_aquisicao === '') {
        $erros[] = 'A data de aquisição é obrigatória.';
    } elseif (!data_valida($data_aquisicao)) {
        $erros[] = 'A data de aquisição não é válida.';
    }

    $custo_aquisicao = str_replace(',', '.', valor_post('custo_aquisicao'));

    if ($custo_aquisicao === '') {
        $erros[] = 'O custo de aquisição é obrigatório.';
    } elseif (!is_numeric($custo_aquisicao) || (float) $custo_aquisicao < 0) {
        $erros[] = 'O custo de aquisição deve ser um valor numérico válido.';
    }

    obrigatorio('tipo_entrada_id', 'O tipo de entrada é obrigatório.');
    obrigatorio('componente_outro_equipamento', 'Indique se é componente de outro equipamento.');
    obrigatorio('tem_consumiveis', 'Indique se o equipamento tem consumíveis.');

    if (valor_post('componente_outro_equipamento') === 'Sim') {
        obrigatorio('equipamento_principal', 'Selecione o equipamento principal.');
    }

    if (valor_post('tem_consumiveis') === 'Sim') {
        obrigatorio('consumiveis_equipamento', 'Indique os consumíveis utilizados.');
    }

    return empty($erros);
}

function validar_localizacao()
{
    global $erros;

    $localizacao_id = (int) valor_post('localizacao_id');

    if ($localizacao_id <= 0) {
        $erros[] = 'A localização geral é obrigatória.';
    } else {
        $localizacao = obter_localizacao_geral($localizacao_id);

        if (!$localizacao) {
            $erros[] = 'A localização geral selecionada não existe na base de dados.';
        }
    }

    $piso = valor_post('piso_equipamento');

    if ($piso === '') {
        $erros[] = 'O piso do equipamento é obrigatório.';
    } elseif (!ctype_digit($piso)) {
        $erros[] = 'O piso deve conter apenas números.';
    } elseif (isset($localizacao) && $localizacao && (int) $localizacao->numero_pisos > 0 && (int) $piso > (int) $localizacao->numero_pisos) {
        $erros[] = 'O piso indicado não pode ser superior ao número de pisos da localização geral.';
    }

    obrigatorio('servico_equipamento', 'O serviço é obrigatório.');
    obrigatorio('sala_equipamento', 'A sala/unidade é obrigatória.');

    return empty($erros);
}

function total_fornecedores_formulario()
{
    return 5;
}


function validar_fornecedor_bloco($sufixo, $obrigatorio, $numero)
{
    global $erros;

    $campos = [
        'fornecedor_id_equipamento' . $sufixo,
        'tipo_relacao_fornecedor_id_equipamento' . $sufixo,
        'contacto_fornecedor_equipamento' . $sufixo,
        'telefone_contacto_fornecedor_equipamento' . $sufixo,
        'email_contacto_fornecedor_equipamento' . $sufixo,
        'observacoes_fornecedor_equipamento' . $sufixo
    ];

    $algum_preenchido = false;

    foreach ($campos as $campo) {
        if (valor_post($campo) !== '') {
            $algum_preenchido = true;
            break;
        }
    }

    if (!$obrigatorio && !$algum_preenchido) {
        return true;
    }

    $prefixo = 'Fornecedor associado ' . $numero . ': ';

    $fornecedor_id = (int) valor_post('fornecedor_id_equipamento' . $sufixo);

    if ($fornecedor_id <= 0) {
        $erros[] = $prefixo . 'o fornecedor é obrigatório.';
    } elseif (!obter_fornecedor_geral($fornecedor_id)) {
        $erros[] = $prefixo . 'o fornecedor selecionado não existe na base de dados.';
    }

    $tipo_relacao_id = (int) valor_post('tipo_relacao_fornecedor_id_equipamento' . $sufixo);

    if ($tipo_relacao_id <= 0) {
        $erros[] = $prefixo . 'o tipo de fornecedor/relação é obrigatório.';
    } elseif (!tipo_relacao_fornecedor_existe($tipo_relacao_id)) {
        $erros[] = $prefixo . 'o tipo de fornecedor/relação selecionado não existe na base de dados.';
    }

    $contacto = valor_post('contacto_fornecedor_equipamento' . $sufixo);

    if ($contacto === '') {
        $erros[] = $prefixo . 'a pessoa de contacto é obrigatória.';
    } elseif (!letras_validas($contacto)) {
        $erros[] = $prefixo . 'a pessoa de contacto deve conter apenas letras.';
    }

    $telefone_contacto = valor_post('telefone_contacto_fornecedor_equipamento' . $sufixo);

    if ($telefone_contacto === '') {
        $erros[] = $prefixo . 'o telefone da pessoa de contacto é obrigatório.';
    } elseif (!telefone_valido($telefone_contacto)) {
        $erros[] = $prefixo . 'o telefone da pessoa de contacto deve ser válido.';
    }

    $email_contacto = valor_post('email_contacto_fornecedor_equipamento' . $sufixo);

    if ($email_contacto === '') {
        $erros[] = $prefixo . 'o email da pessoa de contacto é obrigatório.';
    } elseif (!filter_var($email_contacto, FILTER_VALIDATE_EMAIL)) {
        $erros[] = $prefixo . 'o email da pessoa de contacto deve ser válido.';
    }

    return empty($erros);
}

function validar_fornecedor()
{
    $total = total_fornecedores_formulario();

    for ($i = 1; $i <= $total; $i++) {
        $sufixo = $i === 1 ? '' : (string) $i;
        validar_fornecedor_bloco($sufixo, $i === 1, $i);
    }

    return empty($GLOBALS['erros']);
}


function validar_documentacao()
{
    global $erros;

    $documentos = [
        ['manual_utilizador', 'manual de utilizador'],
        ['manual_servico', 'manual de serviço'],
        ['certificado', 'certificado de calibração'],
        ['declaracao_conformidade', 'declaração de conformidade'],
        ['relatorio_tecnico', 'relatório técnico'],
        ['fatura_aquisicao', 'fatura ou guia de aquisição']
    ];

    foreach ($documentos as $documento) {
        [$chave, $nome] = $documento;

        obrigatorio('nome_' . $chave, 'O nome do documento ' . $nome . ' é obrigatório.');

        $data = valor_post('data_' . $chave);

        if ($data === '') {
            $erros[] = 'A data do documento ' . $nome . ' é obrigatória.';
        } elseif (!data_valida($data)) {
            $erros[] = 'A data do documento ' . $nome . ' não é válida.';
        }

        $fornecedor_documento = valor_post('fornecedor_' . $chave);

        if ($fornecedor_documento === '') {
            $erros[] = 'Escolha o fornecedor associado ao documento ' . $nome . ', ou selecione Sem fornecedor associado.';
        } elseif ($fornecedor_documento !== '0' && !fornecedor_associado_ao_equipamento((int) $fornecedor_documento)) {
            $erros[] = 'O fornecedor associado ao documento ' . $nome . ' deve estar associado a este equipamento.';
        }

        if (!ficheiro_pdf_valido('ficheiro_' . $chave)) {
            $erros[] = 'O ficheiro do documento ' . $nome . ' é obrigatório e deve ser PDF.';
        }
    }

    return empty($erros);
}

function validar_contratos()
{
    global $erros;

    obrigatorio('estado_garantia', 'O estado do contrato/garantia é obrigatório.');

    $inicio = valor_post('data_inicio_garantia');
    $fim = valor_post('data_fim_garantia');

    if ($inicio === '') {
        $erros[] = 'A data de início da garantia é obrigatória.';
    } elseif (!data_valida($inicio)) {
        $erros[] = 'A data de início da garantia não é válida.';
    }

    if ($fim === '') {
        $erros[] = 'A data de fim da garantia é obrigatória.';
    } elseif (!data_valida($fim)) {
        $erros[] = 'A data de fim da garantia não é válida.';
    }

    if ($inicio !== '' && $fim !== '' && data_valida($inicio) && data_valida($fim) && strtotime($fim) < strtotime($inicio)) {
        $erros[] = 'A data de fim da garantia não pode ser anterior à data de início.';
    }

    obrigatorio('tipo_contrato', 'O tipo de contrato é obrigatório.');
    obrigatorio('periodicidade_manutencao', 'A periodicidade de manutenção é obrigatória.');

    $valor = str_replace(',', '.', valor_post('valor_contrato'));

    if ($valor === '') {
        $erros[] = 'O valor associado é obrigatório.';
    } elseif (!is_numeric($valor) || (float) $valor < 0) {
        $erros[] = 'O valor associado deve ser numérico e igual ou superior a zero.';
    }

    obrigatorio('nome_contrato_documento', 'O nome do documento contrato/garantia é obrigatório.');

    $data_documento_contrato = valor_post('data_contrato_documento');

    if ($data_documento_contrato === '') {
        $erros[] = 'A data do documento contrato/garantia é obrigatória.';
    } elseif (!data_valida($data_documento_contrato)) {
        $erros[] = 'A data do documento contrato/garantia não é válida.';
    }

    $fornecedor_documento = valor_post('fornecedor_contrato_documento');

    if ($fornecedor_documento === '') {
        $erros[] = 'Escolha o fornecedor associado ao contrato/garantia, ou selecione Sem fornecedor associado.';
    } elseif ($fornecedor_documento !== '0' && !fornecedor_associado_ao_equipamento((int) $fornecedor_documento)) {
        $erros[] = 'O fornecedor associado ao contrato/garantia deve estar associado a este equipamento.';
    }

    if (!ficheiro_pdf_valido('ficheiro_contrato_documento')) {
        $erros[] = 'O ficheiro do contrato/garantia é obrigatório e deve ser PDF.';
    }

    return empty($erros);
}

function mapear_valor_bd($tipo, $valor)
{
    $mapas = [
        'categoria' => [
            'Laboratório' => 'Outro',
            'Esterilização' => 'Outro'
        ],
        'estado_equipamento' => [],
        'criticidade' => [
            'Suporte de vida' => 'Alta'
        ],
        'prioridade' => [
            'Moderada' => 'Média',
            'Elevada' => 'Alta',
            'Urgente' => 'Alta'
        ],
        'tipo_entrada' => [
            'Compra' => 'Aquisição',
            'Aluguer' => 'Outro'
        ],
        'estado_localizacao' => [
            'Temporariamente indisponível' => 'Inativa'
        ],
        'tipo_fornecedor' => [
            'Assistência técnica' => 'Manutenção',
            'Consumíveis' => 'Outro'
        ],
        'tipo_relacao_fornecedor' => [
            'Consumíveis' => 'Fornecedor principal'
        ],
        'tipo_contrato' => [
            'Manutenção preventiva' => 'Contrato de manutenção'
        ]
    ];

    return $mapas[$tipo][$valor] ?? $valor;
}

function obter_id_por_nome($tabela, $nome)
{
    global $ligacao;

    $tabelas_permitidas = [
        'categorias_equipamento',
        'estados_equipamento',
        'criticidades',
        'prioridades_manutencao',
        'tipos_entrada',
        'estados_localizacao',
        'tipos_fornecedor',
        'tipos_relacao_fornecedor',
        'tipos_documento',
        'estados_documento',
        'tipos_contrato',
        'estados_contrato'
    ];

    if (!in_array($tabela, $tabelas_permitidas, true)) {
        return 0;
    }

    $stmt = $ligacao->prepare("SELECT id FROM {$tabela} WHERE nome = :nome LIMIT 1");
    $stmt->execute([':nome' => $nome]);

    return (int) $stmt->fetchColumn();
}

function obter_id_obrigatorio($tabela, $nome, $mensagem)
{
    global $ligacao, $erros;

    if ($nome === '' || $nome === null) {
        $erros[] = $mensagem;
        return 0;
    }

    $tabelas_permitidas = [
        'categorias_equipamento',
        'estados_equipamento',
        'criticidades',
        'prioridades_manutencao',
        'tipos_entrada',
        'estados_localizacao',
        'tipos_fornecedor',
        'tipos_relacao_fornecedor',
        'tipos_documento',
        'estados_documento',
        'tipos_contrato',
        'estados_contrato'
    ];

    if (!in_array($tabela, $tabelas_permitidas, true)) {
        $erros[] = $mensagem;
        return 0;
    }

    if (ctype_digit((string) $nome) && (int) $nome > 0) {
        $stmt = $ligacao->prepare("SELECT id FROM " . $tabela . " WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int) $nome]);

        $id = (int) $stmt->fetchColumn();
    } else {
        $id = obter_id_por_nome($tabela, $nome);
    }

    if ($id <= 0) {
        $erros[] = $mensagem;
    }

    return $id;
}


function carregar_localizacoes_gerais()
{
    global $ligacao;

    if (!$ligacao) {
        return [];
    }

    $sql = "
        SELECT
            l.id,
            l.codigo,
            l.edificio,
            COALESCE(l.numero_pisos, l.piso) AS numero_pisos,
            l.responsavel,
            l.contacto_interno,
            el.nome AS estado
        FROM localizacoes l
        INNER JOIN estados_localizacao el
            ON l.estado_localizacao_id = el.id
        WHERE l.codigo <> 'LOC-TMP'
          AND el.nome NOT IN ('Inativa', 'Inativo')
        ORDER BY l.codigo
    ";

    return $ligacao->query($sql)->fetchAll();
}

function obter_localizacao_geral($id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT
            l.id,
            l.codigo,
            l.edificio,
            COALESCE(l.numero_pisos, l.piso) AS numero_pisos,
            l.responsavel,
            l.contacto_interno,
            el.nome AS estado
        FROM localizacoes l
        INNER JOIN estados_localizacao el
            ON l.estado_localizacao_id = el.id
        WHERE l.id = :id
          AND l.codigo <> 'LOC-TMP'
          AND el.nome NOT IN ('Inativa', 'Inativo')
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch();
}


function carregar_fornecedores_gerais()
{
    global $ligacao;

    if (!$ligacao) {
        return [];
    }

    $sql = "
        SELECT
            id,
            codigo,
            nome,
            nif,
            telefone,
            email
        FROM fornecedores
        WHERE ativo = 1
        ORDER BY codigo
    ";

    return $ligacao->query($sql)->fetchAll();
}

function obter_fornecedor_geral($id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT
            id,
            codigo,
            nome,
            nif,
            telefone,
            email
        FROM fornecedores
        WHERE id = :id
          AND ativo = 1
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch();
}

function carregar_tipos_relacao_fornecedor()
{
    global $ligacao;

    if (!$ligacao) {
        return [];
    }

    $sql = "
        SELECT id, nome
        FROM tipos_relacao_fornecedor
        ORDER BY nome
    ";

    return $ligacao->query($sql)->fetchAll();
}

function tipo_relacao_fornecedor_existe($id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM tipos_relacao_fornecedor
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function proximo_codigo($tabela, $prefixo)
{
    global $ligacao;

    $tabelas_permitidas = ['localizacoes', 'fornecedores', 'documentos', 'contratos'];

    if (!in_array($tabela, $tabelas_permitidas, true)) {
        return $prefixo . '001';
    }

    $stmt = $ligacao->prepare("SELECT codigo FROM {$tabela} WHERE codigo LIKE :prefixo");
    $stmt->execute([':prefixo' => $prefixo . '%']);

    $maior = 0;
    $padrao = '/^' . preg_quote($prefixo, '/') . '([0-9]+)$/';

    while ($codigo = $stmt->fetchColumn()) {
        if (preg_match($padrao, (string) $codigo, $matches)) {
            $numero = (int) $matches[1];

            if ($numero > $maior) {
                $maior = $numero;
            }
        }
    }

    return $prefixo . str_pad($maior + 1, 3, '0', STR_PAD_LEFT);
}

function garantir_localizacao_provisoria()
{
    global $ligacao, $erros;

    $stmt = $ligacao->prepare("SELECT id FROM localizacoes WHERE codigo = 'LOC-TMP' LIMIT 1");
    $stmt->execute();
    $id = (int) $stmt->fetchColumn();

    if ($id > 0) {
        return $id;
    }

    $estado_id = obter_id_obrigatorio(
        'estados_localizacao',
        'Ativa',
        'Não foi possível encontrar o estado Ativa para criar a localização provisória.'
    );

    if ($estado_id <= 0) {
        return 0;
    }

    try {
        $stmt = $ligacao->prepare("
            INSERT INTO localizacoes
                (codigo, edificio, piso, servico, sala, estado_localizacao_id, responsavel, contacto_interno, observacoes)
            VALUES
                ('LOC-TMP', 'Por definir', '0', 'Por definir', 'Por definir', :estado_id, 'Sistema', '0000', 'Localização provisória usada durante o registo por etapas.')
        ");

        $stmt->execute([':estado_id' => $estado_id]);

        return (int) $ligacao->lastInsertId();
    } catch (PDOException $erroBD) {
        $erros[] = 'Não foi possível criar a localização provisória.';
        return 0;
    }
}

function equipamento_id_atual()
{
    return isset($_SESSION['novo_equipamento_id']) ? (int) $_SESSION['novo_equipamento_id'] : 0;
}

function guardar_informacao_geral_bd()
{
    global $ligacao, $erros;

    $categoria_id = obter_id_obrigatorio(
        'categorias_equipamento',
        mapear_valor_bd('categoria', valor_post('categoria_equipamento_id')),
        'A categoria selecionada não existe na base de dados.'
    );

    $estado_id = obter_id_obrigatorio(
        'estados_equipamento',
        mapear_valor_bd('estado_equipamento', valor_post('estado_equipamento_id')),
        'O estado selecionado não existe na base de dados.'
    );

    $criticidade_id = obter_id_obrigatorio(
        'criticidades',
        mapear_valor_bd('criticidade', valor_post('criticidade_id')),
        'A criticidade selecionada não existe na base de dados.'
    );

    $prioridade_id = obter_id_obrigatorio(
        'prioridades_manutencao',
        mapear_valor_bd('prioridade', valor_post('prioridade_manutencao_id')),
        'A prioridade selecionada não existe na base de dados.'
    );

    $tipo_entrada_id = obter_id_obrigatorio(
        'tipos_entrada',
        mapear_valor_bd('tipo_entrada', valor_post('tipo_entrada_id')),
        'O tipo de entrada selecionado não existe na base de dados.'
    );

    $localizacao_id = garantir_localizacao_provisoria();

    if (!empty($erros)) {
        return false;
    }

    $componente = valor_post('componente_outro_equipamento') === 'Sim' ? 1 : 0;
    $tem_consumiveis = valor_post('tem_consumiveis') === 'Sim' ? 1 : 0;
    $custo = str_replace(',', '.', valor_post('custo_aquisicao'));

    try {
        $stmt = $ligacao->prepare("
            INSERT INTO equipamentos (
                codigo,
                designacao,
                categoria_equipamento_id,
                marca,
                modelo,
                numero_serie,
                fabricante,
                ano_fabrico,
                estado_equipamento_id,
                criticidade_id,
                prioridade_manutencao_id,
                utilizacao_clinica,
                data_aquisicao,
                custo,
                tipo_entrada_id,
                componente_outro_equipamento,
                equipamento_principal_id,
                tem_consumiveis,
                consumiveis,
                localizacao_id,
                observacoes,
                ativo
            ) VALUES (
                :codigo,
                :designacao,
                :categoria_equipamento_id,
                :marca,
                :modelo,
                :numero_serie,
                :fabricante,
                :ano_fabrico,
                :estado_equipamento_id,
                :criticidade_id,
                :prioridade_manutencao_id,
                :utilizacao_clinica,
                :data_aquisicao,
                :custo,
                :tipo_entrada_id,
                :componente_outro_equipamento,
                NULL,
                :tem_consumiveis,
                :consumiveis,
                :localizacao_id,
                :observacoes,
                0
            )
        ");

        $stmt->execute([
            ':codigo' => valor_post('codigo'),
            ':designacao' => valor_post('designacao'),
            ':categoria_equipamento_id' => $categoria_id,
            ':marca' => valor_post('marca'),
            ':modelo' => valor_post('modelo'),
            ':numero_serie' => valor_post('numero_serie'),
            ':fabricante' => valor_post('fabricante'),
            ':ano_fabrico' => (int) valor_post('ano_fabrico'),
            ':estado_equipamento_id' => $estado_id,
            ':criticidade_id' => $criticidade_id,
            ':prioridade_manutencao_id' => $prioridade_id,
            ':utilizacao_clinica' => valor_post('utilizacao_clinica'),
            ':data_aquisicao' => valor_post('data_aquisicao'),
            ':custo' => $custo,
            ':tipo_entrada_id' => $tipo_entrada_id,
            ':componente_outro_equipamento' => $componente,
            ':tem_consumiveis' => $tem_consumiveis,
            ':consumiveis' => $tem_consumiveis ? valor_post('consumiveis_equipamento') : null,
            ':localizacao_id' => $localizacao_id,
            ':observacoes' => 'RASCUNHO_REGISTO'
        ]);

        $_SESSION['novo_equipamento_id'] = (int) $ligacao->lastInsertId();

        return true;
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao guardar a Informação geral. Verifique se o código interno ou número de série já existem.';
        return false;
    }
}

function guardar_localizacao_bd()
{
    global $ligacao, $erros;

    $equipamento_id = equipamento_id_atual();

    if ($equipamento_id <= 0) {
        $erros[] = 'Não foi encontrado o equipamento criado na etapa Informação geral.';
        return false;
    }

    $localizacao_id = (int) valor_post('localizacao_id');
    $localizacao = obter_localizacao_geral($localizacao_id);

    if (!$localizacao) {
        $erros[] = 'A localização geral selecionada não existe na base de dados.';
        return false;
    }

    try {
        $stmt = $ligacao->prepare("
            UPDATE equipamentos
            SET
                localizacao_id = :localizacao_id,
                localizacao_piso = :localizacao_piso,
                localizacao_servico = :localizacao_servico,
                localizacao_sala = :localizacao_sala
            WHERE id = :equipamento_id
        ");

        $stmt->execute([
            ':localizacao_id' => $localizacao_id,
            ':localizacao_piso' => valor_post('piso_equipamento'),
            ':localizacao_servico' => valor_post('servico_equipamento'),
            ':localizacao_sala' => valor_post('sala_equipamento'),
            ':equipamento_id' => $equipamento_id
        ]);

        $_SESSION['novo_localizacao_id'] = $localizacao_id;

        return true;
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao associar a localização ao equipamento. Confirma se já executaste o SQL que adiciona as colunas localizacao_piso, localizacao_servico e localizacao_sala.';
        return false;
    }
}

function fornecedor_ids_sessao()
{
    if (!isset($_SESSION['novo_fornecedor_ids']) || !is_array($_SESSION['novo_fornecedor_ids'])) {
        $_SESSION['novo_fornecedor_ids'] = [];
    }

    return $_SESSION['novo_fornecedor_ids'];
}

function guardar_fornecedor_individual_bd($sufixo, $principal, $numero)
{
    global $ligacao, $erros;

    $fornecedor_id = (int) valor_post('fornecedor_id_equipamento' . $sufixo);

    if ($fornecedor_id <= 0) {
        return null;
    }

    if (!obter_fornecedor_geral($fornecedor_id)) {
        $erros[] = 'Fornecedor associado ' . $numero . ': o fornecedor selecionado não existe na base de dados.';
        return null;
    }

    $tipo_relacao_id = (int) valor_post('tipo_relacao_fornecedor_id_equipamento' . $sufixo);

    if ($tipo_relacao_id <= 0 || !tipo_relacao_fornecedor_existe($tipo_relacao_id)) {
        $erros[] = 'Fornecedor associado ' . $numero . ': o tipo de fornecedor/relação selecionado não existe na base de dados.';
        return null;
    }

    $equipamento_id = equipamento_id_atual();

    try {
        $stmt = $ligacao->prepare("
            INSERT INTO equipamento_fornecedores
                (equipamento_id, fornecedor_id, tipo_relacao_fornecedor_id, pessoa_contacto, telefone_contacto, email_contacto, principal, observacoes)
            VALUES
                (:equipamento_id, :fornecedor_id, :tipo_relacao_fornecedor_id, :pessoa_contacto, :telefone_contacto, :email_contacto, :principal, :observacoes)
            ON DUPLICATE KEY UPDATE
                tipo_relacao_fornecedor_id = VALUES(tipo_relacao_fornecedor_id),
                pessoa_contacto = VALUES(pessoa_contacto),
                telefone_contacto = VALUES(telefone_contacto),
                email_contacto = VALUES(email_contacto),
                principal = VALUES(principal),
                observacoes = VALUES(observacoes)
        ");

        $stmt->execute([
            ':equipamento_id' => $equipamento_id,
            ':fornecedor_id' => $fornecedor_id,
            ':tipo_relacao_fornecedor_id' => $tipo_relacao_id,
            ':pessoa_contacto' => valor_post('contacto_fornecedor_equipamento' . $sufixo),
            ':telefone_contacto' => valor_post('telefone_contacto_fornecedor_equipamento' . $sufixo),
            ':email_contacto' => valor_post('email_contacto_fornecedor_equipamento' . $sufixo),
            ':principal' => $principal ? 1 : 0,
            ':observacoes' => valor_post('observacoes_fornecedor_equipamento' . $sufixo) !== '' ? valor_post('observacoes_fornecedor_equipamento' . $sufixo) : null
        ]);

        return $fornecedor_id;
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao associar o Fornecedor ' . $numero . ' ao equipamento. Confirma se já executaste o SQL dos fornecedores associados.';
        return null;
    }
}

function guardar_fornecedores_bd()
{
    global $erros;

    $equipamento_id = equipamento_id_atual();

    if ($equipamento_id <= 0) {
        $erros[] = 'Não foi encontrado o equipamento criado na etapa Informação geral.';
        return false;
    }

    $ids = [];
    $total = total_fornecedores_formulario();

    for ($i = 1; $i <= $total; $i++) {
        $sufixo = $i === 1 ? '' : (string) $i;

        if ($i === 1 || valor_post('fornecedor_id_equipamento' . $sufixo) !== '') {
            $ids[$i] = guardar_fornecedor_individual_bd($sufixo, $i === 1, $i);
        }
    }

    if (!empty($erros)) {
        return false;
    }

    $_SESSION['novo_fornecedor_ids'] = $ids;

    return true;
}


function fornecedor_id_por_opcao($opcao)
{
    $ids = fornecedor_ids_sessao();

    if (preg_match('/Fornecedor associado ([0-9]+)/', $opcao, $matches)) {
        $indice = (int) $matches[1];
        return $ids[$indice] ?? null;
    }

    return null;
}


function fornecedores_associados_equipamento_atual()
{
    global $ligacao;

    $equipamento_id = equipamento_id_atual();

    if ($equipamento_id <= 0 || !$ligacao) {
        return [];
    }

    $stmt = $ligacao->prepare("
        SELECT
            f.id,
            f.codigo,
            f.nome,
            ef.principal
        FROM equipamento_fornecedores ef
        INNER JOIN fornecedores f
            ON ef.fornecedor_id = f.id
        WHERE ef.equipamento_id = :equipamento_id
        ORDER BY ef.principal DESC, f.codigo
    ");

    $stmt->execute([
        ':equipamento_id' => $equipamento_id
    ]);

    return $stmt->fetchAll();
}

function fornecedor_associado_ao_equipamento($fornecedor_id)
{
    global $ligacao;

    $equipamento_id = equipamento_id_atual();

    if ($equipamento_id <= 0 || (int) $fornecedor_id <= 0 || !$ligacao) {
        return false;
    }

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM equipamento_fornecedores
        WHERE equipamento_id = :equipamento_id
          AND fornecedor_id = :fornecedor_id
    ");

    $stmt->execute([
        ':equipamento_id' => $equipamento_id,
        ':fornecedor_id' => (int) $fornecedor_id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function fornecedor_id_documento($valor)
{
    $valor = trim((string) $valor);

    if ($valor === '' || $valor === '0') {
        return null;
    }

    $fornecedor_id = (int) $valor;

    if ($fornecedor_id <= 0 || !fornecedor_associado_ao_equipamento($fornecedor_id)) {
        return null;
    }

    return $fornecedor_id;
}

function guardar_ficheiro_pdf($campo, $prefixo)
{
    global $erros;

    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        $erros[] = 'Não foi possível carregar o ficheiro PDF.';
        return null;
    }

    $nome_original = $_FILES[$campo]['name'];
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

    if ($extensao !== 'pdf') {
        $erros[] = 'O ficheiro deve ser PDF.';
        return null;
    }

    $pasta = __DIR__ . '/../../assets/uploads/documentos/';

    if (!is_dir($pasta)) {
        mkdir($pasta, 0775, true);
    }

    $nome_seguro = $prefixo . '_' . date('YmdHis') . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $nome_original);

    $destino = $pasta . $nome_seguro;

    if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
        $erros[] = 'Não foi possível guardar o ficheiro PDF no servidor.';
        return null;
    }

    return 'assets/uploads/documentos/' . $nome_seguro;
}

function guardar_documento_bd($config)
{
    global $ligacao, $erros;

    $equipamento_id = equipamento_id_atual();

    $tipo_documento_id = obter_id_obrigatorio(
        'tipos_documento',
        $config['tipo'],
        'O tipo de documento ' . $config['tipo'] . ' não existe na base de dados.'
    );

    $estado_documento_id = obter_id_obrigatorio(
        'estados_documento',
        'Válido',
        'O estado de documento Válido não existe na base de dados.'
    );

    $fornecedor_id = fornecedor_id_documento(valor_post($config['fornecedor']));

    $ficheiro = guardar_ficheiro_pdf($config['ficheiro'], $config['prefixo']);

    if (!empty($erros)) {
        return null;
    }

    try {
        $stmt = $ligacao->prepare("
            INSERT INTO documentos
                (codigo, equipamento_id, fornecedor_id, tipo_documento_id, nome, data_documento, data_validade, estado_documento_id, ficheiro, responsavel_registo, observacoes)
            VALUES
                (:codigo, :equipamento_id, :fornecedor_id, :tipo_documento_id, :nome, :data_documento, :data_validade, :estado_documento_id, :ficheiro, NULL, NULL)
        ");

        $stmt->execute([
            ':codigo' => proximo_codigo('documentos', 'DOC'),
            ':equipamento_id' => $equipamento_id,
            ':fornecedor_id' => $fornecedor_id,
            ':tipo_documento_id' => $tipo_documento_id,
            ':nome' => valor_post($config['nome']),
            ':data_documento' => valor_post($config['data']),
            ':data_validade' => valor_post($config['validade']) !== '' ? valor_post($config['validade']) : null,
            ':estado_documento_id' => $estado_documento_id,
            ':ficheiro' => $ficheiro
        ]);

        return (int) $ligacao->lastInsertId();
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao guardar o documento ' . $config['tipo'] . '.';
        return null;
    }
}

function configuracoes_documentos()
{
    return [
        [
            'tipo' => 'Manual de utilizador',
            'prefixo' => 'manual_utilizador',
            'nome' => 'nome_manual_utilizador',
            'data' => 'data_manual_utilizador',
            'validade' => 'validade_manual_utilizador',
            'fornecedor' => 'fornecedor_manual_utilizador',
            'ficheiro' => 'ficheiro_manual_utilizador'
        ],
        [
            'tipo' => 'Manual de serviço',
            'prefixo' => 'manual_servico',
            'nome' => 'nome_manual_servico',
            'data' => 'data_manual_servico',
            'validade' => 'validade_manual_servico',
            'fornecedor' => 'fornecedor_manual_servico',
            'ficheiro' => 'ficheiro_manual_servico'
        ],
        [
            'tipo' => 'Certificado de calibração',
            'prefixo' => 'certificado',
            'nome' => 'nome_certificado',
            'data' => 'data_certificado',
            'validade' => 'validade_certificado',
            'fornecedor' => 'fornecedor_certificado',
            'ficheiro' => 'ficheiro_certificado'
        ],
        [
            'tipo' => 'Declaração de conformidade',
            'prefixo' => 'declaracao_conformidade',
            'nome' => 'nome_declaracao_conformidade',
            'data' => 'data_declaracao_conformidade',
            'validade' => 'validade_declaracao_conformidade',
            'fornecedor' => 'fornecedor_declaracao_conformidade',
            'ficheiro' => 'ficheiro_declaracao_conformidade'
        ],
        [
            'tipo' => 'Relatório técnico',
            'prefixo' => 'relatorio_tecnico',
            'nome' => 'nome_relatorio_tecnico',
            'data' => 'data_relatorio_tecnico',
            'validade' => 'validade_relatorio_tecnico',
            'fornecedor' => 'fornecedor_relatorio_tecnico',
            'ficheiro' => 'ficheiro_relatorio_tecnico'
        ],
        [
            'tipo' => 'Fatura ou guia de aquisição',
            'prefixo' => 'fatura_aquisicao',
            'nome' => 'nome_fatura_aquisicao',
            'data' => 'data_fatura_aquisicao',
            'validade' => 'validade_fatura_aquisicao',
            'fornecedor' => 'fornecedor_fatura_aquisicao',
            'ficheiro' => 'ficheiro_fatura_aquisicao'
        ]
    ];
}

function guardar_documentacao_bd()
{
    global $erros;

    if (equipamento_id_atual() <= 0) {
        $erros[] = 'Não foi encontrado o equipamento criado na etapa Informação geral.';
        return false;
    }

    $documento_ids = [];

    foreach (configuracoes_documentos() as $config) {
        $id = guardar_documento_bd($config);

        if ($id) {
            $documento_ids[$config['prefixo']] = $id;
        }
    }

    if (!empty($erros)) {
        return false;
    }

    $_SESSION['novo_documento_ids'] = $documento_ids;

    return true;
}

function guardar_contrato_bd()
{
    global $ligacao, $erros;

    $equipamento_id = equipamento_id_atual();
    $fornecedor_ids = fornecedor_ids_sessao();
    $fornecedor_id = $fornecedor_ids[1] ?? null;

    if ($equipamento_id <= 0) {
        $erros[] = 'Não foi encontrado o equipamento criado na etapa Informação geral.';
        return false;
    }

    if (!$fornecedor_id) {
        $erros[] = 'Não foi encontrado o fornecedor principal do equipamento.';
        return false;
    }

    $tipo_contrato_id = obter_id_obrigatorio(
        'tipos_contrato',
        mapear_valor_bd('tipo_contrato', valor_post('tipo_contrato')),
        'O tipo de contrato selecionado não existe na base de dados.'
    );

    $estado_contrato_id = obter_id_obrigatorio(
        'estados_contrato',
        valor_post('estado_garantia'),
        'O estado do contrato selecionado não existe na base de dados.'
    );

    if (!empty($erros)) {
        return false;
    }

    $documento_id = guardar_documento_bd([
        'tipo' => 'Contrato / garantia',
        'prefixo' => 'contrato_documento',
        'nome' => 'nome_contrato_documento',
        'data' => 'data_contrato_documento',
        'validade' => '',
        'fornecedor' => 'fornecedor_contrato_documento',
        'ficheiro' => 'ficheiro_contrato_documento'
    ]);

    if (!$documento_id || !empty($erros)) {
        return false;
    }

    $valor = str_replace(',', '.', valor_post('valor_contrato'));

    try {
        $stmt = $ligacao->prepare("
            INSERT INTO contratos
                (codigo, equipamento_id, fornecedor_id, tipo_contrato_id, data_inicio, data_fim, periodicidade, estado_contrato_id, valor, documento_id, observacoes)
            VALUES
                (:codigo, :equipamento_id, :fornecedor_id, :tipo_contrato_id, :data_inicio, :data_fim, :periodicidade, :estado_contrato_id, :valor, :documento_id, NULL)
        ");

        $stmt->execute([
            ':codigo' => proximo_codigo('contratos', 'CON'),
            ':equipamento_id' => $equipamento_id,
            ':fornecedor_id' => $fornecedor_id,
            ':tipo_contrato_id' => $tipo_contrato_id,
            ':data_inicio' => valor_post('data_inicio_garantia'),
            ':data_fim' => valor_post('data_fim_garantia'),
            ':periodicidade' => valor_post('periodicidade_manutencao'),
            ':estado_contrato_id' => $estado_contrato_id,
            ':valor' => $valor,
            ':documento_id' => $documento_id
        ]);

        $_SESSION['novo_contrato_id'] = (int) $ligacao->lastInsertId();

        $stmt = $ligacao->prepare("UPDATE equipamentos SET ativo = 1, observacoes = NULL WHERE id = :id");
        $stmt->execute([':id' => $equipamento_id]);

        return true;
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao guardar o Contrato.';
        return false;
    }
}


function classe_tab($etapa, $etapa_atual)
{
    return $etapa === $etapa_atual ? 'active' : 'disabled';
}

function atributo_tab($etapa, $etapa_atual)
{
    return $etapa === $etapa_atual ? '' : 'disabled';
}

function classe_pane($etapa, $etapa_atual)
{
    return $etapa === $etapa_atual ? 'show active' : '';
}

function marcar_etapa_ok($etapa)
{
    $_SESSION['novo_equipamento_etapas_ok'][$etapa] = true;
}

function etapa_ok($etapa)
{
    return !empty($_SESSION['novo_equipamento_etapas_ok'][$etapa]);
}

function limpar_erros()
{
    $GLOBALS['erros'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao_form'] ?? '';

    if ($acao === 'avancar_localizacao') {
        if (validar_informacao_geral() && guardar_informacao_geral_bd()) {
            marcar_etapa_ok('info');
            $etapa_atual = 'localizacao';
        } else {
            $etapa_atual = 'info';
        }
    } elseif ($acao === 'avancar_fornecedor') {
        if (validar_localizacao() && guardar_localizacao_bd()) {
            marcar_etapa_ok('localizacao');
            $etapa_atual = 'fornecedor';
        } else {
            $etapa_atual = 'localizacao';
        }
    } elseif ($acao === 'avancar_documentacao') {
        if (validar_fornecedor() && guardar_fornecedores_bd()) {
            marcar_etapa_ok('fornecedor');
            $etapa_atual = 'documentacao';
        } else {
            $etapa_atual = 'fornecedor';
        }
    } elseif ($acao === 'avancar_contratos') {
        if (validar_documentacao() && guardar_documentacao_bd()) {
            marcar_etapa_ok('documentacao');
            $etapa_atual = 'garantia';
        } else {
            $etapa_atual = 'documentacao';
        }
    } elseif ($acao === 'guardar_equipamento') {
        if (!etapa_ok('info')) {
            $erros[] = 'Valide primeiro a Informação geral.';
            $etapa_atual = 'info';
        } elseif (!etapa_ok('localizacao')) {
            $erros[] = 'Valide primeiro a Localização.';
            $etapa_atual = 'localizacao';
        } elseif (!etapa_ok('fornecedor')) {
            $erros[] = 'Valide primeiro o Fornecedor.';
            $etapa_atual = 'fornecedor';
        } elseif (!etapa_ok('documentacao')) {
            $erros[] = 'Valide primeiro a Documentação.';
            $etapa_atual = 'documentacao';
        } elseif (validar_contratos() && guardar_contrato_bd()) {
            marcar_etapa_ok('garantia');
            $etapa_atual = 'garantia';
            $sucesso_validacao = 'Equipamento registado com sucesso na base de dados.';
            limpar_sessao_novo_equipamento();
        } else {
            $etapa_atual = 'garantia';
        }
    }
}


$localizacoes_gerais = [];
$fornecedores_gerais = [];
$tipos_relacao_fornecedor = [];
$fornecedores_documentacao = [];

if ($ligacao) {
    try {
        $localizacoes_gerais = carregar_localizacoes_gerais();
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao carregar as localizações gerais. Confirma se já executaste o SQL das localizações gerais.';
    }

    try {
        $fornecedores_gerais = carregar_fornecedores_gerais();
        $tipos_relacao_fornecedor = carregar_tipos_relacao_fornecedor();
        $fornecedores_documentacao = fornecedores_associados_equipamento_atual();
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao carregar os fornecedores gerais. Confirma se já executaste o SQL dos fornecedores gerais.';
    }
}

include '../includes/header.php';
?>
<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Novo Equipamento</h1>
                <p>Registo completo de um novo equipamento médico no inventário hospitalar.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span><?php echo htmlspecialchars(nome_utilizador(), ENT_QUOTES, 'UTF-8'); ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    

                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <section class="backend-box">
            <div class="backend-section-header">
                <div>
                    <h2>Ficha do Equipamento</h2>
                    <p>Preencha cada etapa antes de avançar para a seguinte.</p>
                </div>

                <a href="novo.php?cancelar=1" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>
            <?php if (!empty($erros)) : ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Foram encontrados os seguintes erros:</strong>
                    <ul class="mb-0">
                        <?php foreach ($erros as $erro) : ?>
                            <li><?= h($erro) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($sucesso_validacao)) : ?>
                <div class="alert alert-success" role="alert">
                    <?= h($sucesso_validacao) ?>
                </div>
            <?php endif; ?>

            <form class="form-backend" id="formEquipamento" action="" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="etapa_atual" value="<?= h($etapa_atual) ?>">

                <ul class="nav nav-tabs mb-4 tabs-equipamento" id="tabsNovoEquipamento" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= classe_tab('info', $etapa_atual) ?>" id="info-tab" type="button" role="tab" <?= atributo_tab('info', $etapa_atual) ?>>
                            <i class="bi bi-info-circle"></i>
                            Informação geral
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= classe_tab('localizacao', $etapa_atual) ?>" id="localizacao-tab" type="button" role="tab" <?= atributo_tab('localizacao', $etapa_atual) ?>>
                            <i class="bi bi-geo-alt"></i>
                            Localização
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= classe_tab('fornecedor', $etapa_atual) ?>" id="fornecedor-tab" type="button" role="tab" <?= atributo_tab('fornecedor', $etapa_atual) ?>>
                            <i class="bi bi-truck"></i>
                            Fornecedor
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= classe_tab('documentacao', $etapa_atual) ?>" id="documentacao-tab" type="button" role="tab" <?= atributo_tab('documentacao', $etapa_atual) ?>>
                            <i class="bi bi-folder"></i>
                            Documentação
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= classe_tab('garantia', $etapa_atual) ?>" id="garantia-tab" type="button" role="tab" <?= atributo_tab('garantia', $etapa_atual) ?>>
                            <i class="bi bi-shield-check"></i>
                            Contratos
                        </button>
                    </li>
                </ul>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="textoProgressoEquipamento">
                            <?= h($dados_etapas[$etapa_atual]['texto']) ?>
                        </span>
                        <strong id="percentagemProgressoEquipamento"><?= h($dados_etapas[$etapa_atual]['percentagem']) ?>%</strong>
                    </div>

                    <div class="progress" role="progressbar" aria-label="Progresso do registo do equipamento" aria-valuenow="<?= h($dados_etapas[$etapa_atual]['percentagem']) ?>"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" id="barraProgressoEquipamento" style="width: <?= h($dados_etapas[$etapa_atual]['percentagem']) ?>%; background-color: #f28c8c;"></div>
                    </div>
                </div>

                <div class="tab-content" id="tabsNovoEquipamentoConteudo">

                    <div class="tab-pane fade <?= classe_pane('info', $etapa_atual) ?>" id="info" role="tabpanel" tabindex="0">
                        <h3>Informação geral</h3>

                        <div class="form-grid">
                            <div>
                                <label for="codigo">Código interno *</label>
                                <input type="text" id="codigo" name="codigo" placeholder="Ex: EQ006" value="<?= campo('codigo') ?>" required pattern="[Ee][Qq][0-9]{3}">
                            </div>

                            <div>
                                <label for="designacao">Designação *</label>
                                <input type="text" id="designacao" name="designacao" placeholder="Nome do equipamento" value="<?= campo('designacao') ?>" required>
                            </div>

                            <div>
                                <label for="categoria">Categoria *</label>
                                <select id="categoria" name="categoria_equipamento_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="Monitorização" <?= selecionado('categoria_equipamento_id', 'Monitorização') ?>>Monitorização</option>
                                    <option value="Suporte de vida" <?= selecionado('categoria_equipamento_id', 'Suporte de vida') ?>>Suporte de vida</option>
                                    <option value="Terapia" <?= selecionado('categoria_equipamento_id', 'Terapia') ?>>Terapia</option>
                                    <option value="Diagnóstico" <?= selecionado('categoria_equipamento_id', 'Diagnóstico') ?>>Diagnóstico</option>
                                    <option value="Laboratório" <?= selecionado('categoria_equipamento_id', 'Laboratório') ?>>Laboratório</option>
                                    <option value="Esterilização" <?= selecionado('categoria_equipamento_id', 'Esterilização') ?>>Esterilização</option>
                                </select>
                            </div>

                            <div>
                                <label for="marca">Marca *</label>
                                <input type="text" id="marca" name="marca" placeholder="Ex: Philips" value="<?= campo('marca') ?>" required>
                            </div>

                            <div>
                                <label for="modelo">Modelo *</label>
                                <input type="text" id="modelo" name="modelo" placeholder="Ex: IntelliVue MP5" value="<?= campo('modelo') ?>" required>
                            </div>

                            <div>
                                <label for="serie">Número de série *</label>
                                <input type="text" id="serie" name="numero_serie" placeholder="Ex: MP5-2022-45873" value="<?= campo('numero_serie') ?>" required>
                            </div>

                            <div>
                                <label for="fabricante">Fabricante *</label>
                                <input type="text" id="fabricante" name="fabricante" placeholder="Ex: Philips Healthcare" value="<?= campo('fabricante') ?>" required>
                            </div>

                            <div>
                                <label for="anoFabrico">Ano de fabrico *</label>
                                <input type="number" id="anoFabrico" name="ano_fabrico" placeholder="Ex: 2024" value="<?= campo('ano_fabrico') ?>" required min="1990" max="2027">
                            </div>

                            <div>
                                <label for="estado">Estado atual *</label>
                                <select id="estado" name="estado_equipamento_id" required>
                                    <option value="">Selecione o estado</option>
                                    <option value="Ativo" <?= selecionado('estado_equipamento_id', 'Ativo') ?>>Ativo</option>
                                    <option value="Em manutenção" <?= selecionado('estado_equipamento_id', 'Em manutenção') ?>>Em manutenção</option>
                                    <option value="Em calibração" <?= selecionado('estado_equipamento_id', 'Em calibração') ?>>Em calibração</option>
                                    <option value="Em quarentena" <?= selecionado('estado_equipamento_id', 'Em quarentena') ?>>Em quarentena</option>
                                </select>
                            </div>

                            <div>
                                <label for="criticidade">Criticidade *</label>
                                <select id="criticidade" name="criticidade_id" required>
                                    <option value="">Selecione a criticidade</option>
                                    <option value="Baixa" <?= selecionado('criticidade_id', 'Baixa') ?>>Baixa</option>
                                    <option value="Média" <?= selecionado('criticidade_id', 'Média') ?>>Média</option>
                                    <option value="Alta" <?= selecionado('criticidade_id', 'Alta') ?>>Alta</option>
                                    <option value="Suporte de vida" <?= selecionado('criticidade_id', 'Suporte de vida') ?>>Suporte de vida</option>
                                </select>
                            </div>

                            <div>
                                <label for="prioridadeManutencao">Prioridade de manutenção *</label>
                                <select id="prioridadeManutencao" name="prioridade_manutencao_id" required>
                                    <option value="">Selecione a prioridade</option>
                                    <?php foreach ($prioridades_manutencao as $opcao) : ?>
                                        <?php $selecionado = ((string) ($dados['prioridade_manutencao_id'] ?? ($equipamento->prioridade_manutencao_id ?? '')) === (string) $opcao->id) ? 'selected' : ''; ?>
                                        <option value="<?= $opcao->id ?>" <?= $selecionado ?>>
                                            <?= htmlspecialchars($opcao->nome, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="utilizacaoClinica">Utilização clínica *</label>
                                <select id="utilizacaoClinica" name="utilizacao_clinica" required>
                                    <option value="">Selecione a utilização clínica</option>
                                    <option value="Monitorização contínua" <?= selecionado('utilizacao_clinica', 'Monitorização contínua') ?>>Monitorização contínua</option>
                                    <option value="Suporte ventilatório" <?= selecionado('utilizacao_clinica', 'Suporte ventilatório') ?>>Suporte ventilatório</option>
                                    <option value="Terapia de infusão" <?= selecionado('utilizacao_clinica', 'Terapia de infusão') ?>>Terapia de infusão</option>
                                    <option value="Diagnóstico" <?= selecionado('utilizacao_clinica', 'Diagnóstico') ?>>Diagnóstico</option>
                                    <option value="Reanimação" <?= selecionado('utilizacao_clinica', 'Reanimação') ?>>Reanimação</option>
                                    <option value="Esterilização" <?= selecionado('utilizacao_clinica', 'Esterilização') ?>>Esterilização</option>
                                    <option value="Outro" <?= selecionado('utilizacao_clinica', 'Outro') ?>>Outro</option>
                                </select>
                            </div>

                            <div>
                                <label for="dataAquisicao">Data de aquisição *</label>
                                <input type="date" id="dataAquisicao" name="data_aquisicao" value="<?= campo('data_aquisicao') ?>" required>
                            </div>

                            <div>
                                <label for="custo">Custo de aquisição *</label>
                                <input type="number" id="custo" name="custo_aquisicao" placeholder="Ex: 4850" value="<?= campo('custo_aquisicao') ?>" required min="0" step="0.01">
                            </div>

                            <div>
                                <label for="tipoEntrada">Tipo de entrada *</label>
                                <select id="tipoEntrada" name="tipo_entrada_id" required>
                                    <option value="">Selecione o tipo de entrada</option>
                                    <?php foreach ($tipos_entrada as $opcao) : ?>
                                        <?php $selecionado = ((string) ($dados['tipo_entrada_id'] ?? ($equipamento->tipo_entrada_id ?? '')) === (string) $opcao->id) ? 'selected' : ''; ?>
                                        <option value="<?= $opcao->id ?>" <?= $selecionado ?>>
                                            <?= htmlspecialchars($opcao->nome, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-full">
                            <hr>
                            <h3>Relações e consumíveis</h3>
                            <p>Indique se este equipamento pertence a outro equipamento e se utiliza consumíveis.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="componenteOutroEquipamento">É componente de outro equipamento? *</label>
                                <select id="componenteOutroEquipamento" name="componente_outro_equipamento" required>
                                    <option value="Não" <?= selecionado('componente_outro_equipamento', 'Não') ?>>Não</option>
                                    <option value="Sim" <?= selecionado('componente_outro_equipamento', 'Sim') ?>>Sim</option>
                                </select>
                            </div>

                            <div>
                                <label for="equipamentoPrincipal">Equipamento principal</label>
                                <select id="equipamentoPrincipal" name="equipamento_principal" <?= selecionado('componente_outro_equipamento', 'Sim') ? '' : 'disabled' ?>
                                    <option value="">Selecione o equipamento principal</option>
                                    <?php foreach ($equipamentos_principais as $equipamento_principal) : ?>
                                        <?php
                                            $nome_equipamento_principal = trim(
                                                ($equipamento_principal->designacao ?? '') . ' ' .
                                                ($equipamento_principal->marca ?? '') . ' ' .
                                                ($equipamento_principal->modelo ?? '')
                                            );

                                            $selecionado_principal = ((string) ($dados['equipamento_principal_id'] ?? '') === (string) $equipamento_principal->id) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $equipamento_principal->id ?>" <?= $selecionado_principal ?>>
                                            <?= htmlspecialchars($nome_equipamento_principal, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="temConsumiveis">Tem consumíveis? *</label>
                                <select id="temConsumiveis" name="tem_consumiveis" required>
                                    <option value="Não" <?= selecionado('tem_consumiveis', 'Não') ?>>Não</option>
                                    <option value="Sim" <?= selecionado('tem_consumiveis', 'Sim') ?>>Sim</option>
                                </select>
                            </div>

                            <div>
                                <label for="consumiveisEquipamento">Consumíveis utilizados</label>
                                <input type="text" id="consumiveisEquipamento" placeholder="Ex: sensores, filtros, cabos, baterias" <?= selecionado('tem_consumiveis', 'Sim') ? '' : 'disabled' ?> name="consumiveis_equipamento" value="<?= campo('consumiveis_equipamento') ?>">
                            </div>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="submit" class="btn-backend" name="acao_form" value="avancar_localizacao" formnovalidate>
                                Próximo: Localização
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= classe_pane('localizacao', $etapa_atual) ?>" id="localizacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Localização</h3>

                        <div class="form-grid">
                            <div>
                                <label for="localizacaoGeralEquipamento">Localização geral *</label>
                                <select id="localizacaoGeralEquipamento" name="localizacao_id" required>
                                    <option value="">Selecione uma localização geral</option>
                                    <?php foreach ($localizacoes_gerais as $localizacao_geral) : ?>
                                        <option value="<?= h($localizacao_geral->id) ?>" <?= selecionado('localizacao_id', $localizacao_geral->id) ?>>
                                            <?= h($localizacao_geral->codigo) ?> - <?= h($localizacao_geral->edificio) ?>
                                            <?php if ($localizacao_geral->numero_pisos !== null && $localizacao_geral->numero_pisos !== '') : ?>
                                                (<?= h($localizacao_geral->numero_pisos) ?> pisos)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="pisoEquipamento">Piso do equipamento *</label>
                                <input type="text" id="pisoEquipamento" placeholder="Ex: 2" inputmode="numeric" pattern="[0-9]*" maxlength="3" name="piso_equipamento" value="<?= campo('piso_equipamento') ?>" required>
                            </div>

                            <div>
                                <label for="servicoEquipamento">Serviço *</label>
                                <select id="servicoEquipamento" name="servico_equipamento" required>
                                    <option value="">Selecione o serviço</option>
                                    <option value="UCI" <?= selecionado('servico_equipamento', 'UCI') ?>>UCI</option>
                                    <option value="Bloco Operatório" <?= selecionado('servico_equipamento', 'Bloco Operatório') ?>>Bloco Operatório</option>
                                    <option value="Medicina Interna" <?= selecionado('servico_equipamento', 'Medicina Interna') ?>>Medicina Interna</option>
                                    <option value="Urgência" <?= selecionado('servico_equipamento', 'Urgência') ?>>Urgência</option>
                                    <option value="Imagiologia" <?= selecionado('servico_equipamento', 'Imagiologia') ?>>Imagiologia</option>
                                    <option value="Laboratório" <?= selecionado('servico_equipamento', 'Laboratório') ?>>Laboratório</option>
                                    <option value="Esterilização" <?= selecionado('servico_equipamento', 'Esterilização') ?>>Esterilização</option>
                                    <option value="Consulta Externa" <?= selecionado('servico_equipamento', 'Consulta Externa') ?>>Consulta Externa</option>
                                </select>
                            </div>

                            <div>
                                <label for="salaEquipamento">Sala / Unidade *</label>
                                <input type="text" id="salaEquipamento" placeholder="Ex: Sala 201" name="sala_equipamento" value="<?= campo('sala_equipamento') ?>" required>
                            </div>
                        </div>

                        <p class="mt-3">
                            A localização geral traz do módulo Localizações os dados do edifício, número de pisos, responsável, contacto e estado.
                            Aqui defines apenas onde o equipamento fica dentro dessa localização.
                        </p>

                        <div class="form-botoes mt-4">
                            <button type="submit" class="btn-backend" name="acao_form" value="avancar_fornecedor" formnovalidate>
                                Próximo: Fornecedor
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= classe_pane('fornecedor', $etapa_atual) ?>" id="fornecedor-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Fornecedores associados</h3>
                        <p>Associe fornecedores já criados no módulo Fornecedores. O primeiro fornecedor é obrigatório. Pode adicionar mais fornecedores se necessário.</p>

                        <?php
                            $total_fornecedores = total_fornecedores_formulario();
                        ?>

                        <div id="fornecedoresAssociadosContainer">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <?php
                                    $sufixo = $i === 1 ? '' : (string) $i;
                                    $obrigatorio_fornecedor = $i === 1;
?>

                                <div class="backend-box-secundaria mb-3 fornecedor-bloco" data-fornecedor-bloco="<?= h($i) ?>">
                                    <h4>Fornecedor associado <?= h($i) ?><?= $obrigatorio_fornecedor ? ' *' : '' ?></h4>

                                    <?php if ($i > 1) : ?>
                                        <p>Opcional. Preencha apenas se existir outro fornecedor associado ao equipamento.</p>
                                    <?php endif; ?>

                                    <div class="form-grid">
                                        <div>
                                            <label for="fornecedorEquipamento<?= h($sufixo) ?>">Fornecedor<?= $obrigatorio_fornecedor ? ' *' : '' ?></label>
                                            <select id="fornecedorEquipamento<?= h($sufixo) ?>" name="fornecedor_id_equipamento<?= h($sufixo) ?>" <?= $obrigatorio_fornecedor ? 'required' : '' ?>>
                                                <option value="">Selecione um fornecedor já criado</option>
                                                <?php foreach ($fornecedores_gerais as $fornecedor_geral) : ?>
                                                    <option value="<?= h($fornecedor_geral->id) ?>" <?= selecionado('fornecedor_id_equipamento' . $sufixo, $fornecedor_geral->id) ?>>
                                                        <?= h($fornecedor_geral->codigo) ?> - <?= h($fornecedor_geral->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="tipoFornecedorEquipamento<?= h($sufixo) ?>">Tipo de fornecedor / relação<?= $obrigatorio_fornecedor ? ' *' : '' ?></label>
                                            <select id="tipoFornecedorEquipamento<?= h($sufixo) ?>" name="tipo_relacao_fornecedor_id_equipamento<?= h($sufixo) ?>" <?= $obrigatorio_fornecedor ? 'required' : '' ?>>
                                                <option value="">Selecione o tipo</option>
                                                <?php foreach ($tipos_relacao_fornecedor as $tipo_relacao) : ?>
                                                    <option value="<?= h($tipo_relacao->id) ?>" <?= selecionado('tipo_relacao_fornecedor_id_equipamento' . $sufixo, $tipo_relacao->id) ?>>
                                                        <?= h($tipo_relacao->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="contactoFornecedorEquipamento<?= h($sufixo) ?>">Pessoa de contacto<?= $obrigatorio_fornecedor ? ' *' : '' ?></label>
                                            <input type="text" id="contactoFornecedorEquipamento<?= h($sufixo) ?>" placeholder="Ex: Ana Silva" name="contacto_fornecedor_equipamento<?= h($sufixo) ?>" value="<?= campo('contacto_fornecedor_equipamento' . $sufixo) ?>" <?= $obrigatorio_fornecedor ? 'required' : '' ?>>
                                        </div>

                                        <div>
                                            <label for="telefoneContactoFornecedorEquipamento<?= h($sufixo) ?>">Telefone da pessoa de contacto<?= $obrigatorio_fornecedor ? ' *' : '' ?></label>
                                            <input type="tel" id="telefoneContactoFornecedorEquipamento<?= h($sufixo) ?>" placeholder="Ex: +351 912 000 000" inputmode="tel" name="telefone_contacto_fornecedor_equipamento<?= h($sufixo) ?>" value="<?= campo('telefone_contacto_fornecedor_equipamento' . $sufixo) ?>" <?= $obrigatorio_fornecedor ? 'required' : '' ?>>
                                        </div>

                                        <div>
                                            <label for="emailContactoFornecedorEquipamento<?= h($sufixo) ?>">Email da pessoa de contacto<?= $obrigatorio_fornecedor ? ' *' : '' ?></label>
                                            <input type="email" id="emailContactoFornecedorEquipamento<?= h($sufixo) ?>" placeholder="Ex: contacto@empresa.pt" name="email_contacto_fornecedor_equipamento<?= h($sufixo) ?>" value="<?= campo('email_contacto_fornecedor_equipamento' . $sufixo) ?>" <?= $obrigatorio_fornecedor ? 'required' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="form-full">
                                        <label for="observacoesFornecedorEquipamento<?= h($sufixo) ?>">Observações da relação</label>
                                        <textarea id="observacoesFornecedorEquipamento<?= h($sufixo) ?>" rows="3" placeholder="Observações específicas sobre a relação deste fornecedor com o equipamento" name="observacoes_fornecedor_equipamento<?= h($sufixo) ?>"><?= campo('observacoes_fornecedor_equipamento' . $sufixo) ?></textarea>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="submit" class="btn-backend" name="acao_form" value="avancar_documentacao" formnovalidate>
                                Próximo: Documentação
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>



                    <div class="tab-pane fade <?= classe_pane('documentacao', $etapa_atual) ?>" id="documentacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Documentação</h3>
                        <p>Associe os documentos PDF ao equipamento. Para cada documento, indique o tipo, nome, data, validade quando aplicável, fornecedor associado ou a opção Sem fornecedor associado, e ficheiro.</p>

                        <div class="detalhe-card mb-4">
                            <h3>Manual de utilizador</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeManualUtilizadorEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeManualUtilizadorEquipamento" placeholder="Ex: Manual de utilizador Philips IntelliVue MP5" required name="nome_manual_utilizador" value="<?= campo('nome_manual_utilizador') ?>">
                                </div>

                                <div>
                                    <label for="dataManualUtilizadorEquipamento">Data do documento *</label>
                                    <input type="date" id="dataManualUtilizadorEquipamento" required name="data_manual_utilizador" value="<?= campo('data_manual_utilizador') ?>">
                                </div>

                                <div>
                                    <label for="validadeManualUtilizadorEquipamento">Data de validade</label>
                                    <input type="date" id="validadeManualUtilizadorEquipamento" name="validade_manual_utilizador" value="<?= campo('validade_manual_utilizador') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorManualUtilizadorEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorManualUtilizadorEquipamento" name="fornecedor_manual_utilizador" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_manual_utilizador', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="manualEquipamento">PDF do manual de utilizador *</label>
                                    <input type="file" id="manualEquipamento" accept=".pdf,application/pdf" required name="ficheiro_manual_utilizador">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Manual de serviço</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeManualServicoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeManualServicoEquipamento" placeholder="Ex: Manual de serviço Philips IntelliVue MP5" required name="nome_manual_servico" value="<?= campo('nome_manual_servico') ?>">
                                </div>

                                <div>
                                    <label for="dataManualServicoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataManualServicoEquipamento" required name="data_manual_servico" value="<?= campo('data_manual_servico') ?>">
                                </div>

                                <div>
                                    <label for="validadeManualServicoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeManualServicoEquipamento" name="validade_manual_servico" value="<?= campo('validade_manual_servico') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorManualServicoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorManualServicoEquipamento" name="fornecedor_manual_servico" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_manual_servico', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="manualServicoEquipamento">PDF do manual de serviço *</label>
                                    <input type="file" id="manualServicoEquipamento" accept=".pdf,application/pdf" required name="ficheiro_manual_servico">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Certificado de calibração</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeCertificadoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeCertificadoEquipamento" placeholder="Ex: Certificado de calibração 2026" required name="nome_certificado" value="<?= campo('nome_certificado') ?>">
                                </div>

                                <div>
                                    <label for="dataCertificadoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataCertificadoEquipamento" required name="data_certificado" value="<?= campo('data_certificado') ?>">
                                </div>

                                <div>
                                    <label for="validadeCertificadoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeCertificadoEquipamento" name="validade_certificado" value="<?= campo('validade_certificado') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorCertificadoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorCertificadoEquipamento" name="fornecedor_certificado" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_certificado', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="certificadoEquipamento">PDF do certificado de calibração *</label>
                                    <input type="file" id="certificadoEquipamento" accept=".pdf,application/pdf" required name="ficheiro_certificado">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Declaração de conformidade</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeDeclaracaoConformidadeEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeDeclaracaoConformidadeEquipamento" placeholder="Ex: Declaração de conformidade CE" required name="nome_declaracao_conformidade" value="<?= campo('nome_declaracao_conformidade') ?>">
                                </div>

                                <div>
                                    <label for="dataDeclaracaoConformidadeEquipamento">Data do documento *</label>
                                    <input type="date" id="dataDeclaracaoConformidadeEquipamento" required name="data_declaracao_conformidade" value="<?= campo('data_declaracao_conformidade') ?>">
                                </div>

                                <div>
                                    <label for="validadeDeclaracaoConformidadeEquipamento">Data de validade</label>
                                    <input type="date" id="validadeDeclaracaoConformidadeEquipamento" name="validade_declaracao_conformidade" value="<?= campo('validade_declaracao_conformidade') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorDeclaracaoConformidadeEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorDeclaracaoConformidadeEquipamento" name="fornecedor_declaracao_conformidade" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_declaracao_conformidade', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="declaracaoConformidadeEquipamento">PDF da declaração de conformidade *</label>
                                    <input type="file" id="declaracaoConformidadeEquipamento" accept=".pdf,application/pdf" required name="ficheiro_declaracao_conformidade">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Relatório técnico</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeRelatorioTecnicoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeRelatorioTecnicoEquipamento" placeholder="Ex: Relatório técnico de instalação" required name="nome_relatorio_tecnico" value="<?= campo('nome_relatorio_tecnico') ?>">
                                </div>

                                <div>
                                    <label for="dataRelatorioTecnicoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataRelatorioTecnicoEquipamento" required name="data_relatorio_tecnico" value="<?= campo('data_relatorio_tecnico') ?>">
                                </div>

                                <div>
                                    <label for="validadeRelatorioTecnicoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeRelatorioTecnicoEquipamento" name="validade_relatorio_tecnico" value="<?= campo('validade_relatorio_tecnico') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorRelatorioTecnicoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorRelatorioTecnicoEquipamento" name="fornecedor_relatorio_tecnico" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_relatorio_tecnico', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="relatorioTecnicoEquipamento">PDF do relatório técnico *</label>
                                    <input type="file" id="relatorioTecnicoEquipamento" accept=".pdf,application/pdf" required name="ficheiro_relatorio_tecnico">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Fatura ou guia de aquisição</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeFaturaAquisicaoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeFaturaAquisicaoEquipamento" placeholder="Ex: Fatura de aquisição do equipamento" required name="nome_fatura_aquisicao" value="<?= campo('nome_fatura_aquisicao') ?>">
                                </div>

                                <div>
                                    <label for="dataFaturaAquisicaoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataFaturaAquisicaoEquipamento" required name="data_fatura_aquisicao" value="<?= campo('data_fatura_aquisicao') ?>">
                                </div>

                                <div>
                                    <label for="validadeFaturaAquisicaoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeFaturaAquisicaoEquipamento" name="validade_fatura_aquisicao" value="<?= campo('validade_fatura_aquisicao') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorFaturaAquisicaoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorFaturaAquisicaoEquipamento" name="fornecedor_fatura_aquisicao" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_fatura_aquisicao', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="faturaAquisicaoEquipamento">PDF da fatura ou guia de aquisição *</label>
                                    <input type="file" id="faturaAquisicaoEquipamento" accept=".pdf,application/pdf" required name="ficheiro_fatura_aquisicao">
                                </div>
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="observacoes">Observações</label>
                            <textarea id="observacoes" rows="4" placeholder="Observações adicionais sobre o equipamento"></textarea>
                        </div>

                        <div class="form-botoes mt-4">
<button type="submit" class="btn-backend" name="acao_form" value="avancar_contratos" formnovalidate>
                                Próximo: Contratos
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= classe_pane('garantia', $etapa_atual) ?>" id="garantia-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Contratos</h3>
                        <p>Associe a garantia ou contrato ao equipamento.</p>

<div class="detalhe-card mb-4">
                            <h3>Contrato / garantia</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeContratoDocumentoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeContratoDocumentoEquipamento" placeholder="Ex: Contrato de garantia e manutenção" required name="nome_contrato_documento" value="<?= campo('nome_contrato_documento') ?>">
                                </div>

                                <div>
                                    <label for="dataContratoDocumentoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataContratoDocumentoEquipamento" required name="data_contrato_documento" value="<?= campo('data_contrato_documento') ?>">
                                </div>

                                <div>
                                    <label for="fornecedorContratoDocumentoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorContratoDocumentoEquipamento" name="fornecedor_contrato_documento" required>
                                        <option value="">Selecione uma opção</option>
                                            <option value="0">Sem fornecedor associado</option>
                                        <?php foreach ($fornecedores_documentacao as $fornecedor_documento) : ?>
                                            <option value="<?= h($fornecedor_documento->id) ?>" <?= selecionado('fornecedor_contrato_documento', $fornecedor_documento->id) ?>>
                                                <?= h($fornecedor_documento->codigo) ?> - <?= h($fornecedor_documento->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="contratoDocumentoEquipamento">PDF do contrato / garantia *</label>
                                    <input type="file" id="contratoDocumentoEquipamento" accept=".pdf,application/pdf" required name="ficheiro_contrato_documento">
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-grid">
                            <div>
                                <label for="estadoGarantiaEquipamento">Estado do contrato / garantia *</label>
                                <select id="estadoGarantiaEquipamento" required name="estado_garantia">
                                    <option value="">Selecione o estado</option>
                                    <option value="Ativo" <?= selecionado('estado_garantia', 'Ativo') ?>>Ativo</option>
                                    <option value="A terminar" <?= selecionado('estado_garantia', 'A terminar') ?>>A terminar</option>
                                    <option value="Expirado" <?= selecionado('estado_garantia', 'Expirado') ?>>Expirado</option>
                                    <option value="Sem garantia registada" <?= selecionado('estado_garantia', 'Sem garantia registada') ?>>Sem garantia registada</option>
                                </select>
                            </div>

                            <div>
                                <label for="dataInicioGarantiaEquipamento">Data de início da garantia *</label>
                                <input type="date" id="dataInicioGarantiaEquipamento" name="data_inicio_garantia" value="<?= campo('data_inicio_garantia') ?>" required>
                            </div>

                            <div>
                                <label for="dataFimGarantiaEquipamento">Data de fim da garantia *</label>
                                <input type="date" id="dataFimGarantiaEquipamento" name="data_fim_garantia" value="<?= campo('data_fim_garantia') ?>" required>
                            </div>

                            <div>
                                <label for="tipoContratoEquipamento">Tipo de contrato *</label>
                                <select id="tipoContratoEquipamento" name="tipo_contrato" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Garantia" <?= selecionado('tipo_contrato', 'Garantia') ?>>Garantia</option>
                                    <option value="Manutenção preventiva" <?= selecionado('tipo_contrato', 'Manutenção preventiva') ?>>Manutenção preventiva</option>
                                    <option value="Assistência técnica" <?= selecionado('tipo_contrato', 'Assistência técnica') ?>>Assistência técnica</option>
                                </select>
                            </div>

                            <div>
                                <label for="periodicidadeManutencaoEquipamento">Periodicidade de manutenção *</label>
                                <select id="periodicidadeManutencaoEquipamento" name="periodicidade_manutencao" required>
                                    <option value="">Selecione a periodicidade</option>
                                    <option value="Não aplicável" <?= selecionado('periodicidade_manutencao', 'Não aplicável') ?>>Não aplicável</option>
                                    <option value="Mensal" <?= selecionado('periodicidade_manutencao', 'Mensal') ?>>Mensal</option>
                                    <option value="Trimestral" <?= selecionado('periodicidade_manutencao', 'Trimestral') ?>>Trimestral</option>
                                    <option value="Semestral" <?= selecionado('periodicidade_manutencao', 'Semestral') ?>>Semestral</option>
                                    <option value="Anual" <?= selecionado('periodicidade_manutencao', 'Anual') ?>>Anual</option>
                                </select>
                            </div>

                            <div>
                                <label for="valorContratoEquipamento">Valor associado *</label>
                                <input type="number" id="valorContratoEquipamento" placeholder="Ex: 1200" name="valor_contrato" value="<?= campo('valor_contrato') ?>" required>
                            </div>
                        </div>


                    </div>

                </div>

                <p id="mensagemEquipamento" class="mensagem-login mt-4"></p>

                <div class="form-botoes">
                    <?php if ($etapa_atual === 'garantia') : ?>
                        <button type="submit" class="btn-backend" name="acao_form" value="guardar_equipamento" formnovalidate>
                            <i class="bi bi-check-circle"></i> Guardar equipamento
                        </button>
                    <?php endif; ?>

                    <a href="novo.php?cancelar=1" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>