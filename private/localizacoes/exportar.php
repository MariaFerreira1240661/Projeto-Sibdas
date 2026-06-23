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
SELECT l.codigo, l.edificio, l.numero_pisos, l.responsavel,
       l.contacto_interno AS contacto, COUNT(e.id) AS equipamentos, el.nome AS estado
FROM localizacoes l
LEFT JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
LEFT JOIN equipamentos e ON e.localizacao_id = l.id
     AND (e.observacoes IS NULL OR e.observacoes <> 'RASCUNHO_REGISTO')
WHERE l.codigo <> 'LOC-TMP'
  AND l.edificio <> 'Por definir'
GROUP BY l.id, l.codigo, l.edificio, l.numero_pisos, l.responsavel, l.contacto_interno, el.nome
ORDER BY l.codigo
SQL;

    $stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    registar_evento(
        'exportacao_dados',
        'localizacoes',
        null,
        'Exportação de dados do módulo localizacoes em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'edificio' => 'Edifício', 'numero_pisos' => 'Número de pisos', 'responsavel' => 'Responsável', 'contacto' => 'Contacto', 'equipamentos' => 'Equipamentos', 'estado' => 'Estado'];

    exportar_dados(
        $formato,
        'localizacoes_medcontrol',
        'Localizações MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar localizacoes.';
    header('Location: index.php');
    exit;
}
