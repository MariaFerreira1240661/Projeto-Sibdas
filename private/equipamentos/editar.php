<?php
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/validacoes.php';

redirect_if_not_logged();

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_atual = 'equipamentos';
$erros = [];
$sucesso = '';
$aba_ativa = 'info';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function classe_aba_edicao($aba, $tipo = 'botao')
{
    global $aba_ativa;

    if ($aba !== $aba_ativa) {
        return '';
    }

    return $tipo === 'painel' ? 'show active' : 'active';
}

function valor_post($nome)
{
    return trim((string) ($_POST[$nome] ?? ''));
}

function campo_equipamento($nome_post, $campo_bd = null)
{
    global $equipamento;

    if (isset($_POST[$nome_post])) {
        return h($_POST[$nome_post]);
    }

    $campo = $campo_bd ?: $nome_post;

    return h($equipamento->$campo ?? '');
}

function selecionado_valor($nome_post, $valor, $campo_bd = null)
{
    global $equipamento;

    $campo = $campo_bd ?: $nome_post;
    $atual = $_POST[$nome_post] ?? ($equipamento->$campo ?? '');

    return (string) $atual === (string) $valor ? 'selected' : '';
}

function selecionado_bool_sim_nao($nome_post, $valor, $campo_bd)
{
    global $equipamento;

    if (isset($_POST[$nome_post])) {
        $atual = $_POST[$nome_post];
    } else {
        $atual = !empty($equipamento->$campo_bd) ? 'Sim' : 'Não';
    }

    return (string) $atual === (string) $valor ? 'selected' : '';
}

function mapear_valor_bd_edicao($tipo, $valor)
{
    $mapas = [
        'categoria' => [
            'Laboratório' => 'Outro',
            'Esterilização' => 'Outro'
        ],
        'estado_equipamento' => [
            'Em quarentena' => 'Inativo',
            'Abatido' => 'Inativo'
        ],
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
        ]
    ];

    return $mapas[$tipo][$valor] ?? $valor;
}

function obter_id_por_nome_edicao($tabela, $nome)
{
    global $ligacao;

    $tabelas_permitidas = [
        'categorias_equipamento',
        'estados_equipamento',
        'criticidades',
        'prioridades_manutencao',
        'tipos_entrada'
    ];

    if (!in_array($tabela, $tabelas_permitidas, true)) {
        return 0;
    }

    $stmt = $ligacao->prepare("SELECT id FROM {$tabela} WHERE nome = :nome LIMIT 1");
    $stmt->execute([
        ':nome' => $nome
    ]);

    return (int) $stmt->fetchColumn();
}

function obter_id_obrigatorio_edicao($tabela, $nome, $mensagem)
{
    global $erros;

    $id = obter_id_por_nome_edicao($tabela, $nome);

    if ($id <= 0) {
        $erros[] = $mensagem;
    }

    return $id;
}

function carregar_localizacoes_gerais_edicao()
{
    global $ligacao;

    return $ligacao->query("
        SELECT
            l.id,
            l.codigo,
            l.edificio,
            COALESCE(l.numero_pisos, l.piso) AS numero_pisos
        FROM localizacoes l
        INNER JOIN estados_localizacao el
            ON l.estado_localizacao_id = el.id
        WHERE l.codigo <> 'LOC-TMP'
          AND el.nome NOT IN ('Inativa', 'Inativo')
        ORDER BY l.codigo
    ")->fetchAll();
}

function obter_localizacao_geral_edicao($id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT
            l.id,
            l.codigo,
            l.edificio,
            COALESCE(l.numero_pisos, l.piso) AS numero_pisos
        FROM localizacoes l
        INNER JOIN estados_localizacao el
            ON l.estado_localizacao_id = el.id
        WHERE l.id = :id
          AND l.codigo <> 'LOC-TMP'
          AND el.nome NOT IN ('Inativa', 'Inativo')
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    return $stmt->fetch();
}

function carregar_fornecedores_gerais_edicao()
{
    global $ligacao;

    return $ligacao->query("
        SELECT id, codigo, nome
        FROM fornecedores
        WHERE ativo = 1
        ORDER BY codigo
    ")->fetchAll();
}

function carregar_tipos_relacao_fornecedor_edicao()
{
    global $ligacao;

    return $ligacao->query("
        SELECT id, nome
        FROM tipos_relacao_fornecedor
        ORDER BY nome
    ")->fetchAll();
}

function carregar_equipamentos_principais_edicao($id_atual)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT id, codigo, designacao
        FROM equipamentos
        WHERE ativo = 1
          AND id <> :id
        ORDER BY codigo
    ");

    $stmt->execute([
        ':id' => $id_atual
    ]);

    return $stmt->fetchAll();
}

function codigo_equipamento_existe_edicao($codigo, $id_atual)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM equipamentos
        WHERE codigo = :codigo
          AND id <> :id
    ");

    $stmt->execute([
        ':codigo' => $codigo,
        ':id' => $id_atual
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function numero_serie_existe_edicao($numero_serie, $id_atual)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM equipamentos
        WHERE numero_serie = :numero_serie
          AND id <> :id
    ");

    $stmt->execute([
        ':numero_serie' => $numero_serie,
        ':id' => $id_atual
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function carregar_fornecedores_associados_edicao($equipamento_id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT
            ef.fornecedor_id,
            ef.tipo_relacao_fornecedor_id,
            ef.pessoa_contacto,
            ef.telefone_contacto,
            ef.email_contacto,
            ef.observacoes,
            ef.principal,
            f.codigo AS fornecedor_codigo,
            f.nome AS fornecedor_nome
        FROM equipamento_fornecedores ef
        INNER JOIN fornecedores f
            ON ef.fornecedor_id = f.id
        WHERE ef.equipamento_id = :equipamento_id
          AND f.ativo = 1
        ORDER BY ef.principal DESC, f.codigo
        LIMIT 3
    ");

    $stmt->execute([
        ':equipamento_id' => $equipamento_id
    ]);

    return $stmt->fetchAll();
}

function carregar_documentos_equipamento_edicao($equipamento_id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT
            d.*,
            td.nome AS tipo_documento_nome,
            ed.nome AS estado_documento_nome,
            f.codigo AS fornecedor_codigo,
            f.nome AS fornecedor_nome
        FROM documentos d
        LEFT JOIN tipos_documento td ON d.tipo_documento_id = td.id
        LEFT JOIN estados_documento ed ON d.estado_documento_id = ed.id
        LEFT JOIN fornecedores f ON d.fornecedor_id = f.id
        WHERE d.equipamento_id = :equipamento_id
          AND ed.nome NOT IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
          AND (
              d.id NOT IN (
                  SELECT c.documento_id
                  FROM contratos c
                  WHERE c.equipamento_id = :equipamento_id
                    AND c.documento_id IS NOT NULL
              )
          )
        ORDER BY d.codigo
    ");

    $stmt->execute([
        ':equipamento_id' => $equipamento_id
    ]);

    return $stmt->fetchAll();
}

function carregar_contratos_equipamento_edicao($equipamento_id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT
            c.*,
            tc.nome AS tipo_contrato_nome,
            ec.nome AS estado_contrato_nome,
            f.codigo AS fornecedor_codigo,
            f.nome AS fornecedor_nome,
            d.nome AS documento_nome,
            d.data_documento AS documento_data,
            d.data_validade AS documento_validade,
            d.fornecedor_id AS documento_fornecedor_id,
            d.ficheiro AS documento_ficheiro
        FROM contratos c
        LEFT JOIN tipos_contrato tc ON c.tipo_contrato_id = tc.id
        LEFT JOIN estados_contrato ec ON c.estado_contrato_id = ec.id
        LEFT JOIN fornecedores f ON c.fornecedor_id = f.id
        LEFT JOIN documentos d ON c.documento_id = d.id
        WHERE c.equipamento_id = :equipamento_id
          AND ec.nome NOT IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
        ORDER BY c.id DESC
        LIMIT 1
    ");

    $stmt->execute([
        ':equipamento_id' => $equipamento_id
    ]);

    return $stmt->fetchAll();
}

function carregar_tabela_simples_edicao($tabela)
{
    global $ligacao;

    $permitidas = [
        'tipos_documento',
        'estados_documento',
        'tipos_contrato',
        'estados_contrato'
    ];

    if (!in_array($tabela, $permitidas, true)) {
        return [];
    }

    return $ligacao->query("SELECT id, nome FROM {$tabela} ORDER BY nome")->fetchAll();
}

function valor_array_post($grupo, $id, $campo, $valor_atual = '')
{
    if (isset($_POST[$grupo][$id][$campo])) {
        return h($_POST[$grupo][$id][$campo]);
    }

    return h($valor_atual);
}

function fornecedor_associado_documento_valido_edicao($fornecedor_id)
{
    global $fornecedores_associados;

    if ((int) $fornecedor_id === 0) {
        return true;
    }

    foreach ($fornecedores_associados as $fornecedor_associado) {
        if ((int) $fornecedor_associado->fornecedor_id === (int) $fornecedor_id) {
            return true;
        }
    }

    return false;
}

function documento_tecnico_ativo_edicao($documento_id, $equipamento_id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM documentos d
        INNER JOIN estados_documento ed
            ON d.estado_documento_id = ed.id
        WHERE d.id = :documento_id
          AND d.equipamento_id = :equipamento_id
          AND ed.nome NOT IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
    ");

    $stmt->execute([
        ':documento_id' => (int) $documento_id,
        ':equipamento_id' => (int) $equipamento_id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function contrato_ativo_edicao($contrato_id, $equipamento_id)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM contratos c
        INNER JOIN estados_contrato ec
            ON c.estado_contrato_id = ec.id
        WHERE c.id = :contrato_id
          AND c.equipamento_id = :equipamento_id
          AND ec.nome NOT IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
    ");

    $stmt->execute([
        ':contrato_id' => (int) $contrato_id,
        ':equipamento_id' => (int) $equipamento_id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function selecionado_array_post($grupo, $id, $campo, $valor, $valor_atual = '')
{
    $atual = $_POST[$grupo][$id][$campo] ?? $valor_atual;

    return (string) $atual === (string) $valor ? 'selected' : '';
}

function atualizar_documentos_equipamento_edicao($equipamento_id)
{
    global $ligacao, $erros, $aba_ativa;

    $documentos = $_POST['documentos'] ?? [];

    if (!is_array($documentos)) {
        return;
    }

    foreach ($documentos as $documento_id => $dados) {
        $documento_id = (int) $documento_id;

        if ($documento_id <= 0) {
            continue;
        }

        if (!documento_tecnico_ativo_edicao($documento_id, $equipamento_id)) {
            $aba_ativa = 'documentacao';
            $erros[] = 'Documentação: não é possível alterar um documento removido/expirado.';
            continue;
        }

        $nome = trim((string) ($dados['nome'] ?? ''));

        if ($nome === '') {
            $aba_ativa = 'documentacao';
            $erros[] = 'Documentação: o nome do documento é obrigatório.';
            continue;
        }

        $fornecedor_documento = (int) ($dados['fornecedor_id'] ?? 0);

        if (!fornecedor_associado_documento_valido_edicao($fornecedor_documento)) {
            $aba_ativa = 'documentacao';
            $erros[] = 'Documentação: o fornecedor selecionado não está associado a este equipamento.';
            continue;
        }

        $fornecedor_documento = (int) ($dados['fornecedor_id'] ?? 0);

        if (!fornecedor_associado_documento_valido_edicao($fornecedor_documento)) {
            $aba_ativa = 'documentacao';
            $erros[] = 'Documentação: o fornecedor selecionado não está associado a este equipamento.';
            continue;
        }

        $ficheiro_atual = $dados['ficheiro_atual'] ?? null;
        $ficheiro = $ficheiro_atual;

        if (isset($_FILES['ficheiro_documento']['error'][$documento_id]) && $_FILES['ficheiro_documento']['error'][$documento_id] === UPLOAD_ERR_OK) {
            $nome_original = $_FILES['ficheiro_documento']['name'][$documento_id] ?? '';
            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));

            if ($extensao !== 'pdf') {
                $aba_ativa = 'documentacao';
                $erros[] = 'Documentação: o ficheiro deve ser PDF.';
                continue;
            }

            $pasta = __DIR__ . '/../../assets/uploads/documentos';

            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            $nome_ficheiro = 'doc_' . $documento_id . '_' . date('YmdHis') . '.pdf';

            if (move_uploaded_file($_FILES['ficheiro_documento']['tmp_name'][$documento_id], $pasta . '/' . $nome_ficheiro)) {
                $ficheiro = 'assets/uploads/documentos/' . $nome_ficheiro;
            }
        }

        $stmt = $ligacao->prepare("
            UPDATE documentos
            SET
                fornecedor_id = :fornecedor_id,
                nome = :nome,
                data_documento = :data_documento,
                data_validade = :data_validade,
                ficheiro = :ficheiro
            WHERE id = :id
              AND equipamento_id = :equipamento_id
        ");

        $stmt->execute([
            ':fornecedor_id' => $fornecedor_documento > 0 ? $fornecedor_documento : null,
            ':nome' => $nome,
            ':data_documento' => ($dados['data_documento'] ?? '') !== '' ? $dados['data_documento'] : null,
            ':data_validade' => ($dados['data_validade'] ?? '') !== '' ? $dados['data_validade'] : null,
            ':ficheiro' => $ficheiro,
            ':id' => $documento_id,
            ':equipamento_id' => $equipamento_id
        ]);
    }
}

function atualizar_contratos_equipamento_edicao($equipamento_id)
{
    global $ligacao, $erros, $aba_ativa;

    $contratos = $_POST['contratos'] ?? [];

    if (!is_array($contratos)) {
        return;
    }

    foreach ($contratos as $contrato_id => $dados) {
        $contrato_id = (int) $contrato_id;

        if ($contrato_id <= 0) {
            continue;
        }

        $stmt = $ligacao->prepare("
            UPDATE contratos
            SET
                tipo_contrato_id = :tipo_contrato_id,
                data_inicio = :data_inicio,
                data_fim = :data_fim,
                periodicidade = :periodicidade,
                estado_contrato_id = :estado_contrato_id,
                valor = :valor
            WHERE id = :id
              AND equipamento_id = :equipamento_id
        ");

        $stmt->execute([
            ':tipo_contrato_id' => (int) ($dados['tipo_contrato_id'] ?? 0),
            ':data_inicio' => ($dados['data_inicio'] ?? '') !== '' ? $dados['data_inicio'] : null,
            ':data_fim' => ($dados['data_fim'] ?? '') !== '' ? $dados['data_fim'] : null,
            ':periodicidade' => trim((string) ($dados['periodicidade'] ?? '')),
            ':estado_contrato_id' => (int) ($dados['estado_contrato_id'] ?? 0),
            ':valor' => str_replace(',', '.', (string) ($dados['valor'] ?? '0')),
            ':id' => $contrato_id,
            ':equipamento_id' => $equipamento_id
        ]);
    }
}

function telefone_valido_edicao($telefone)
{
    $telefone = trim($telefone);
    $quantidade_numeros = strlen(preg_replace('/[^0-9]/', '', $telefone));

    return preg_match('/^[0-9+\s]+$/', $telefone)
        && $quantidade_numeros >= 9
        && $quantidade_numeros <= 15;
}

function fornecedor_campo($indice, $campo, $nome_post)
{
    global $fornecedores_associados;

    if (isset($_POST[$nome_post])) {
        return h($_POST[$nome_post]);
    }

    $registo = $fornecedores_associados[$indice - 1] ?? null;

    if (!$registo) {
        return '';
    }

    return h($registo->$campo ?? '');
}

function fornecedor_selecionado($indice, $campo, $valor, $nome_post)
{
    global $fornecedores_associados;

    if (isset($_POST[$nome_post])) {
        $atual = $_POST[$nome_post];
    } else {
        $registo = $fornecedores_associados[$indice - 1] ?? null;
        $atual = $registo ? ($registo->$campo ?? '') : '';
    }

    return (string) $atual === (string) $valor ? 'selected' : '';
}

function validar_fornecedor_bloco_edicao($indice, $obrigatorio)
{
    global $erros, $fornecedores_gerais, $tipos_relacao_fornecedor;

    $sufixo = $indice === 1 ? '' : (string) $indice;

    $fornecedor = valor_post('fornecedor_id_equipamento' . $sufixo);
    $tipo = valor_post('tipo_relacao_fornecedor_id_equipamento' . $sufixo);
    $contacto = valor_post('contacto_fornecedor_equipamento' . $sufixo);
    $telefone = valor_post('telefone_contacto_fornecedor_equipamento' . $sufixo);
    $email = valor_post('email_contacto_fornecedor_equipamento' . $sufixo);
    $observacoes = valor_post('observacoes_fornecedor_equipamento' . $sufixo);

    $algum = $fornecedor !== '' || $tipo !== '' || $contacto !== '' || $telefone !== '' || $email !== '' || $observacoes !== '';

    if (!$obrigatorio && !$algum) {
        return;
    }

    $prefixo = 'Fornecedor associado ' . $indice . ': ';

    $ids_fornecedores = array_map('intval', array_column($fornecedores_gerais, 'id'));
    $ids_tipos = array_map('intval', array_column($tipos_relacao_fornecedor, 'id'));

    if ($fornecedor === '') {
        $erros[] = $prefixo . 'selecione o fornecedor.';
    } elseif (!in_array((int) $fornecedor, $ids_fornecedores, true)) {
        $erros[] = $prefixo . 'o fornecedor selecionado não existe na base de dados.';
    }

    if ($tipo === '') {
        $erros[] = $prefixo . 'selecione o tipo de fornecedor/relação.';
    } elseif (!in_array((int) $tipo, $ids_tipos, true)) {
        $erros[] = $prefixo . 'o tipo de fornecedor/relação selecionado não existe na base de dados.';
    }

    if ($contacto === '') {
        $erros[] = $prefixo . 'a pessoa de contacto é obrigatória.';
    } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/u', $contacto)) {
        $erros[] = $prefixo . 'a pessoa de contacto deve conter apenas letras.';
    }

    if ($telefone === '') {
        $erros[] = $prefixo . 'o telefone da pessoa de contacto é obrigatório.';
    } elseif (!telefone_valido_edicao($telefone)) {
        $erros[] = $prefixo . 'o telefone deve conter apenas números, espaços ou o sinal +, e ter entre 9 e 15 números.';
    }

    if ($email === '') {
        $erros[] = $prefixo . 'o email da pessoa de contacto é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = $prefixo . 'insira um email válido.';
    }
}

function valores_fornecedor_bloco($indice)
{
    $sufixo = $indice === 1 ? '' : (string) $indice;

    return [
        'fornecedor_id' => valor_post('fornecedor_id_equipamento' . $sufixo),
        'tipo_relacao_fornecedor_id' => valor_post('tipo_relacao_fornecedor_id_equipamento' . $sufixo),
        'pessoa_contacto' => valor_post('contacto_fornecedor_equipamento' . $sufixo),
        'telefone_contacto' => valor_post('telefone_contacto_fornecedor_equipamento' . $sufixo),
        'email_contacto' => valor_post('email_contacto_fornecedor_equipamento' . $sufixo),
        'observacoes' => valor_post('observacoes_fornecedor_equipamento' . $sufixo)
    ];
}

$id_encriptado = $_GET['id'] ?? null;
$id_equipamento = validar_id_encriptado($id_encriptado);

if (!$id_equipamento) {
    header('Location: index.php');
    exit;
}

$ligacao = ligar_bd();

if (!$ligacao) {
    $erros[] = 'Aconteceu um erro na ligação à base de dados.';
    $equipamento = null;
    $localizacoes_gerais = [];
    $fornecedores_gerais = [];
    $tipos_relacao_fornecedor = [];
    $equipamentos_principais = [];
    $fornecedores_associados = [];
    $documentos_equipamento = [];
    $contratos_equipamento = [];
    $tipos_documento = [];
    $estados_documento = [];
    $tipos_contrato = [];
    $estados_contrato = [];
} else {
    try {
        $localizacoes_gerais = carregar_localizacoes_gerais_edicao();
        $fornecedores_gerais = carregar_fornecedores_gerais_edicao();
        $tipos_relacao_fornecedor = carregar_tipos_relacao_fornecedor_edicao();
        $equipamentos_principais = carregar_equipamentos_principais_edicao($id_equipamento);

        $stmt = $ligacao->prepare("
            SELECT
                e.*,
                ce.nome AS categoria_nome,
                ee.nome AS estado_nome,
                cr.nome AS criticidade_nome,
                pm.nome AS prioridade_nome,
                te.nome AS tipo_entrada_nome
            FROM equipamentos e
            LEFT JOIN categorias_equipamento ce ON e.categoria_equipamento_id = ce.id
            LEFT JOIN estados_equipamento ee ON e.estado_equipamento_id = ee.id
            LEFT JOIN criticidades cr ON e.criticidade_id = cr.id
            LEFT JOIN prioridades_manutencao pm ON e.prioridade_manutencao_id = pm.id
            LEFT JOIN tipos_entrada te ON e.tipo_entrada_id = te.id
            WHERE e.id = :id
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id_equipamento
        ]);

        $equipamento = $stmt->fetch();

        if (!$equipamento) {
            header('Location: index.php');
            exit;
        }

        $fornecedores_associados = carregar_fornecedores_associados_edicao($id_equipamento);
        $documentos_equipamento = carregar_documentos_equipamento_edicao($id_equipamento);
        $contratos_equipamento = carregar_contratos_equipamento_edicao($id_equipamento);
        $tipos_documento = carregar_tabela_simples_edicao('tipos_documento');
        $estados_documento = carregar_tabela_simples_edicao('estados_documento');
        $tipos_contrato = carregar_tabela_simples_edicao('tipos_contrato');
        $estados_contrato = carregar_tabela_simples_edicao('estados_contrato');
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao carregar o equipamento.';
        $equipamento = null;
        $localizacoes_gerais = [];
        $fornecedores_gerais = [];
        $tipos_relacao_fornecedor = [];
        $equipamentos_principais = [];
        $fornecedores_associados = [];
        $documentos_equipamento = [];
        $contratos_equipamento = [];
        $tipos_documento = [];
        $estados_documento = [];
        $tipos_contrato = [];
        $estados_contrato = [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ligacao && $equipamento) {
    $aba_post = valor_post('aba_ativa');

    if (in_array($aba_post, ['info', 'localizacao', 'fornecedor', 'documentacao', 'contratos'], true)) {
        $aba_ativa = $aba_post;
    }

    if ($aba_ativa === 'documentacao') {
        try {
            $ligacao->beginTransaction();

            atualizar_documentos_equipamento_edicao($id_equipamento);

            if (empty($erros)) {
                $ligacao->commit();

                $documentos_equipamento = carregar_documentos_equipamento_edicao($id_equipamento);
                $contratos_equipamento = carregar_contratos_equipamento_edicao($id_equipamento);
                $sucesso = 'Documentação atualizada com sucesso.';
            } else {
                $ligacao->rollBack();
            }
        } catch (PDOException $erroBD) {
            if ($ligacao->inTransaction()) {
                $ligacao->rollBack();
            }

            $erros[] = 'Aconteceu um erro ao atualizar a documentação.';
        }
    } elseif ($aba_ativa === 'contratos') {
        try {
            $ligacao->beginTransaction();

            atualizar_contratos_equipamento_edicao($id_equipamento);

            if (empty($erros)) {
                $ligacao->commit();

                $documentos_equipamento = carregar_documentos_equipamento_edicao($id_equipamento);
                $contratos_equipamento = carregar_contratos_equipamento_edicao($id_equipamento);
                $sucesso = 'Contrato atualizado com sucesso.';
            } else {
                $ligacao->rollBack();
            }
        } catch (PDOException $erroBD) {
            if ($ligacao->inTransaction()) {
                $ligacao->rollBack();
            }

            $erros[] = 'Aconteceu um erro ao atualizar o contrato.';
        }
    } else {
        $codigo = normalizar_codigo(valor_post('codigo'));
        $_POST['codigo'] = $codigo;

    if ($codigo === '') {
        $erros[] = 'O código interno é obrigatório.';
    } elseif (!preg_match('/^EQ[0-9]{3}$/', $codigo)) {
        $erros[] = 'O código interno deve seguir o formato EQ006.';
    }

    if (valor_post('designacao') === '') {
        $erros[] = 'A designação do equipamento é obrigatória.';
    }

    if (valor_post('marca') === '') {
        $erros[] = 'A marca é obrigatória.';
    }

    if (valor_post('modelo') === '') {
        $erros[] = 'O modelo é obrigatório.';
    }

    if (valor_post('numero_serie') === '') {
        $erros[] = 'O número de série é obrigatório.';
    }

    if (valor_post('fabricante') === '') {
        $erros[] = 'O fabricante é obrigatório.';
    }

    $ano = valor_post('ano_fabrico');
    $ano_maximo = (int) date('Y') + 1;

    if ($ano === '') {
        $erros[] = 'O ano de fabrico é obrigatório.';
    } elseif (!ctype_digit($ano) || (int) $ano < 1900 || (int) $ano > $ano_maximo) {
        $erros[] = 'O ano de fabrico deve ser um ano válido.';
    }

    if (valor_post('utilizacao_clinica') === '') {
        $erros[] = 'A utilização clínica é obrigatória.';
    }

    if (valor_post('data_aquisicao') === '') {
        $erros[] = 'A data de aquisição é obrigatória.';
    }

    $custo = str_replace(',', '.', valor_post('custo_aquisicao'));
    if (valor_post('custo_aquisicao') === '') {
        $erros[] = 'O custo de aquisição é obrigatório.';
    } elseif (!is_numeric($custo) || (float) $custo < 0) {
        $erros[] = 'O custo de aquisição deve ser um número válido.';
    }

    if (!in_array(valor_post('componente_outro_equipamento'), ['Sim', 'Não'], true)) {
        $erros[] = 'Indique se é componente de outro equipamento.';
    } elseif (valor_post('componente_outro_equipamento') === 'Sim' && (int) valor_post('equipamento_principal_id') <= 0) {
        $erros[] = 'Selecione o equipamento principal.';
    }

    if (!in_array(valor_post('tem_consumiveis'), ['Sim', 'Não'], true)) {
        $erros[] = 'Indique se o equipamento tem consumíveis.';
    } elseif (valor_post('tem_consumiveis') === 'Sim' && valor_post('consumiveis_equipamento') === '') {
        $erros[] = 'Indique os consumíveis utilizados.';
    }

    $categoria_id = obter_id_obrigatorio_edicao(
        'categorias_equipamento',
        mapear_valor_bd_edicao('categoria', valor_post('categoria_equipamento_id')),
        'A categoria selecionada não existe na base de dados.'
    );

    $estado_id = obter_id_obrigatorio_edicao(
        'estados_equipamento',
        mapear_valor_bd_edicao('estado_equipamento', valor_post('estado_equipamento_id')),
        'O estado selecionado não existe na base de dados.'
    );

    $criticidade_id = obter_id_obrigatorio_edicao(
        'criticidades',
        mapear_valor_bd_edicao('criticidade', valor_post('criticidade_id')),
        'A criticidade selecionada não existe na base de dados.'
    );

    $prioridade_id = obter_id_obrigatorio_edicao(
        'prioridades_manutencao',
        mapear_valor_bd_edicao('prioridade', valor_post('prioridade_manutencao_id')),
        'A prioridade selecionada não existe na base de dados.'
    );

    $tipo_entrada_id = obter_id_obrigatorio_edicao(
        'tipos_entrada',
        mapear_valor_bd_edicao('tipo_entrada', valor_post('tipo_entrada_id')),
        'O tipo de entrada selecionado não existe na base de dados.'
    );

    $localizacao_id = (int) valor_post('localizacao_id');
    $localizacao = obter_localizacao_geral_edicao($localizacao_id);

    if (!$localizacao) {
        $erros[] = 'Selecione uma localização geral válida e ativa.';
    }

    $piso = valor_post('piso_equipamento');

    if ($piso === '') {
        $erros[] = 'O piso específico é obrigatório.';
    } elseif (!ctype_digit($piso) || (int) $piso <= 0) {
        $erros[] = 'O piso específico deve ser um número inteiro superior a 0.';
    } elseif ($localizacao && (int) $piso > (int) $localizacao->numero_pisos) {
        $erros[] = 'O piso indicado não pode ser superior ao número de pisos da localização geral.';
    }

    if (valor_post('servico_equipamento') === '') {
        $erros[] = 'O serviço é obrigatório.';
    }

    if (valor_post('sala_equipamento') === '') {
        $erros[] = 'A sala/unidade é obrigatória.';
    }

    validar_fornecedor_bloco_edicao(1, true);
    validar_fornecedor_bloco_edicao(2, false);
    validar_fornecedor_bloco_edicao(3, false);

    $fornecedores_escolhidos = [];

    foreach ([1, 2, 3] as $indice) {
        $valores = valores_fornecedor_bloco($indice);

        if ($valores['fornecedor_id'] !== '') {
            if (in_array((int) $valores['fornecedor_id'], $fornecedores_escolhidos, true)) {
                $erros[] = 'Não pode repetir o mesmo fornecedor associado ao equipamento.';
            }

            $fornecedores_escolhidos[] = (int) $valores['fornecedor_id'];
        }
    }

    if (empty($erros) && codigo_equipamento_existe_edicao($codigo, $id_equipamento)) {
        $erros[] = 'Já existe um equipamento com esse código interno.';
    }

    if (empty($erros) && numero_serie_existe_edicao(valor_post('numero_serie'), $id_equipamento)) {
        $erros[] = 'Já existe um equipamento com esse número de série.';
    }

    if (empty($erros)) {
        try {
            $ligacao->beginTransaction();

            $stmt = $ligacao->prepare("
                UPDATE equipamentos
                SET
                    codigo = :codigo,
                    designacao = :designacao,
                    categoria_equipamento_id = :categoria_equipamento_id,
                    marca = :marca,
                    modelo = :modelo,
                    numero_serie = :numero_serie,
                    fabricante = :fabricante,
                    ano_fabrico = :ano_fabrico,
                    estado_equipamento_id = :estado_equipamento_id,
                    criticidade_id = :criticidade_id,
                    prioridade_manutencao_id = :prioridade_manutencao_id,
                    utilizacao_clinica = :utilizacao_clinica,
                    data_aquisicao = :data_aquisicao,
                    custo = :custo,
                    tipo_entrada_id = :tipo_entrada_id,
                    componente_outro_equipamento = :componente_outro_equipamento,
                    equipamento_principal_id = :equipamento_principal_id,
                    tem_consumiveis = :tem_consumiveis,
                    consumiveis = :consumiveis,
                    localizacao_id = :localizacao_id,
                    localizacao_piso = :localizacao_piso,
                    localizacao_servico = :localizacao_servico,
                    localizacao_sala = :localizacao_sala,
                    observacoes = :observacoes,
                    ativo = 1
                WHERE id = :id
            ");

            $componente = valor_post('componente_outro_equipamento') === 'Sim' ? 1 : 0;
            $tem_consumiveis = valor_post('tem_consumiveis') === 'Sim' ? 1 : 0;

            $stmt->execute([
                ':codigo' => $codigo,
                ':designacao' => valor_post('designacao'),
                ':categoria_equipamento_id' => $categoria_id,
                ':marca' => valor_post('marca'),
                ':modelo' => valor_post('modelo'),
                ':numero_serie' => valor_post('numero_serie'),
                ':fabricante' => valor_post('fabricante'),
                ':ano_fabrico' => (int) $ano,
                ':estado_equipamento_id' => $estado_id,
                ':criticidade_id' => $criticidade_id,
                ':prioridade_manutencao_id' => $prioridade_id,
                ':utilizacao_clinica' => valor_post('utilizacao_clinica'),
                ':data_aquisicao' => valor_post('data_aquisicao'),
                ':custo' => $custo,
                ':tipo_entrada_id' => $tipo_entrada_id,
                ':componente_outro_equipamento' => $componente,
                ':equipamento_principal_id' => $componente ? (int) valor_post('equipamento_principal_id') : null,
                ':tem_consumiveis' => $tem_consumiveis,
                ':consumiveis' => $tem_consumiveis ? valor_post('consumiveis_equipamento') : null,
                ':localizacao_id' => $localizacao_id,
                ':localizacao_piso' => $piso,
                ':localizacao_servico' => valor_post('servico_equipamento'),
                ':localizacao_sala' => valor_post('sala_equipamento'),
                ':observacoes' => valor_post('observacoes') !== '' ? valor_post('observacoes') : null,
                ':id' => $id_equipamento
            ]);

            $stmt = $ligacao->prepare("DELETE FROM equipamento_fornecedores WHERE equipamento_id = :equipamento_id");
            $stmt->execute([
                ':equipamento_id' => $id_equipamento
            ]);

            $stmt = $ligacao->prepare("
                INSERT INTO equipamento_fornecedores
                    (equipamento_id, fornecedor_id, tipo_relacao_fornecedor_id, pessoa_contacto, telefone_contacto, email_contacto, principal, observacoes)
                VALUES
                    (:equipamento_id, :fornecedor_id, :tipo_relacao_fornecedor_id, :pessoa_contacto, :telefone_contacto, :email_contacto, :principal, :observacoes)
            ");

            foreach ([1, 2, 3] as $indice) {
                $valores = valores_fornecedor_bloco($indice);

                if ($valores['fornecedor_id'] === '') {
                    continue;
                }

                $stmt->execute([
                    ':equipamento_id' => $id_equipamento,
                    ':fornecedor_id' => (int) $valores['fornecedor_id'],
                    ':tipo_relacao_fornecedor_id' => (int) $valores['tipo_relacao_fornecedor_id'],
                    ':pessoa_contacto' => $valores['pessoa_contacto'],
                    ':telefone_contacto' => $valores['telefone_contacto'],
                    ':email_contacto' => $valores['email_contacto'],
                    ':principal' => $indice === 1 ? 1 : 0,
                    ':observacoes' => $valores['observacoes'] !== '' ? $valores['observacoes'] : null
                ]);
            }

            $ligacao->commit();

            $fornecedores_associados = carregar_fornecedores_associados_edicao($id_equipamento);
            $documentos_equipamento = carregar_documentos_equipamento_edicao($id_equipamento);
            $contratos_equipamento = carregar_contratos_equipamento_edicao($id_equipamento);
            $sucesso = 'Registo atualizado com sucesso.';
        } catch (PDOException $erroBD) {
            if ($ligacao->inTransaction()) {
                $ligacao->rollBack();
            }

            $erros[] = 'Aconteceu um erro ao atualizar o equipamento.';
        }
        
    }
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Editar Equipamento</h1>
                <p>Atualização dos dados principais, localização e fornecedores associados.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/index.php">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Sair para o site público
                        </a>
                    </li>

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
                    <h2>Ficha de Edição do Equipamento</h2>
                    <p>O ID vem encriptado por GET, os dados são carregados da BD e guardados por POST com UPDATE.</p>
                </div>

                <a href="index.php" class="btn-backend">
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

            <?php if (!empty($sucesso)) : ?>
                <div class="alert alert-success" role="alert">
                    <?= h($sucesso) ?>
                </div>
            <?php endif; ?>

            <?php if ($equipamento) : ?>
                <form class="form-backend" action="editar.php?id=<?= h($id_encriptado) ?>" method="post" enctype="multipart/form-data" novalidate>
                    <input type="hidden" id="abaAtivaEditarEquipamento" name="aba_ativa" value="<?= h($aba_ativa) ?>">

                    <ul class="nav nav-tabs mb-4 tabs-equipamento" id="tabsEditarEquipamento" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= classe_aba_edicao('info') ?>" id="edit-info-tab" data-bs-toggle="tab" data-bs-target="#edit-info-tab-pane" type="button" role="tab">
                                <i class="bi bi-info-circle"></i>
                                Informação geral
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= classe_aba_edicao('localizacao') ?>" id="edit-localizacao-tab" data-bs-toggle="tab" data-bs-target="#edit-localizacao-tab-pane" type="button" role="tab">
                                <i class="bi bi-geo-alt"></i>
                                Localização
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= classe_aba_edicao('fornecedor') ?>" id="edit-fornecedor-tab" data-bs-toggle="tab" data-bs-target="#edit-fornecedor-tab-pane" type="button" role="tab">
                                <i class="bi bi-truck"></i>
                                Fornecedor
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= classe_aba_edicao('documentacao') ?>" id="edit-documentacao-tab" data-bs-toggle="tab" data-bs-target="#edit-documentacao-tab-pane" type="button" role="tab">
                                <i class="bi bi-folder"></i>
                                Documentação
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= classe_aba_edicao('contratos') ?>" id="edit-garantia-tab" data-bs-toggle="tab" data-bs-target="#edit-garantia-tab-pane" type="button" role="tab">
                                <i class="bi bi-shield-check"></i>
                                Contratos
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="tabsEditarEquipamentoConteudo">

                        <div class="tab-pane fade <?= classe_aba_edicao('info', 'painel') ?>" id="edit-info-tab-pane" role="tabpanel" tabindex="0">
                            <div class="form-grid">
                                <div>
                                    <label for="codigo">Código interno *</label>
                                    <input type="text" id="codigo" name="codigo" placeholder="Ex: EQ006" value="<?= campo_equipamento('codigo') ?>" required pattern="[Ee][Qq][0-9]{3}">
                                </div>

                                <div>
                                    <label for="designacao">Designação *</label>
                                    <input type="text" id="designacao" name="designacao" value="<?= campo_equipamento('designacao') ?>" required>
                                </div>

                                <div>
                                    <label for="categoria">Categoria *</label>
                                    <select id="categoria" name="categoria_equipamento_id" required>
                                        <option value="">Selecione uma categoria</option>
                                        <?php foreach (['Monitorização', 'Suporte de vida', 'Terapia', 'Diagnóstico', 'Laboratório', 'Esterilização'] as $opcao) : ?>
                                            <option value="<?= h($opcao) ?>" <?= selecionado_valor('categoria_equipamento_id', $opcao, 'categoria_nome') ?>><?= h($opcao) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="marca">Marca *</label>
                                    <input type="text" id="marca" name="marca" value="<?= campo_equipamento('marca') ?>" required>
                                </div>

                                <div>
                                    <label for="modelo">Modelo *</label>
                                    <input type="text" id="modelo" name="modelo" value="<?= campo_equipamento('modelo') ?>" required>
                                </div>

                                <div>
                                    <label for="serie">Número de série *</label>
                                    <input type="text" id="serie" name="numero_serie" value="<?= campo_equipamento('numero_serie') ?>" required>
                                </div>

                                <div>
                                    <label for="fabricante">Fabricante *</label>
                                    <input type="text" id="fabricante" name="fabricante" value="<?= campo_equipamento('fabricante') ?>" required>
                                </div>

                                <div>
                                    <label for="anoFabrico">Ano de fabrico *</label>
                                    <input type="number" id="anoFabrico" name="ano_fabrico" value="<?= campo_equipamento('ano_fabrico') ?>" min="1900" max="<?= date('Y') + 1 ?>" required>
                                </div>

                                <div>
                                    <label for="estado">Estado atual *</label>
                                    <select id="estado" name="estado_equipamento_id" required>
                                        <option value="">Selecione o estado</option>
                                        <?php foreach (['Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em quarentena', 'Abatido'] as $opcao) : ?>
                                            <option value="<?= h($opcao) ?>" <?= selecionado_valor('estado_equipamento_id', $opcao, 'estado_nome') ?>><?= h($opcao) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="criticidade">Criticidade *</label>
                                    <select id="criticidade" name="criticidade_id" required>
                                        <option value="">Selecione a criticidade</option>
                                        <?php foreach (['Baixa', 'Média', 'Alta', 'Suporte de vida'] as $opcao) : ?>
                                            <option value="<?= h($opcao) ?>" <?= selecionado_valor('criticidade_id', $opcao, 'criticidade_nome') ?>><?= h($opcao) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="prioridadeManutencao">Prioridade de manutenção *</label>
                                    <select id="prioridadeManutencao" name="prioridade_manutencao_id" required>
                                        <option value="">Selecione a prioridade</option>
                                        <?php foreach (['Baixa', 'Moderada', 'Elevada', 'Urgente'] as $opcao) : ?>
                                            <option value="<?= h($opcao) ?>" <?= selecionado_valor('prioridade_manutencao_id', $opcao, 'prioridade_nome') ?>><?= h($opcao) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="dataAquisicao">Data de aquisição *</label>
                                    <input type="date" id="dataAquisicao" name="data_aquisicao" value="<?= campo_equipamento('data_aquisicao') ?>" required>
                                </div>

                                <div>
                                    <label for="custo">Custo de aquisição (€) *</label>
                                    <input type="number" id="custo" name="custo_aquisicao" value="<?= campo_equipamento('custo_aquisicao', 'custo') ?>" min="0" step="0.01" required>
                                </div>

                                <div>
                                    <label for="tipoEntrada">Tipo de entrada *</label>
                                    <select id="tipoEntrada" name="tipo_entrada_id" required>
                                        <option value="">Selecione o tipo de entrada</option>
                                        <?php foreach (['Compra', 'Doação', 'Aluguer', 'Empréstimo'] as $opcao) : ?>
                                            <option value="<?= h($opcao) ?>" <?= selecionado_valor('tipo_entrada_id', $opcao, 'tipo_entrada_nome') ?>><?= h($opcao) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="componenteOutroEquipamento">É componente de outro equipamento? *</label>
                                    <select id="componenteOutroEquipamento" name="componente_outro_equipamento" required>
                                        <option value="">Selecione</option>
                                        <option value="Não" <?= selecionado_bool_sim_nao('componente_outro_equipamento', 'Não', 'componente_outro_equipamento') ?>>Não</option>
                                        <option value="Sim" <?= selecionado_bool_sim_nao('componente_outro_equipamento', 'Sim', 'componente_outro_equipamento') ?>>Sim</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="equipamentoPrincipal">Equipamento principal</label>
                                    <select id="equipamentoPrincipal" name="equipamento_principal_id">
                                        <option value="">Não aplicável</option>
                                        <?php foreach ($equipamentos_principais as $principal) : ?>
                                            <option value="<?= h($principal->id) ?>" <?= selecionado_valor('equipamento_principal_id', $principal->id) ?>>
                                                <?= h($principal->codigo) ?> - <?= h($principal->designacao) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="temConsumiveis">Tem consumíveis? *</label>
                                    <select id="temConsumiveis" name="tem_consumiveis" required>
                                        <option value="">Selecione</option>
                                        <option value="Não" <?= selecionado_bool_sim_nao('tem_consumiveis', 'Não', 'tem_consumiveis') ?>>Não</option>
                                        <option value="Sim" <?= selecionado_bool_sim_nao('tem_consumiveis', 'Sim', 'tem_consumiveis') ?>>Sim</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-full">
                                <label for="utilizacaoClinica">Utilização clínica *</label>
                                <textarea id="utilizacaoClinica" name="utilizacao_clinica" rows="3" required><?= campo_equipamento('utilizacao_clinica') ?></textarea>
                            </div>

                            <div class="form-full">
                                <label for="consumiveisEquipamento">Consumíveis</label>
                                <textarea id="consumiveisEquipamento" name="consumiveis_equipamento" rows="3"><?= campo_equipamento('consumiveis_equipamento', 'consumiveis') ?></textarea>
                            </div>
                        </div>

                        <div class="tab-pane fade <?= classe_aba_edicao('localizacao', 'painel') ?>" id="edit-localizacao-tab-pane" role="tabpanel" tabindex="0">
                            <div class="form-grid">
                                <div>
                                    <label for="localizacaoGeralEquipamento">Localização geral *</label>
                                    <select id="localizacaoGeralEquipamento" name="localizacao_id" required>
                                        <option value="">Selecione uma localização geral</option>
                                        <?php foreach ($localizacoes_gerais as $localizacao_geral) : ?>
                                            <option value="<?= h($localizacao_geral->id) ?>" <?= selecionado_valor('localizacao_id', $localizacao_geral->id) ?>>
                                                <?= h($localizacao_geral->codigo) ?> - <?= h($localizacao_geral->edificio) ?>
                                                <?php if ($localizacao_geral->numero_pisos !== null && $localizacao_geral->numero_pisos !== '') : ?>
                                                    (<?= h($localizacao_geral->numero_pisos) ?> pisos)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="pisoEquipamento">Piso específico *</label>
                                    <input type="text" id="pisoEquipamento" name="piso_equipamento" value="<?= campo_equipamento('piso_equipamento', 'localizacao_piso') ?>" inputmode="numeric" pattern="[0-9]*" maxlength="3" required>
                                </div>

                                <div>
                                    <label for="servicoEquipamento">Serviço *</label>
                                    <input type="text" id="servicoEquipamento" name="servico_equipamento" value="<?= campo_equipamento('servico_equipamento', 'localizacao_servico') ?>" required>
                                </div>

                                <div>
                                    <label for="salaEquipamento">Sala / Unidade *</label>
                                    <input type="text" id="salaEquipamento" name="sala_equipamento" value="<?= campo_equipamento('sala_equipamento', 'localizacao_sala') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?= classe_aba_edicao('fornecedor', 'painel') ?>" id="edit-fornecedor-tab-pane" role="tabpanel" tabindex="0">
                            <p>O primeiro fornecedor é obrigatório. O segundo e terceiro só são validados se forem preenchidos.</p>

                            <?php for ($i = 1; $i <= 3; $i++) : ?>
                                <?php $sufixo = $i === 1 ? '' : (string) $i; ?>

                                <div class="backend-box-secundaria mb-3">
                                    <h4>Fornecedor associado <?= $i ?><?= $i === 1 ? ' *' : '' ?></h4>

                                    <div class="form-grid">
                                        <div>
                                            <label for="fornecedorEquipamento<?= h($sufixo) ?>">Fornecedor<?= $i === 1 ? ' *' : '' ?></label>
                                            <select id="fornecedorEquipamento<?= h($sufixo) ?>" name="fornecedor_id_equipamento<?= h($sufixo) ?>" <?= $i === 1 ? 'required' : '' ?>>
                                                <option value="">Selecione um fornecedor já criado</option>
                                                <?php foreach ($fornecedores_gerais as $fornecedor_geral) : ?>
                                                    <option value="<?= h($fornecedor_geral->id) ?>" <?= fornecedor_selecionado($i, 'fornecedor_id', $fornecedor_geral->id, 'fornecedor_id_equipamento' . $sufixo) ?>>
                                                        <?= h($fornecedor_geral->codigo) ?> - <?= h($fornecedor_geral->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="tipoFornecedorEquipamento<?= h($sufixo) ?>">Tipo de fornecedor/relação<?= $i === 1 ? ' *' : '' ?></label>
                                            <select id="tipoFornecedorEquipamento<?= h($sufixo) ?>" name="tipo_relacao_fornecedor_id_equipamento<?= h($sufixo) ?>" <?= $i === 1 ? 'required' : '' ?>>
                                                <option value="">Selecione o tipo</option>
                                                <?php foreach ($tipos_relacao_fornecedor as $tipo_relacao) : ?>
                                                    <option value="<?= h($tipo_relacao->id) ?>" <?= fornecedor_selecionado($i, 'tipo_relacao_fornecedor_id', $tipo_relacao->id, 'tipo_relacao_fornecedor_id_equipamento' . $sufixo) ?>>
                                                        <?= h($tipo_relacao->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="contactoFornecedorEquipamento<?= h($sufixo) ?>">Pessoa de contacto<?= $i === 1 ? ' *' : '' ?></label>
                                            <input type="text" id="contactoFornecedorEquipamento<?= h($sufixo) ?>" name="contacto_fornecedor_equipamento<?= h($sufixo) ?>" value="<?= fornecedor_campo($i, 'pessoa_contacto', 'contacto_fornecedor_equipamento' . $sufixo) ?>" <?= $i === 1 ? 'required' : '' ?>>
                                        </div>

                                        <div>
                                            <label for="telefoneContactoFornecedorEquipamento<?= h($sufixo) ?>">Telefone contacto<?= $i === 1 ? ' *' : '' ?></label>
                                            <input type="tel" id="telefoneContactoFornecedorEquipamento<?= h($sufixo) ?>" name="telefone_contacto_fornecedor_equipamento<?= h($sufixo) ?>" value="<?= fornecedor_campo($i, 'telefone_contacto', 'telefone_contacto_fornecedor_equipamento' . $sufixo) ?>" <?= $i === 1 ? 'required' : '' ?>>
                                        </div>

                                        <div>
                                            <label for="emailContactoFornecedorEquipamento<?= h($sufixo) ?>">Email contacto<?= $i === 1 ? ' *' : '' ?></label>
                                            <input type="email" id="emailContactoFornecedorEquipamento<?= h($sufixo) ?>" name="email_contacto_fornecedor_equipamento<?= h($sufixo) ?>" value="<?= fornecedor_campo($i, 'email_contacto', 'email_contacto_fornecedor_equipamento' . $sufixo) ?>" <?= $i === 1 ? 'required' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="form-full">
                                        <label for="observacoesFornecedorEquipamento<?= h($sufixo) ?>">Observações da relação</label>
                                        <textarea id="observacoesFornecedorEquipamento<?= h($sufixo) ?>" name="observacoes_fornecedor_equipamento<?= h($sufixo) ?>" rows="3"><?= fornecedor_campo($i, 'observacoes', 'observacoes_fornecedor_equipamento' . $sufixo) ?></textarea>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="tab-pane fade <?= classe_aba_edicao('documentacao', 'painel') ?>" id="edit-documentacao-tab-pane" role="tabpanel" tabindex="0">
                            <h3>Documentação do equipamento</h3>
                            <p>Edite apenas os documentos técnicos do equipamento. O contrato/garantia fica na aba Contratos.</p>

                            <?php if (empty($documentos_equipamento)) : ?>
                                <div class="alert alert-info" role="alert">
                                    Este equipamento ainda não tem documentos técnicos associados.
                                </div>
                            <?php endif; ?>

                            <?php foreach ($documentos_equipamento as $documento) : ?>
                                <div class="backend-box-secundaria mb-4">
                                    <h4><?= h($documento->codigo) ?> - <?= h($documento->tipo_documento_nome) ?></h4>

                                    <div class="form-grid">
                                        <div>
                                            <label>Nome do documento *</label>
                                            <input type="text" name="documentos[<?= h($documento->id) ?>][nome]" value="<?= valor_array_post('documentos', $documento->id, 'nome', $documento->nome) ?>" required>
                                        </div>

                                        <div>
                                            <label>Fornecedor associado *</label>
                                            <select name="documentos[<?= h($documento->id) ?>][fornecedor_id]" required>
                                                <option value="0" <?= selecionado_array_post('documentos', $documento->id, 'fornecedor_id', '0', $documento->fornecedor_id ?: '0') ?>>
                                                    Sem fornecedor associado
                                                </option>
                                                <?php foreach ($fornecedores_associados as $fornecedor_associado) : ?>
                                                    <option value="<?= h($fornecedor_associado->fornecedor_id) ?>" <?= selecionado_array_post('documentos', $documento->id, 'fornecedor_id', $fornecedor_associado->fornecedor_id, $documento->fornecedor_id) ?>>
                                                        <?= h($fornecedor_associado->fornecedor_codigo) ?> - <?= h($fornecedor_associado->fornecedor_nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label>Data do documento *</label>
                                            <input type="date" name="documentos[<?= h($documento->id) ?>][data_documento]" value="<?= valor_array_post('documentos', $documento->id, 'data_documento', $documento->data_documento) ?>" required>
                                        </div>

                                        <div>
                                            <label>Data de validade</label>
                                            <input type="date" name="documentos[<?= h($documento->id) ?>][data_validade]" value="<?= valor_array_post('documentos', $documento->id, 'data_validade', $documento->data_validade) ?>">
                                        </div>

                                        <div>
                                            <label>PDF respetivo</label>
                                            <input type="file" name="ficheiro_documento[<?= h($documento->id) ?>]" accept="application/pdf">
                                            <input type="hidden" name="documentos[<?= h($documento->id) ?>][ficheiro_atual]" value="<?= h($documento->ficheiro) ?>">
                                            <?php if (!empty($documento->ficheiro)) : ?>
                                                <small>Ficheiro atual mantido se não escolheres outro.</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="tab-pane fade <?= classe_aba_edicao('contratos', 'painel') ?>" id="edit-garantia-tab-pane" role="tabpanel" tabindex="0">
                            <h3>Contratos / Garantia do equipamento</h3>
                            <p>Edite aqui a garantia/contrato e o respetivo documento PDF associado ao equipamento.</p>

                            <?php if (empty($contratos_equipamento)) : ?>
                                <div class="alert alert-info" role="alert">
                                    Este equipamento ainda não tem contrato/garantia associado.
                                </div>
                            <?php endif; ?>

                            <?php foreach ($contratos_equipamento as $contrato) : ?>
                                <div class="backend-box-secundaria mb-4">
                                    <h4><?= h($contrato->codigo) ?> - Contrato / Garantia</h4>

                                    <input type="hidden" name="contratos[<?= h($contrato->id) ?>][documento_id]" value="<?= h($contrato->documento_id) ?>">
                                    <input type="hidden" name="contratos[<?= h($contrato->id) ?>][documento_ficheiro_atual]" value="<?= h($contrato->documento_ficheiro) ?>">

                                    <h4>Documento do contrato / garantia</h4>

                                    <div class="form-grid">
                                        <div>
                                            <label>Nome do documento *</label>
                                            <input type="text" name="contratos[<?= h($contrato->id) ?>][documento_nome]" value="<?= valor_array_post('contratos', $contrato->id, 'documento_nome', $contrato->documento_nome) ?>" required>
                                        </div>

                                        <div>
                                            <label>Fornecedor associado *</label>
                                            <select name="contratos[<?= h($contrato->id) ?>][documento_fornecedor_id]" required>
                                                <option value="0" <?= selecionado_array_post('contratos', $contrato->id, 'documento_fornecedor_id', '0', $contrato->documento_fornecedor_id ?: '0') ?>>
                                                    Sem fornecedor associado
                                                </option>
                                                <?php foreach ($fornecedores_associados as $fornecedor_associado) : ?>
                                                    <option value="<?= h($fornecedor_associado->fornecedor_id) ?>" <?= selecionado_array_post('contratos', $contrato->id, 'documento_fornecedor_id', $fornecedor_associado->fornecedor_id, $contrato->documento_fornecedor_id) ?>>
                                                        <?= h($fornecedor_associado->fornecedor_codigo) ?> - <?= h($fornecedor_associado->fornecedor_nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label>Data do documento *</label>
                                            <input type="date" name="contratos[<?= h($contrato->id) ?>][documento_data]" value="<?= valor_array_post('contratos', $contrato->id, 'documento_data', $contrato->documento_data) ?>" required>
                                        </div>

                                        <div>
                                            <label>PDF do contrato / garantia</label>
                                            <input type="file" name="ficheiro_contrato[<?= h($contrato->id) ?>]" accept="application/pdf">
                                            <?php if (!empty($contrato->documento_ficheiro)) : ?>
                                                <small>Ficheiro atual mantido se não escolheres outro.</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <h4 class="mt-4">Dados do contrato</h4>

                                    <div class="form-grid">
                                        <div>
                                            <label>Tipo de contrato *</label>
                                            <select name="contratos[<?= h($contrato->id) ?>][tipo_contrato_id]" required>
                                                <?php foreach ($tipos_contrato as $tipo_contrato) : ?>
                                                    <option value="<?= h($tipo_contrato->id) ?>" <?= selecionado_array_post('contratos', $contrato->id, 'tipo_contrato_id', $tipo_contrato->id, $contrato->tipo_contrato_id) ?>>
                                                        <?= h($tipo_contrato->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label>Estado do contrato *</label>
                                            <select name="contratos[<?= h($contrato->id) ?>][estado_contrato_id]" required>
                                                <?php foreach ($estados_contrato as $estado_contrato) : ?>
                                                    <option value="<?= h($estado_contrato->id) ?>" <?= selecionado_array_post('contratos', $contrato->id, 'estado_contrato_id', $estado_contrato->id, $contrato->estado_contrato_id) ?>>
                                                        <?= h($estado_contrato->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label>Data de início da garantia *</label>
                                            <input type="date" name="contratos[<?= h($contrato->id) ?>][data_inicio]" value="<?= valor_array_post('contratos', $contrato->id, 'data_inicio', $contrato->data_inicio) ?>" required>
                                        </div>

                                        <div>
                                            <label>Data de fim *</label>
                                            <input type="date" name="contratos[<?= h($contrato->id) ?>][data_fim]" value="<?= valor_array_post('contratos', $contrato->id, 'data_fim', $contrato->data_fim) ?>" required>
                                        </div>

                                        <div>
                                            <label>Periodicidade de manutenção *</label>
                                            <input type="text" name="contratos[<?= h($contrato->id) ?>][periodicidade]" value="<?= valor_array_post('contratos', $contrato->id, 'periodicidade', $contrato->periodicidade) ?>" required>
                                        </div>

                                        <div>
                                            <label>Valor associado (€) *</label>
                                            <input type="number" step="0.01" min="0" name="contratos[<?= h($contrato->id) ?>][valor]" value="<?= valor_array_post('contratos', $contrato->id, 'valor', $contrato->valor) ?>" required>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <div class="form-full mt-4">
                        <label for="observacoesEquipamento">Observações gerais do equipamento</label>
                        <textarea id="observacoesEquipamento" name="observacoes" rows="4"><?= campo_equipamento('observacoes') ?></textarea>
                    </div>

                    <div class="form-botoes mt-4">
                        <button type="submit" class="btn-backend">
                            <i class="bi bi-check-circle"></i>
                            Guardar alterações
                        </button>

                        <a href="index.php" class="btn-secundario">
                            Cancelar
                        </a>
                    </div>

                </form>
            <?php endif; ?>
        </section>

    </main>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const campoAba = document.getElementById('abaAtivaEditarEquipamento');
    const botoesAbas = document.querySelectorAll('#tabsEditarEquipamento button[data-bs-toggle="tab"]');

    const mapaAbas = {
        'edit-info-tab': 'info',
        'edit-localizacao-tab': 'localizacao',
        'edit-fornecedor-tab': 'fornecedor',
        'edit-documentacao-tab': 'documentacao',
        'edit-garantia-tab': 'contratos'
    };

    botoesAbas.forEach(function (botao) {
        botao.addEventListener('shown.bs.tab', function (evento) {
            if (campoAba && mapaAbas[evento.target.id]) {
                campoAba.value = mapaAbas[evento.target.id];
            }
        });
    });
});
</script>


<?php include '../includes/footer.php'; ?>
