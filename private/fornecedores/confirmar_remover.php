<?php
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/logs_eventos.php';

redirect_if_not_logged();
redirect_if_no_permission('fornecedores', 'remover');

$id_encriptado = $_GET['id'] ?? null;
$id_registo = validar_id_encriptado($id_encriptado);

if (!$id_registo) {
    registar_evento('remocao_logica', 'fornecedores', $id ?? null, 'Registo removido/desativado no módulo fornecedores.');

header('Location: index.php');
    exit;
}

$ligacao = ligar_bd();

if (!$ligacao) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $ligacao->prepare("UPDATE fornecedores SET ativo = 0 WHERE id = :id");
    $stmt->execute([
        ':id' => $id_registo
    ]);
    header('Location: remover.php?id=' . urlencode($id_encriptado) . '&removido=1');
    exit;
} catch (PDOException $erroBD) {
    header('Location: index.php');
    exit;
}
