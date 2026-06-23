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

// --------------------------------------------------------------------
// PERFIS E PERMISSÕES DE ACESSO
// --------------------------------------------------------------------

function perfil_atual()
{
    return $_SESSION['profile'] ?? '';
}

function nome_utilizador()
{
    if (!empty($_SESSION['nome_utilizador'])) {
        return $_SESSION['nome_utilizador'];
    }

    if (!empty($_SESSION['utilizador'])) {
        return $_SESSION['utilizador'];
    }

    return 'Utilizador';
}

if (!function_exists('perfil_nome')) {
function perfil_nome($perfil = null)
{
    if ($perfil === null) {
        $perfil = perfil_atual();
    }

    $nomes = [
        'admin' => 'Administrador',
        'tecnico' => 'Técnico biomédico',
        'profissional_saude' => 'Profissional de saúde',
        'gestor_logistica' => 'Gestor de logística'
    ];

    return $nomes[$perfil] ?? 'Utilizador';
}
}


function perfis_medcontrol()
{
    return ['admin', 'tecnico', 'profissional_saude', 'gestor_logistica'];
}

function permissoes_medcontrol()
{
    $todos = perfis_medcontrol();

    return [
        'dashboard' => [
            'ver' => $todos
        ],

        'equipamentos' => [
            'ver' => $todos,
            'criar' => ['admin', 'tecnico'],
            'editar' => ['admin', 'tecnico'],
            'remover' => ['admin', 'tecnico']
        ],

        'localizacoes' => [
            'ver' => ['admin', 'tecnico', 'gestor_logistica'],
            'criar' => ['admin', 'tecnico'],
            'editar' => ['admin', 'tecnico'],
            'remover' => ['admin', 'tecnico']
        ],

        'fornecedores' => [
            'ver' => ['admin', 'tecnico', 'gestor_logistica'],
            'criar' => ['admin', 'gestor_logistica'],
            'editar' => ['admin', 'gestor_logistica'],
            'remover' => ['admin', 'gestor_logistica']
        ],

        'documentacao' => [
            'ver' => $todos,
            'criar' => ['admin', 'tecnico', 'gestor_logistica'],
            'editar' => ['admin', 'tecnico', 'gestor_logistica'],
            'remover' => ['admin', 'tecnico', 'gestor_logistica']
        ],

        'contratos' => [
            'ver' => $todos,
            'criar' => ['admin', 'gestor_logistica'],
            'editar' => ['admin', 'gestor_logistica'],
            'remover' => ['admin', 'gestor_logistica']
        ],

        'conteudos' => [
            'ver' => ['admin'],
            'editar' => ['admin']
        ],

        'mensagens' => [
            'ver' => ['admin', 'gestor_logistica'],
            'editar' => ['admin', 'gestor_logistica']
        ]
    ];
}

if (!function_exists('perfil_tem_acesso')) {
function perfil_tem_acesso($modulo, $acao = 'ver')
{
    $perfil = perfil_atual();
    $permissoes = permissoes_medcontrol();

    if ($perfil === 'admin') {
        return true;
    }

    if (!isset($permissoes[$modulo][$acao])) {
        return false;
    }

    return in_array($perfil, $permissoes[$modulo][$acao], true);
}
}


function redirect_if_no_permission($modulo, $acao = 'ver')
{
    start_session();

    if (!perfil_tem_acesso($modulo, $acao)) {
        $_SESSION['server_error'] = 'Não tem permissão para aceder a esta funcionalidade.';
        header('Location: ' . BASE_URL . '/private/index.php');
        exit;
    }
}

function modulo_e_acao_da_pagina_atual()
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $ficheiro = basename($script);

    $modulo = '';

    if (str_contains($script, '/private/equipamentos/')) {
        $modulo = 'equipamentos';
    } elseif (str_contains($script, '/private/localizacoes/')) {
        $modulo = 'localizacoes';
    } elseif (str_contains($script, '/private/fornecedores/')) {
        $modulo = 'fornecedores';
    } elseif (str_contains($script, '/private/documentacao/')) {
        $modulo = 'documentacao';
    } elseif (str_contains($script, '/private/contratos/')) {
        $modulo = 'contratos';
    } elseif (str_contains($script, '/private/conteudos/')) {
        $modulo = 'conteudos';
    } elseif (str_ends_with($script, '/private/index.php')) {
        $modulo = 'dashboard';
    }

    if ($modulo === '') {
        return [null, null];
    }

    $acao = 'ver';

    if ($ficheiro === 'novo.php') {
        $acao = 'criar';
    } elseif ($ficheiro === 'editar.php') {
        $acao = 'editar';
    } elseif ($ficheiro === 'remover.php' || $ficheiro === 'confirmar_remover.php') {
        $acao = 'remover';
    } elseif ($modulo === 'conteudos') {
        $acao = 'editar';
    }

    return [$modulo, $acao];
}

function proteger_pagina_atual()
{
    [$modulo, $acao] = modulo_e_acao_da_pagina_atual();

    if ($modulo !== null) {
        redirect_if_no_permission($modulo, $acao);
    }
}



function exigir_perfil($modulo, $acao = 'ver')
{
    redirect_if_not_logged();

    if (!perfil_tem_acesso($modulo, $acao)) {
        header('Location: ' . BASE_URL . '/private/index.php');
        exit;
    }
}
