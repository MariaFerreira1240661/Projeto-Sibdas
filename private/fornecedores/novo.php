<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'fornecedores';

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

function valor_post($nome)
{
    return trim((string) ($_POST[$nome] ?? ''));
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
    if (trim($website) === '') {
        return true;
    }

    return (bool) preg_match('/^(https?:\/\/)?([\w-]+\.)+[\w-]{2,}(\/.*)?$/i', trim($website));
}

function codigo_fornecedor_existe($codigo)
{
    global $ligacao;

    $stmt = $ligacao->prepare("SELECT COUNT(*) FROM fornecedores WHERE codigo = :codigo");
    $stmt->execute([
        ':codigo' => $codigo
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function nif_fornecedor_existe($nif)
{
    global $ligacao;

    $stmt = $ligacao->prepare("SELECT COUNT(*) FROM fornecedores WHERE nif = :nif");
    $stmt->execute([
        ':nif' => $nif
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function validar_fornecedor()
{
    global $erros;

    $codigo = strtoupper(preg_replace('/\s+/', '', valor_post('codigo')));
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

    return empty($erros);
}

function guardar_fornecedor()
{
    global $erros, $ligacao;

    if (!$ligacao) {
        $erros[] = 'Aconteceu um erro na ligação à base de dados.';
        return false;
    }

    $codigo = strtoupper(preg_replace('/\s+/', '', valor_post('codigo')));
    $_POST['codigo'] = $codigo;

    if (codigo_fornecedor_existe($codigo)) {
        $erros[] = 'Já existe um fornecedor com esse código interno.';
        return false;
    }

    if (nif_fornecedor_existe(valor_post('nif'))) {
        $erros[] = 'Já existe um fornecedor com esse NIF.';
        return false;
    }

    try {
        /*
         * O fornecedor passa a ser uma entidade geral.
         * O tipo de fornecedor e os dados da pessoa de contacto passam
         * a pertencer à associação entre equipamento e fornecedor.
         */
        $stmt = $ligacao->prepare("
            INSERT INTO fornecedores
                (codigo, nome, nif, tipo_fornecedor_id, telefone, email, website, pessoa_contacto, telefone_contacto, morada, observacoes, ativo)
            VALUES
                (:codigo, :nome, :nif, NULL, :telefone, :email, :website, NULL, NULL, :morada, :observacoes, 1)
        ");

        $stmt->execute([
            ':codigo' => $codigo,
            ':nome' => valor_post('nome'),
            ':nif' => valor_post('nif'),
            ':telefone' => valor_post('telefone'),
            ':email' => valor_post('email'),
            ':website' => valor_post('website'),
            ':morada' => valor_post('morada'),
            ':observacoes' => valor_post('observacoes') !== '' ? valor_post('observacoes') : null
        ]);

        return true;
    } catch (PDOException $erroBD) {
        $erros[] = 'Aconteceu um erro ao guardar o fornecedor. Confirma se já executaste o SQL que torna os campos antigos opcionais.';
        return false;
    }
}

$ligacao = ligar_bd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (validar_fornecedor() && guardar_fornecedor()) {
        $sucesso = 'Fornecedor geral registado com sucesso na base de dados.';
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
                <h1>Novo Fornecedor</h1>
                <p>Registo de uma entidade fornecedora geral.</p>
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
                    <h2>Dados do Fornecedor Geral</h2>
                    <p>Preencha apenas os dados gerais da entidade. O tipo de relação e a pessoa de contacto ficam para o registo do equipamento.</p>
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

            <form class="form-backend" id="formFornecedor" action="" method="post" novalidate>

                <div class="form-grid">
                    <div>
                        <label for="codigoFornecedor">Código interno *</label>
                        <input type="text" id="codigoFornecedor" name="codigo" placeholder="Ex: FOR006" value="<?= campo('codigo') ?>" required pattern="[Ff][Oo][Rr][0-9]{3}">
                    </div>

                    <div>
                        <label for="nomeFornecedor">Fornecedor *</label>
                        <input type="text" id="nomeFornecedor" name="nome" placeholder="Ex: MedTech Portugal" value="<?= campo('nome') ?>" required>
                    </div>

                    <div>
                        <label for="nifFornecedor">NIF *</label>
                        <input type="text" id="nifFornecedor" name="nif" placeholder="Ex: 501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9" value="<?= campo('nif') ?>" required>
                    </div>

                    <div>
                        <label for="telefoneFornecedor">Telefone do fornecedor *</label>
                        <input type="tel" id="telefoneFornecedor" name="telefone" placeholder="Ex: +351 222 000 000" inputmode="tel" value="<?= campo('telefone') ?>" required>
                    </div>

                    <div>
                        <label for="emailFornecedor">Email do fornecedor *</label>
                        <input type="email" id="emailFornecedor" name="email" placeholder="Ex: geral@empresa.pt" value="<?= campo('email') ?>" required>
                    </div>

                    <div>
                        <label for="websiteFornecedor">Website *</label>
                        <input type="text" id="websiteFornecedor" name="website" placeholder="Ex: www.empresa.pt" value="<?= campo('website') ?>" required>
                    </div>
                </div>

                <div class="form-full">
                    <label for="moradaFornecedor">Morada *</label>
                    <textarea id="moradaFornecedor" name="morada" rows="3" placeholder="Morada do fornecedor" required><?= campo('morada') ?></textarea>
                </div>

                <div class="form-full">
                    <label for="observacoesFornecedor">Observações</label>
                    <textarea id="observacoesFornecedor" name="observacoes" rows="4" placeholder="Observações gerais sobre o fornecedor"><?= campo('observacoes') ?></textarea>
                </div>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar fornecedor geral
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
