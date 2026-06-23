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
SELECT f.codigo, f.nome AS fornecedor, f.nif, f.telefone, f.email, f.website, f.morada,
       COUNT(ef.id) AS equipamentos,
       CASE WHEN f.ativo = 1 THEN 'Ativo' ELSE 'Inativo' END AS estado
FROM fornecedores f
LEFT JOIN equipamento_fornecedores ef ON ef.fornecedor_id = f.id
GROUP BY f.id, f.codigo, f.nome, f.nif, f.telefone, f.email, f.website, f.morada, f.ativo
ORDER BY f.codigo
SQL;

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    registar_evento(
        'exportacao_dados',
        'fornecedores',
        null,
        'Exportação de dados do módulo fornecedores em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'fornecedor' => 'Fornecedor', 'nif' => 'NIF', 'telefone' => 'Telefone', 'email' => 'Email', 'website' => 'Website', 'morada' => 'Morada', 'equipamentos' => 'Equipamentos', 'estado' => 'Estado'];

    exportar_dados(
        $formato,
        'fornecedores_medcontrol',
        'Fornecedores MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar fornecedores.';
    header('Location: index.php');
    exit;
}
