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
SELECT c.codigo, e.codigo AS equipamento, e.designacao,
       tc.nome AS tipo_contrato, ec.nome AS estado, c.data_inicio,
       c.data_fim, c.periodicidade, c.valor, d.nome AS documento
FROM contratos c
LEFT JOIN equipamentos e ON e.id = c.equipamento_id
LEFT JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id
LEFT JOIN estados_contrato ec ON ec.id = c.estado_contrato_id
LEFT JOIN documentos d ON d.id = c.documento_id
ORDER BY c.codigo
SQL;

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    registar_evento(
        'exportacao_dados',
        'contratos',
        null,
        'Exportação de dados do módulo contratos em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'equipamento' => 'Equipamento', 'designacao' => 'Designação', 'tipo_contrato' => 'Tipo de contrato', 'estado' => 'Estado', 'data_inicio' => 'Data início', 'data_fim' => 'Data fim', 'periodicidade' => 'Periodicidade', 'valor' => 'Valor', 'documento' => 'Documento'];

    exportar_dados(
        $formato,
        'contratos_medcontrol',
        'Contratos MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar contratos.';
    header('Location: index.php');
    exit;
}
