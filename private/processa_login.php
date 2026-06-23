<?php
require_once __DIR__ . '/includes/utilizadores.php';
require_once __DIR__ . '/includes/logs_eventos.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php');
    exit;
}

$username = trim((string) ($_POST['text_username'] ?? ''));
$password = trim((string) ($_POST['text_password'] ?? ''));

$validation_errors = [];

if ($username === '') {
    $validation_errors[] = 'O email é obrigatório.';
} elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O email não é válido.';
}

if ($password === '') {
    $validation_errors[] = 'A palavra-passe é obrigatória.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: ../public/login.php');
    exit;
}

$utilizador = validar_login_utilizador($username, $password);

if (!$utilizador) {
    registar_evento('login_falhado', 'utilizadores', null, 'Tentativa de login falhada para o email: ' . $username);
    $_SESSION['server_error'] = 'Login inválido.';
    header('Location: ../public/login.php');
    exit;
}

$_SESSION['utilizador_id'] = (int) $utilizador->id;
$_SESSION['utilizador'] = $utilizador->email;
$_SESSION['nome_utilizador'] = !empty($utilizador->nome) ? $utilizador->nome : $utilizador->email;
$_SESSION['profile'] = $utilizador->perfil;

registar_evento('login_sucesso', 'utilizadores', (int) $utilizador->id, 'Login efetuado com sucesso.');

header('Location: index.php');
exit;
