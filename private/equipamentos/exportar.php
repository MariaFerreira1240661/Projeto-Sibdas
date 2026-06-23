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
SELECT e.codigo, e.designacao AS equipamento, CONCAT(e.marca, ' ', e.modelo) AS marca_modelo,
       e.numero_serie, ce.nome AS categoria,
       CONCAT(l.codigo, ' - ', l.edificio, ' / ', e.localizacao_servico) AS localizacao,
       ee.nome AS estado, c.nome AS criticidade, e.data_aquisicao, e.custo
FROM equipamentos e
LEFT JOIN categorias_equipamento ce ON ce.id = e.categoria_equipamento_id
LEFT JOIN localizacoes l ON l.id = e.localizacao_id
LEFT JOIN estados_equipamento ee ON ee.id = e.estado_equipamento_id
LEFT JOIN criticidades c ON c.id = e.criticidade_id
WHERE (e.observacoes IS NULL OR e.observacoes <> 'RASCUNHO_REGISTO')
ORDER BY e.codigo
SQL;

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    registar_evento(
        'exportacao_dados',
        'equipamentos',
        null,
        'Exportação de dados do módulo equipamentos em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'equipamento' => 'Equipamento', 'marca_modelo' => 'Marca/Modelo', 'numero_serie' => 'Número de série', 'categoria' => 'Categoria', 'localizacao' => 'Localização', 'estado' => 'Estado', 'criticidade' => 'Criticidade', 'data_aquisicao' => 'Data de aquisição', 'custo' => 'Custo'];

    exportar_dados(
        $formato,
        'equipamentos_medcontrol',
        'Equipamentos MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar equipamentos.';
    header('Location: index.php');
    exit;
}
