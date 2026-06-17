<?php
require_once __DIR__ . '/../private/includes/funcoes.php';

start_session();
// --------------------------------------------------------------------
// MENSAGENS TEMPORÁRIAS DA SESSÃO
// --------------------------------------------------------------------

$validation_errors = [];
$server_error = '';

if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}

if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>

    <!-- favicon -->
    <link rel="shortcut icon" href="../assets/img/medcontrol-logo1.png" type="image/png">

    <!-- Bootstrap -->
    <link href="../assets/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap-icons.min.css">

    <!-- CSS do projeto -->
    <link rel="stylesheet" href="../assets/css/1240661.css">
</head>

<body class="login-body">

    <main class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">
                <img src="../assets/img/medcontrol-logo1.png" alt="Logótipo da MedControl">
                <h1><?php echo APP_NAME; ?></h1>
            </div>

            <h2>Área Reservada</h2>
            <p>Introduza os seus dados para aceder ao sistema.</p>

            <form action="../private/index.php" method="post">
                <div class="mb-3">
                    <label for="text_username" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        name="text_username"
                        id="text_username"
                        placeholder="Insira o seu email"
                        required>
                </div>

                <div class="mb-3">
                    <label for="text_password" class="form-label">Palavra-passe</label>
                    <input
                        type="password"
                        class="form-control"
                        name="text_password"
                        id="text_password"
                        placeholder="Palavra-passe"
                        required>
                </div>

                <button type="submit" class="btn-login">Entrar</button>

                <?php if (!empty($validation_errors)) : ?>
                    <div class="mensagem-login">
                        <?php foreach ($validation_errors as $erro) : ?>
                            <p><?php echo $erro; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($server_error)) : ?>
                    <div class="mensagem-login">
                        <p><?php echo $server_error; ?></p>
                    </div>
                <?php endif; ?>

                <p id="mensagem-login" class="mensagem-login"></p>
            </form>

            <a href="index.php" class="voltar-site">Voltar ao site público</a>

        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>

</html>