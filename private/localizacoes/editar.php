<?php
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/validacoes.php';

redirect_if_not_logged();

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_atual = 'localizacoes';
$erros = [];
$sucesso = '';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function valor_post($nome)
{
    return trim((string) ($_POST[$nome] ?? ''));
}

function campo_edicao($nome)
{
    global $localizacao;

    if (isset($_POST[$nome])) {
        return h($_POST[$nome]);
    }

    return h($localizacao->$nome ?? '');
}

function selecionado_edicao($nome, $valor)
{
    global $localizacao;

    $atual = $_POST[$nome] ?? ($localizacao->$nome ?? '');

    return (string) $atual === (string) $valor ? 'selected' : '';
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

    $nome_bd = $mapa[$nome] ?? $nome;

    $stmt = $ligacao->prepare("SELECT id FROM estados_localizacao WHERE nome = :nome LIMIT 1");
    $stmt->execute([
        ':nome' => $nome_bd
    ]);

    return (int) $stmt->fetchColumn();
}

function codigo_localizacao_existe_edicao($codigo, $id_atual)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM localizacoes
        WHERE codigo = :codigo
          AND id <> :id
    ");

    $stmt->execute([
        ':codigo' => $codigo,
        ':id' => $id_atual
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

$id_encriptado = $_GET['id'] ?? null;
$id_localizacao = validar_id_encriptado($id_encriptado);

if (!$id_localizacao) {
    header('Location: index.php');
    exit;
}

$ligacao = ligar_bd();

if (!$ligacao) {
    $erros[] = 'Aconteceu um erro na ligação à base de dados.';
    $localizacao = null;
} else {
    try {
        $stmt = $ligacao->prepare("
            SELECT
                l.id,
                l.codigo,
                l.edificio,
                COALESCE(l.numero_pisos, l.piso) AS numero_pisos,
                COALESCE(el.nome, 'Ativa') AS estado_localizacao,
                l.responsavel,
                l.contacto_interno,
                l.observacoes
            FROM localizacoes l
            LEFT JOIN estados_localizacao el
                ON l.estado_localizacao_id = el.id
            WHERE l.id = :id
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id_localizacao
        ]);

        $localizacao = $stmt->fetch();

        if (!$localizacao) {
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao carregar a localização.';
        $localizacao = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ligacao && $localizacao) {
    $codigo = normalizar_codigo(valor_post('codigo'));
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
    } elseif (obter_estado_localizacao_id($estado) <= 0) {
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

    if (empty($erros) && codigo_localizacao_existe_edicao($codigo, $id_localizacao)) {
        $erros[] = 'Já existe uma localização com esse código interno.';
    }

    if (empty($erros)) {
        try {
            $estado_id = obter_estado_localizacao_id($estado);

            $stmt = $ligacao->prepare("
                UPDATE localizacoes
                SET
                    codigo = :codigo,
                    edificio = :edificio,
                    numero_pisos = :numero_pisos,
                    estado_localizacao_id = :estado_localizacao_id,
                    responsavel = :responsavel,
                    contacto_interno = :contacto_interno,
                    observacoes = :observacoes
                WHERE id = :id
            ");

            $stmt->execute([
                ':codigo' => $codigo,
                ':edificio' => valor_post('edificio'),
                ':numero_pisos' => (int) $numero_pisos,
                ':estado_localizacao_id' => $estado_id,
                ':responsavel' => valor_post('responsavel'),
                ':contacto_interno' => valor_post('contacto_interno'),
                ':observacoes' => valor_post('observacoes') !== '' ? valor_post('observacoes') : null,
                ':id' => $id_localizacao
            ]);

            $sucesso = 'Registo atualizado com sucesso.';
        } catch (PDOException $erroBD) {
            $erros[] = 'Aconteceu um erro ao atualizar a localização.';
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
                <h1>Editar Localização</h1>
                <p>Atualização dos dados gerais de uma localização existente.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
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
                    <h2>Dados da Localização Geral</h2>
                    <p>O ID vem encriptado por GET e o formulário é preenchido com os dados reais da base de dados.</p>
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

            <?php if ($localizacao) : ?>
                <form class="form-backend" action="editar.php?id=<?= h($id_encriptado) ?>" method="post" novalidate>

                    <div class="form-grid">
                        <div>
                            <label for="codigoLocalizacao">Código interno *</label>
                            <input type="text" id="codigoLocalizacao" name="codigo" placeholder="Ex: LOC006" value="<?= campo_edicao('codigo') ?>" required pattern="[Ll][Oo][Cc][0-9]{3}">
                        </div>

                        <div>
                            <label for="edificioLocalizacao">Edifício *</label>
                            <input type="text" id="edificioLocalizacao" name="edificio" placeholder="Ex: Edifício Principal" value="<?= campo_edicao('edificio') ?>" required>
                        </div>

                        <div>
                            <label for="numeroPisosLocalizacao">Número de pisos *</label>
                            <input type="text" id="numeroPisosLocalizacao" name="numero_pisos" placeholder="Ex: 5" inputmode="numeric" pattern="[0-9]*" maxlength="3" value="<?= campo_edicao('numero_pisos') ?>" required>
                        </div>

                        <div>
                            <label for="estadoLocalizacao">Estado da localização *</label>
                            <select id="estadoLocalizacao" name="estado_localizacao" required>
                                <option value="">Selecione o estado</option>
                                <option value="Ativa" <?= selecionado_edicao('estado_localizacao', 'Ativa') ?>>Ativa</option>
                                <option value="Em manutenção" <?= selecionado_edicao('estado_localizacao', 'Em manutenção') ?>>Em manutenção</option>
                                <option value="Inativa" <?= selecionado_edicao('estado_localizacao', 'Inativa') ?>>Inativa</option>
                                <option value="Temporariamente indisponível" <?= selecionado_edicao('estado_localizacao', 'Temporariamente indisponível') ?>>Temporariamente indisponível</option>
                            </select>
                        </div>

                        <div>
                            <label for="responsavelLocalizacao">Responsável da localização *</label>
                            <input type="text" id="responsavelLocalizacao" name="responsavel" placeholder="Ex: Ana Silva" value="<?= campo_edicao('responsavel') ?>" required>
                        </div>

                        <div>
                            <label for="contactoInternoLocalizacao">Contacto interno *</label>
                            <input type="text" id="contactoInternoLocalizacao" name="contacto_interno" placeholder="Ex: 2201" inputmode="numeric" pattern="[0-9]*" maxlength="6" value="<?= campo_edicao('contacto_interno') ?>" required>
                        </div>
                    </div>

                    <div class="form-full">
                        <label for="observacoesLocalizacao">Observações</label>
                        <textarea id="observacoesLocalizacao" name="observacoes" rows="4" placeholder="Observações gerais sobre esta localização"><?= campo_edicao('observacoes') ?></textarea>
                    </div>

                    <div class="form-botoes">
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

<?php include '../includes/footer.php'; ?>
