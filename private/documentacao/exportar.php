<?php
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/logs_eventos.php';
require_once __DIR__ . '/../includes/exportacoes.php';

redirect_if_not_logged();

$formato = $_GET['formato'] ?? 'csv';

try {
    $ligacao = ligar_bd();

    if (!$ligacao) {
        throw new PDOException('Sem ligação à base de dados.');
    }

    $sql = <<<SQL
SELECT d.codigo, d.nome AS documento, e.codigo AS equipamento, e.designacao,
       td.nome AS tipo_documento, ed.nome AS estado, d.data_documento,
       d.data_validade, f.nome AS fornecedor
FROM documentos d
LEFT JOIN equipamentos e ON e.id = d.equipamento_id
LEFT JOIN tipos_documento td ON td.id = d.tipo_documento_id
LEFT JOIN estados_documento ed ON ed.id = d.estado_documento_id
LEFT JOIN fornecedores f ON f.id = d.fornecedor_id
ORDER BY d.codigo
SQL;

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    registar_evento(
        'exportacao_dados',
        'documentacao',
        null,
        'Exportação de dados do módulo documentacao em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'documento' => 'Documento', 'equipamento' => 'Equipamento', 'designacao' => 'Designação', 'tipo_documento' => 'Tipo de documento', 'estado' => 'Estado', 'data_documento' => 'Data do documento', 'data_validade' => 'Data de validade', 'fornecedor' => 'Fornecedor'];

    exportar_dados(
        $formato,
        'documentacao_medcontrol',
        'Documentação MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar documentacao.';
    header('Location: index.php');
    exit;
}
