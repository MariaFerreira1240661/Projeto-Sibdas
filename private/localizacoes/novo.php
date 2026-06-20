<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'localizacoes';

$erros = [];
$sucesso = '';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function campo($nome)
{
    return h($_POST[$nome] ?? '');
}

function selecionado($nome, $valor)
{
    return (($_POST[$nome] ?? '') === $valor) ? 'selected' : '';
}

function valor_post($nome)
{
    return trim((string) ($_POST[$nome] ?? ''));
}

function letras_validas($valor)
{
    return (bool) preg_match('/^[A-Za-zÀ-ÿ\s]+$/u', trim($valor));
}

function obter_estado_localizacao_id($nome)
{
    global $ligacao;

    $mapa = [
        'Temporariamente indisponível' => 'Inativa'
    ];

    $nome_bd = $nome;

    $stmt = $ligacao->prepare("SELECT id FROM estados_localizacao WHERE nome = :nome LIMIT 1");
    $stmt->execute([
        ':nome' => $nome_bd
    ]);

    $id = (int) $stmt->fetchColumn();

    if ($id > 0) {
        return $id;
    }

    if (isset($mapa[$nome])) {
        $nome_bd = $mapa[$nome];

        $stmt = $ligacao->prepare("SELECT id FROM estados_localizacao WHERE nome = :nome LIMIT 1");
        $stmt->execute([
            ':nome' => $nome_bd
        ]);

        return (int) $stmt->fetchColumn();
    }

    return 0;
}

function codigo_localizacao_existe($codigo)
{
    global $ligacao;

    $stmt = $ligacao->prepare("SELECT COUNT(*) FROM localizacoes WHERE codigo = :codigo");
    $stmt->execute([
        ':codigo' => $codigo
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function validar_localizacao()
{
    global $erros, $ligacao;

    $codigo = strtoupper(preg_replace('/\s+/', '', valor_post('codigo')));
    $_POST['codigo'] = $codigo;

    if ($codigo === '') {
        $erros[] = 'O código interno é obrigatório.';
    } elseif (!preg_match('/^LOC[0-9]{3}$/', $codigo)) {
        $erros[] = 'O código interno deve seguir o formato LOC006.';
    }

    if (valor_post('edificio') === '') {
        $erros[] = 'O edifício é obrigatório.';
    }

    $numero_pisos = valor_post('numero_pisos');

    if ($numero_pisos === '') {
        $erros[] = 'O número de pisos é obrigatório.';
    } elseif (!ctype_digit($numero_pisos) || (int) $numero_pisos <= 0) {
        $erros[] = 'O número de pisos deve ser um número inteiro superior a 0.';
    }

    $estado = valor_post('estado_localizacao');

    if ($estado === '') {
        $erros[] = 'O estado da localização é obrigatório.';
    } elseif ($ligacao && obter_estado_localizacao_id($estado) <= 0) {
        $erros[] = 'O estado selecionado não existe na base de dados.';
    }

    $responsavel = valor_post('responsavel');

    if ($responsavel === '') {
        $erros[] = 'O responsável da localização é obrigatório.';
    } elseif (!letras_validas($responsavel)) {
        $erros[] = 'O responsável da localização deve conter apenas letras.';
    }

    $contacto = valor_post('contacto_interno');

    if ($contacto === '') {
        $erros[] = 'O contacto interno é obrigatório.';
    } elseif (!ctype_digit($contacto)) {
        $erros[] = 'O contacto interno deve conter apenas números.';
    }

    return empty($erros);
}

function guardar_localizacao()
{
    global $erros, $ligacao;

    if (!$ligacao) {
        $erros[] = 'Aconteceu um erro na ligação à base de dados.';
        return false;
    }

    $codigo = strtoupper(preg_replace('/\s+/', '', valor_post('codigo')));
    $_POST['codigo'] = $codigo;

    if (codigo_localizacao_existe($codigo)) {
        $erros[] = 'Já existe uma localização com esse código interno.';
        return false;
    }

    $estado_id = obter_estado_localizacao_id(valor_post('estado_localizacao'));

    if ($estado_id <= 0) {
        $erros[] = 'O estado selecionado não existe na base de dados.';
        return false;
    }

    try {
        /*
         * A localização passa a ser geral.
         * piso, servico e sala ficam apenas com valores neutros por compatibilidade
         * com a estrutura antiga da tabela. O local específico do equipamento será
         * tratado mais tarde no módulo Equipamentos.
         */
        $stmt = $ligacao->prepare("
            INSERT INTO localizacoes
                (codigo, edificio, numero_pisos, piso, servico, sala, estado_localizacao_id, responsavel, contacto_interno, observacoes)
            VALUES
                (:codigo, :edificio, :numero_pisos, 0, 'Geral', 'Geral', :estado_localizacao_id, :responsavel, :contacto_interno, :observacoes)
        ");

        $stmt->execute([
            ':codigo' => $codigo,
            ':edificio' => valor_post('edificio'),
            ':numero_pisos' => (int) valor_post('numero_pisos'),
            ':estado_localizacao_id' => $estado_id,
            ':responsavel' => valor_post('responsavel'),
            ':contacto_interno' => valor_post('contacto_interno'),
            ':observacoes' => valor_post('observacoes') !== '' ? valor_post('observacoes') : null
        ]);

        return true;
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao guardar a localização. Confirma se já executaste o SQL que adiciona a coluna numero_pisos.';
        return false;
    }
}

$ligacao = ligar_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (validar_localizacao() && guardar_localizacao()) {
        $sucesso = 'Localização geral registada com sucesso na base de dados.';
        $_POST = [];
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Nova Localização</h1>
                <p>Registo de uma localização geral do hospital.</p>
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
                    <h2>Dados da Localização Geral</h2>
                    <p>Preencha apenas os dados gerais da localização. O piso específico, serviço e sala ficam para o registo do equipamento.</p>
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

            <form class="form-backend" id="formLocalizacao" action="" method="post" novalidate>

                <div class="form-grid">
                    <div>
                        <label for="codigoLocalizacao">Código interno *</label>
                        <input type="text" id="codigoLocalizacao" name="codigo" placeholder="Ex: LOC006" value="<?= campo('codigo') ?>" required pattern="[Ll][Oo][Cc][0-9]{3}">
                    </div>

                    <div>
                        <label for="edificioLocalizacao">Edifício *</label>
                        <input type="text" id="edificioLocalizacao" name="edificio" placeholder="Ex: Edifício Principal" value="<?= campo('edificio') ?>" required>
                    </div>

                    <div>
                        <label for="numeroPisosLocalizacao">Número de pisos *</label>
                        <input type="text" id="numeroPisosLocalizacao" name="numero_pisos" placeholder="Ex: 5" inputmode="numeric" pattern="[0-9]*" maxlength="3" value="<?= campo('numero_pisos') ?>" required>
                    </div>

                    <div>
                        <label for="estadoLocalizacao">Estado da localização *</label>
                        <select id="estadoLocalizacao" name="estado_localizacao" required>
                            <option value="">Selecione o estado</option>
                            <option value="Ativa" <?= selecionado('estado_localizacao', 'Ativa') ?>>Ativa</option>
                            <option value="Em manutenção" <?= selecionado('estado_localizacao', 'Em manutenção') ?>>Em manutenção</option>
                            <option value="Inativa" <?= selecionado('estado_localizacao', 'Inativa') ?>>Inativa</option>
                            <option value="Temporariamente indisponível" <?= selecionado('estado_localizacao', 'Temporariamente indisponível') ?>>Temporariamente indisponível</option>
                        </select>
                    </div>

                    <div>
                        <label for="responsavelLocalizacao">Responsável da localização *</label>
                        <input type="text" id="responsavelLocalizacao" name="responsavel" placeholder="Ex: Ana Silva" value="<?= campo('responsavel') ?>" required>
                    </div>

                    <div>
                        <label for="contactoInternoLocalizacao">Contacto interno *</label>
                        <input type="text" id="contactoInternoLocalizacao" name="contacto_interno" placeholder="Ex: 2201" inputmode="numeric" pattern="[0-9]*" maxlength="6" value="<?= campo('contacto_interno') ?>" required>
                    </div>
                </div>

                <div class="form-full">
                    <label for="observacoesLocalizacao">Observações</label>
                    <textarea id="observacoesLocalizacao" name="observacoes" rows="4" placeholder="Observações gerais sobre esta localização"><?= campo('observacoes') ?></textarea>
                </div>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar localização geral
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>
