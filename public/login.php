<?php
require_once __DIR__ . '/../config/config.php';
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
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="text_password" class="form-label">Palavra-passe</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        name="text_password" 
                        id="text_password" 
                        placeholder="Palavra-passe"
                        required
                    >
                </div>

                <button type="submit" class="btn-login">Entrar</button>

                <p id="mensagem-login" class="mensagem-login"></p>
            </form>

            <a href="index.php" class="voltar-site">Voltar ao site público</a>

        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>