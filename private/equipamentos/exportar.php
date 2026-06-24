<?php

// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/logs_eventos.php';
require_once __DIR__ . '/../includes/exportacoes.php';

// Proteção da página: impede acesso sem autenticação.
redirect_if_not_logged();

// Formato de exportação solicitado pelo utilizador.
$formato = $_GET['formato'] ?? 'csv';

// Execução protegida por try/catch para tratar erros de base de dados ou processamento.
try {
    // Estabelece a ligação à base de dados através da função centralizada.
$ligacao = ligar_bd();

    // Verifica se a ligação à base de dados foi estabelecida corretamente.
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

    // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Registo de evento relevante para auditoria e acompanhamento do sistema.
registar_evento(
        'exportacao_dados',
        'equipamentos',
        null,
        'Exportação de dados do módulo equipamentos em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'equipamento' => 'Equipamento', 'marca_modelo' => 'Marca/Modelo', 'numero_serie' => 'Número de série', 'categoria' => 'Categoria', 'localizacao' => 'Localização', 'estado' => 'Estado', 'criticidade' => 'Criticidade', 'data_aquisicao' => 'Data de aquisição', 'custo' => 'Custo'];

    // Chamada da função de exportação para gerar o ficheiro no formato escolhido.
exportar_dados(
        $formato,
        'equipamentos_medcontrol',
        'Equipamentos MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar equipamentos.';
    // Redirecionamento do utilizador após a operação ou validação.
header('Location: index.php');
    exit;
}
