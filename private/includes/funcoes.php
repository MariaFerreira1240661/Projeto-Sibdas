<?php
require_once __DIR__ . '/../../config/config.php';

// --------------------------------------------------------------------
// FUNÇÕES DE SESSÃO
// --------------------------------------------------------------------
// Este ficheiro centraliza a gestão das sessões,
// como indicado na Ficha 10.
// --------------------------------------------------------------------

// Inicia a sessão se ainda não estiver iniciada
function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Verifica se existe utilizador autenticado
function check_session()
{
    return isset($_SESSION['utilizador']);
}

// Redireciona para o login se não houver sessão ativa
function redirect_if_not_logged($redirect_to = '')
{
    start_session();

    if (!check_session()) {
        if ($redirect_to == '') {
            $redirect_to = BASE_URL . '/public/login.php';
        }

        header('Location: ' . $redirect_to);
        exit;
    }
}

// Termina a sessão e redireciona para o login
function logout_and_redirect($redirect_to = '')
{
    start_session();

    session_unset();
    session_destroy();

    if ($redirect_to == '') {
        $redirect_to = BASE_URL . '/public/login.php';
    }

    header('Location: ' . $redirect_to);
    exit;
}