<?php

// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
require_once __DIR__ . '/funcoes.php';

function obter_utilizador_id_atual()
{
    if (!empty($_SESSION['utilizador_id'])) {
        return (int) $_SESSION['utilizador_id'];
    }

    if (empty($_SESSION['utilizador'])) {
        return null;
    }

    // Execução protegida por try/catch para tratar erros de base de dados ou processamento.
try {
        // Estabelece a ligação à base de dados através da função centralizada.
$ligacao = ligar_bd();

        // Verifica se a ligação à base de dados foi estabelecida corretamente.
if (!$ligacao) {
            return null;
        }

        // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare("SELECT id FROM utilizadores WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $_SESSION['utilizador']]);
        $id = $stmt->fetchColumn();

        return $id ? (int) $id : null;
    } catch (PDOException $erro) {
        return null;
    }
}

function obter_ip_utilizador()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }

    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function garantir_tabela_logs($ligacao)
{
    // Usa a tabela existente do projeto: logs_eventos.
    // Não cria a tabela logs.
    $ligacao->exec("
        CREATE TABLE IF NOT EXISTS logs_eventos (
            id INT NOT NULL AUTO_INCREMENT,
            utilizador_id INT NULL,
            tipo_evento VARCHAR(80) NOT NULL,
            entidade VARCHAR(80) NULL,
            entidade_id INT NULL,
            descricao TEXT NOT NULL,
            ip VARCHAR(45) NULL,
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX fk_logs_utilizador (utilizador_id),
            CONSTRAINT fk_logs_utilizador
                FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

function normalizar_tipo_evento($tipo_evento)
{
    $tipo = strtolower(str_replace([' ', '-'], '_', trim((string) $tipo_evento)));

    $mapa = [
        'login_ok' => 'login_sucesso',
        'login_sucesso' => 'login_sucesso',
        'login_falhou' => 'login_falhado',
        'login_falhado' => 'login_falhado',
        'login_falha' => 'login_falhado',
        'criacao' => 'dados_alterados',
        'criação' => 'dados_alterados',
        'edicao' => 'dados_alterados',
        'edição' => 'dados_alterados',
        'atualizacao' => 'dados_alterados',
        'atualização' => 'dados_alterados',
        'alteracao' => 'dados_alterados',
        'alteração' => 'dados_alterados',
        'remocao' => 'dados_alterados',
        'remoção' => 'dados_alterados',
        'remocao_logica' => 'dados_alterados',
        'remoção_lógica' => 'dados_alterados',
        'delete' => 'dados_alterados',
        'dados_alterados' => 'dados_alterados',
        'exportacao' => 'exportacao_dados',
        'exportação' => 'exportacao_dados',
        'exportacao_dados' => 'exportacao_dados',
        'exportação_dados' => 'exportacao_dados',
        'erro' => 'erro_sistema',
        'erro_sistema' => 'erro_sistema'
    ];

    return $mapa[$tipo] ?? $tipo;
}

function // Registo de evento relevante para auditoria e acompanhamento do sistema.
registar_evento($tipo_evento, $entidade = null, $entidade_id = null, $descricao = null, $utilizador_id = null)
{
    try {
        $ligacao = ligar_bd();

        if (!$ligacao) {
            return false;
        }

        garantir_tabela_logs($ligacao);

        $stmt = $ligacao->prepare("
            INSERT INTO logs_eventos (
                utilizador_id,
                tipo_evento,
                entidade,
                entidade_id,
                descricao,
                ip,
                criado_em
            ) VALUES (
                :utilizador_id,
                :tipo_evento,
                :entidade,
                :entidade_id,
                :descricao,
                :ip,
                NOW()
            )
        ");

        $stmt->execute([
            ':utilizador_id' => $utilizador_id ?? obter_utilizador_id_atual(),
            ':tipo_evento' => normalizar_tipo_evento($tipo_evento),
            ':entidade' => $entidade,
            ':entidade_id' => $entidade_id,
            ':descricao' => $descricao ?: 'Evento registado no sistema.',
            ':ip' => obter_ip_utilizador()
        ]);

        return true;
    } catch (PDOException $erro) {
        return false;
    }
}
