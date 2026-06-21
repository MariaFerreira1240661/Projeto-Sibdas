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
// --------------------------------------------------------------------
// BASE DE DADOS
// --------------------------------------------------------------------

function ligar_bd()
{
    try {
        $ligacao = new PDO(
            "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
            MYSQL_USERNAME,
            MYSQL_PASSWORD
        );

        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ligacao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $ligacao;
    } catch (PDOException $erro) {
        return null;
    }
}

// --------------------------------------------------------------------
// SEGURANÇA - Encriptação e desencriptação de IDs
// --------------------------------------------------------------------

function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt(
        (string) $value,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    ));
}

function aes_decrypt($value)
{
    if (!is_string($value) || $value === '' || strlen($value) % 2 !== 0) {
        return false;
    }

    $binario = @hex2bin($value);

    if ($binario === false) {
        return false;
    }

    return openssl_decrypt(
        $binario,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    );
}

function validar_id_encriptado($valor)
{
    $id = aes_decrypt($valor);

    if (!$id || !ctype_digit((string) $id)) {
        return false;
    }

    return (int) $id;
}
