<?php
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/logs_eventos.php';

redirect_if_not_logged();
redirect_if_no_permission('contratos', 'remover');

$id_encriptado = $_GET['id'] ?? null;
$id_registo = validar_id_encriptado($id_encriptado);

if (!$id_registo) {
    registar_evento('remocao_logica', 'contratos', $id ?? null, 'Registo removido/desativado no módulo contratos.');

header('Location: index.php');
    exit;
}

$ligacao = ligar_bd();

if (!$ligacao) {
    header('Location: index.php');
    exit;
}

try {
    $stmt_estado = $ligacao->prepare("
        SELECT id
        FROM estados_contrato
        WHERE nome IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
        ORDER BY FIELD(nome, 'Expirado', 'Expirada', 'Inativo', 'Inativa')
        LIMIT 1
    ");
    $stmt_estado->execute();
    $estado_removido = $stmt_estado->fetchColumn();

    if (!$estado_removido) {
        throw new PDOException('Estado para contrato removido não encontrado.');
    }

    $stmt = $ligacao->prepare("UPDATE contratos SET estado_contrato_id = :estado WHERE id = :id");
    $stmt->execute([
        ':estado' => $estado_removido,
        ':id' => $id_registo
    ]);
    header('Location: remover.php?id=' . urlencode($id_encriptado) . '&removido=1');
    exit;
} catch (PDOException $erroBD) {
    header('Location: index.php');
    exit;
}
