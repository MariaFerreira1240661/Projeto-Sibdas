<?php
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/validacoes.php';
require_once __DIR__ . '/../includes/logs_eventos.php';

redirect_if_not_logged();

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pagina_atual = 'fornecedores';
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
    global $fornecedor;

    if (isset($_POST[$nome])) {
        return h($_POST[$nome]);
    }

    return h($fornecedor->$nome ?? '');
}

function selecionado_edicao($nome, $valor)
{
    global $fornecedor;

    $atual = $_POST[$nome] ?? ($fornecedor->$nome ?? '');

    return (string) $atual === (string) $valor ? 'selected' : '';
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

function codigo_fornecedor_existe_edicao($codigo, $id_atual)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM fornecedores
        WHERE codigo = :codigo
          AND id <> :id
    ");

    $stmt->execute([
        ':codigo' => $codigo,
        ':id' => $id_atual
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function nif_fornecedor_existe_edicao($nif, $id_atual)
{
    global $ligacao;

    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM fornecedores
        WHERE nif = :nif
          AND id <> :id
    ");

    $stmt->execute([
        ':nif' => $nif,
        ':id' => $id_atual
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

$id_encriptado = $_GET['id'] ?? null;
$id_fornecedor = validar_id_encriptado($id_encriptado);

if (!$id_fornecedor) {
    header('Location: index.php');
    exit;
}

$ligacao = ligar_bd();

if (!$ligacao) {
    $erros[] = 'Aconteceu um erro na ligação à base de dados.';
    $fornecedor = null;
} else {
    try {
        $stmt = $ligacao->prepare("
            SELECT
                id,
                codigo,
                nome,
                nif,
                telefone,
                email,
                website,
                morada,
                observacoes,
                ativo
            FROM fornecedores
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id_fornecedor
        ]);

        $fornecedor = $stmt->fetch();

        if (!$fornecedor) {
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao carregar o fornecedor.';
        $fornecedor = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ligacao && $fornecedor) {
    $codigo = normalizar_codigo(valor_post('codigo'));
    $_POST['codigo'] = $codigo;

    if ($codigo === '') {
        $erros[] = 'O código interno é obrigatório.';
    } elseif (!preg_match('/^FOR[0-9]{3}$/', $codigo)) {
        $erros[] = 'O código interno deve seguir o formato FOR006.';
    }

    if (valor_post('nome') === '') {
        $erros[] = 'O nome do fornecedor é obrigatório.';
    }

    $nif = valor_post('nif');

    if ($nif === '') {
        $erros[] = 'O NIF é obrigatório.';
    } elseif (!preg_match('/^[0-9]{9}$/', $nif)) {
        $erros[] = 'O NIF deve conter exatamente 9 números.';
    }

    $telefone = valor_post('telefone');

    if ($telefone === '') {
        $erros[] = 'O telefone do fornecedor é obrigatório.';
    } elseif (!telefone_valido($telefone)) {
        $erros[] = 'O telefone deve conter apenas números, espaços ou o sinal +, e ter entre 9 e 15 números.';
    }

    $email = valor_post('email');

    if ($email === '') {
        $erros[] = 'O email do fornecedor é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Insira um email válido.';
    }

    $website = valor_post('website');

    if ($website === '') {
        $erros[] = 'O website do fornecedor é obrigatório.';
    } elseif (!website_valido($website)) {
        $erros[] = 'Insira um website válido, por exemplo www.empresa.pt.';
    }

    if (valor_post('morada') === '') {
        $erros[] = 'A morada é obrigatória.';
    }

    if (!in_array(valor_post('ativo'), ['1', '0'], true)) {
        $erros[] = 'O estado do fornecedor é obrigatório.';
    }

    if (empty($erros) && codigo_fornecedor_existe_edicao($codigo, $id_fornecedor)) {
        $erros[] = 'Já existe um fornecedor com esse código interno.';
    }

    if (empty($erros) && nif_fornecedor_existe_edicao($nif, $id_fornecedor)) {
        $erros[] = 'Já existe um fornecedor com esse NIF.';
    }

    if (empty($erros)) {
        try {
            $stmt = $ligacao->prepare("
                UPDATE fornecedores
                SET
                    codigo = :codigo,
                    nome = :nome,
                    nif = :nif,
                    telefone = :telefone,
                    email = :email,
                    website = :website,
                    morada = :morada,
                    observacoes = :observacoes,
                    ativo = :ativo
                WHERE id = :id
            ");

            $stmt->execute([
                ':codigo' => $codigo,
                ':nome' => valor_post('nome'),
                ':nif' => $nif,
                ':telefone' => $telefone,
                ':email' => $email,
                ':website' => $website,
                ':morada' => valor_post('morada'),
                ':observacoes' => valor_post('observacoes') !== '' ? valor_post('observacoes') : null,
                ':ativo' => (int) valor_post('ativo'),
                ':id' => $id_fornecedor
            ]);
            registar_evento(
                'dados_alterados',
                'fornecedores',
                $id_fornecedor,
                'Fornecedor editado: ' . $codigo . ' - ' . valor_post('nome')
            );

            $sucesso = 'Registo atualizado com sucesso.';
        } catch (PDOException $erroBD) {
            $erros[] = 'Aconteceu um erro ao atualizar o fornecedor.';
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
                <h1>Editar Fornecedor</h1>
                <p>Atualização dos dados gerais de uma entidade fornecedora existente.</p>
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
                    <h2>Dados do Fornecedor Geral</h2>
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

            <?php if ($fornecedor) : ?>
                <form class="form-backend" action="editar.php?id=<?= h($id_encriptado) ?>" method="post" novalidate>

                    <div class="form-grid">
                        <div>
                            <label for="editCodigoFornecedor">Código interno *</label>
                            <input type="text" id="editCodigoFornecedor" name="codigo" placeholder="Ex: FOR006" value="<?= campo_edicao('codigo') ?>" required pattern="[Ff][Oo][Rr][0-9]{3}">
                        </div>

                        <div>
                            <label for="editNomeFornecedor">Fornecedor *</label>
                            <input type="text" id="editNomeFornecedor" name="nome" placeholder="Ex: MedTech Portugal" value="<?= campo_edicao('nome') ?>" required>
                        </div>

                        <div>
                            <label for="editNifFornecedor">NIF *</label>
                            <input type="text" id="editNifFornecedor" name="nif" placeholder="Ex: 501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9" value="<?= campo_edicao('nif') ?>" required>
                        </div>

                        <div>
                            <label for="editTelefoneFornecedor">Telefone do fornecedor *</label>
                            <input type="tel" id="editTelefoneFornecedor" name="telefone" placeholder="Ex: +351 912 345 678" value="<?= campo_edicao('telefone') ?>" required>
                        </div>

                        <div>
                            <label for="editEmailFornecedor">Email do fornecedor *</label>
                            <input type="email" id="editEmailFornecedor" name="email" placeholder="Ex: geral@fornecedor.pt" value="<?= campo_edicao('email') ?>" required>
                        </div>

                        <div>
                            <label for="editWebsiteFornecedor">Website *</label>
                            <input type="text" id="editWebsiteFornecedor" name="website" placeholder="Ex: www.fornecedor.pt" value="<?= campo_edicao('website') ?>" required>
                        </div>

                        <div>
                            <label for="ativoFornecedor">Estado *</label>
                            <select id="ativoFornecedor" name="ativo" required>
                                <option value="1" <?= selecionado_edicao('ativo', '1') ?>>Ativo</option>
                                <option value="0" <?= selecionado_edicao('ativo', '0') ?>>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-full">
                        <label for="moradaFornecedor">Morada *</label>
                        <textarea id="moradaFornecedor" name="morada" rows="3" required><?= campo_edicao('morada') ?></textarea>
                    </div>

                    <div class="form-full">
                        <label for="observacoesFornecedor">Observações</label>
                        <textarea id="observacoesFornecedor" name="observacoes" rows="4"><?= campo_edicao('observacoes') ?></textarea>
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
