<?php
require_once __DIR__ . '/funcoes.php';

if (!defined('MYSQL_AES_KEY')) {
    define('MYSQL_AES_KEY', 'medcontrol_chave_local_2026');
}

function garantir_tabela_agents($ligacao)
{
    $ligacao->exec("
        CREATE TABLE IF NOT EXISTS agents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            email VARBINARY(255) NOT NULL,
            passwrd VARCHAR(255) NOT NULL,
            profile VARCHAR(60) NOT NULL,
            last_login DATETIME NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NULL,
            deleted_at DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    garantir_colunas_agents($ligacao);
    inserir_agents_default($ligacao);
}

function coluna_existe_agents($ligacao, $nome_coluna)
{
    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'agents'
          AND COLUMN_NAME = :coluna
    ");

    $stmt->execute([':coluna' => $nome_coluna]);

    return (int) $stmt->fetchColumn() > 0;
}

function garantir_colunas_agents($ligacao)
{
    if (!coluna_existe_agents($ligacao, 'nome')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN nome VARCHAR(150) NULL AFTER id");
    }

    if (!coluna_existe_agents($ligacao, 'email')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN email VARBINARY(255) NULL AFTER nome");
    }

    if (!coluna_existe_agents($ligacao, 'passwrd')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN passwrd VARCHAR(255) NULL AFTER email");
    }

    if (!coluna_existe_agents($ligacao, 'profile')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN profile VARCHAR(60) NULL AFTER passwrd");
    }

    if (!coluna_existe_agents($ligacao, 'last_login')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN last_login DATETIME NULL AFTER profile");
    }

    if (!coluna_existe_agents($ligacao, 'created_at')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER last_login");
    }

    if (!coluna_existe_agents($ligacao, 'updated_at')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN updated_at DATETIME NULL AFTER created_at");
    }

    if (!coluna_existe_agents($ligacao, 'deleted_at')) {
        $ligacao->exec("ALTER TABLE agents ADD COLUMN deleted_at DATETIME NULL AFTER updated_at");
    }

    if (coluna_existe_agents($ligacao, 'name')) {
        $ligacao->exec("
            UPDATE agents
            SET email = name
            WHERE email IS NULL
              AND name IS NOT NULL
        ");
    }

    if (coluna_existe_agents($ligacao, 'display_name')) {
        $ligacao->exec("
            UPDATE agents
            SET nome = display_name
            WHERE (nome IS NULL OR nome = '')
              AND display_name IS NOT NULL
        ");
    }
}

function inserir_agents_default($ligacao)
{
    inserir_agent_default($ligacao, 'Maria Ferreira', 'admin@medcontrol.pt', 'admin123', 'admin');
    inserir_agent_default($ligacao, 'Tiago Martins', 'tecnico@medcontrol.pt', 'tecnico123', 'tecnico');
    inserir_agent_default($ligacao, 'Ana Costa', 'profissional@medcontrol.pt', 'saude123', 'profissional_saude');
    inserir_agent_default($ligacao, 'João Pereira', 'logistica@medcontrol.pt', 'logistica123', 'gestor_logistica');
}

function inserir_agent_default($ligacao, $nome, $email, $password, $profile)
{
    $stmt = $ligacao->prepare("
        SELECT id
        FROM agents
        WHERE CAST(AES_DECRYPT(email, :chave) AS CHAR(255)) = :email
        LIMIT 1
    ");

    $stmt->execute([
        ':chave' => MYSQL_AES_KEY,
        ':email' => $email
    ]);

    $agente_existente = $stmt->fetch();

    if ($agente_existente) {
        $update = $ligacao->prepare("
            UPDATE agents
            SET nome = :nome,
                passwrd = :passwrd,
                profile = :profile,
                updated_at = NOW(),
                deleted_at = NULL
            WHERE id = :id
        ");

        $update->execute([
            ':nome' => $nome,
            ':passwrd' => $password,
            ':profile' => $profile,
            ':id' => $agente_existente->id
        ]);

        return;
    }

    $insert = $ligacao->prepare("
        INSERT INTO agents (nome, email, passwrd, profile, created_at)
        VALUES (:nome, AES_ENCRYPT(:email, :chave), :passwrd, :profile, NOW())
    ");

    $insert->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':chave' => MYSQL_AES_KEY,
        ':passwrd' => $password,
        ':profile' => $profile
    ]);
}

function validar_login_agent($email, $password)
{
    $ligacao = ligar_bd();

    if (!$ligacao) {
        return null;
    }

    try {
        garantir_tabela_agents($ligacao);

        $stmt = $ligacao->prepare("
            SELECT
                id,
                nome,
                CAST(AES_DECRYPT(email, :chave) AS CHAR(255)) AS email,
                passwrd,
                profile,
                last_login
            FROM agents
            WHERE CAST(AES_DECRYPT(email, :chave) AS CHAR(255)) = :email
              AND deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([
            ':chave' => MYSQL_AES_KEY,
            ':email' => $email
        ]);

        $agente = $stmt->fetch();

        if (!$agente || $password !== $agente->passwrd) {
            return null;
        }

        $update = $ligacao->prepare("
            UPDATE agents
            SET last_login = NOW()
            WHERE id = :id
        ");

        $update->execute([
            ':id' => $agente->id
        ]);

        return $agente;
    } catch (PDOException $erro) {
        return null;
    }
}

if (!function_exists('perfil_nome')) {
function perfil_nome($profile = null)
{
    $profile = $profile ?? perfil_atual();

    $nomes = [
        'admin' => 'Administrador',
        'tecnico' => 'Técnico biomédico',
        'profissional_saude' => 'Profissional de saúde',
        'gestor_logistica' => 'Gestor de logística'
    ];

    return $nomes[$profile] ?? 'Utilizador';
}
}


if (!function_exists('perfil_tem_acesso')) {
function perfil_tem_acesso($modulo, $acao = 'ver')
{
    $profile = perfil_atual();

    $permissoes = [
        'admin' => [
            '*' => ['ver', 'criar', 'editar', 'remover', 'gerir']
        ],
        'tecnico' => [
            'dashboard' => ['ver'],
            'equipamentos' => ['ver', 'criar', 'editar'],
            'localizacoes' => ['ver', 'criar', 'editar'],
            'documentacao' => ['ver', 'criar', 'editar'],
            'contratos' => ['ver']
        ],
        'profissional_saude' => [
            'dashboard' => ['ver'],
            'equipamentos' => ['ver'],
            'documentacao' => ['ver'],
            'contratos' => ['ver']
        ],
        'gestor_logistica' => [
            'dashboard' => ['ver'],
            'fornecedores' => ['ver', 'criar', 'editar'],
            'documentacao' => ['ver', 'criar', 'editar'],
            'contratos' => ['ver', 'criar', 'editar'],
            'mensagens' => ['ver', 'gerir']
        ]
    ];

    if (!isset($permissoes[$profile])) {
        return false;
    }

    if (isset($permissoes[$profile]['*'])) {
        return in_array($acao, $permissoes[$profile]['*'], true);
    }

    if (!isset($permissoes[$profile][$modulo])) {
        return false;
    }

    return in_array($acao, $permissoes[$profile][$modulo], true);
}
}


if (!function_exists('exigir_perfil')) {
function exigir_perfil($modulo, $acao = 'ver')
{
    redirect_if_not_logged();

    if (!perfil_tem_acesso($modulo, $acao)) {
        header('Location: ' . BASE_URL . '/private/index.php');
        exit;
    }
}
}

