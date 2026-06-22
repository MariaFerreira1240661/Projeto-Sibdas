<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$id_encriptado = $_GET['id'] ?? null;
$id_registo = validar_id_encriptado($id_encriptado);

if (!$id_registo) {
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
