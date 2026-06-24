<?php

// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/logs_eventos.php';

// Proteção da página: impede acesso sem autenticação.
redirect_if_not_logged();
// Controlo de permissões: verifica se o perfil autenticado pode aceder a esta funcionalidade.
redirect_if_no_permission('localizacoes', 'remover');

// Receção e validação do identificador encriptado do registo a remover.
$id_encriptado = $_GET['id'] ?? null;
$id_registo = validar_id_encriptado($id_encriptado);

if (!$id_registo) {
    // Registo de evento relevante para auditoria e acompanhamento do sistema.
registar_evento('remocao_logica', 'localizacoes', $id ?? null, 'Registo removido/desativado no módulo localizacoes.');

// Redirecionamento do utilizador após a operação ou validação.
header('Location: index.php');
    exit;
}

// Estabelece a ligação à base de dados através da função centralizada.
$ligacao = ligar_bd();

// Verifica se a ligação à base de dados foi estabelecida corretamente.
if (!$ligacao) {
    header('Location: index.php');
    exit;
}

// Execução protegida por try/catch para tratar erros de base de dados ou processamento.
try {
    $stmt_estado = $ligacao->prepare("
        SELECT id
        FROM estados_localizacao
        WHERE nome IN ('Inativa', 'Inativo')
        ORDER BY FIELD(nome, 'Inativa', 'Inativo')
        LIMIT 1
    ");
    $stmt_estado->execute();
    $estado_inativo = $stmt_estado->fetchColumn();

    if (!$estado_inativo) {
        throw new PDOException('Estado inativo não encontrado.');
    }

    // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare("UPDATE localizacoes SET estado_localizacao_id = :estado WHERE id = :id");
    $stmt->execute([
        ':estado' => $estado_inativo,
        ':id' => $id_registo
    ]);
    header('Location: remover.php?id=' . urlencode($id_encriptado) . '&removido=1');
    exit;
} catch (PDOException $erroBD) {
    header('Location: index.php');
    exit;
}
