<?php
require_once __DIR__ . '/includes/agentes.php';

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

$agente = validar_login_agent($username, $password);

if (!$agente) {
    $_SESSION['server_error'] = 'Login inválido.';
    header('Location: ../public/login.php');
    exit;
}

$_SESSION['utilizador'] = $agente->email;
$_SESSION['nome_utilizador'] = !empty($agente->nome) ? $agente->nome : $agente->email;
$_SESSION['profile'] = $agente->profile;

header('Location: index.php');
exit;
