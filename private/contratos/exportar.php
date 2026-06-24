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

    // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Registo de evento relevante para auditoria e acompanhamento do sistema.
registar_evento(
        'exportacao_dados',
        'contratos',
        null,
        'Exportação de dados do módulo contratos em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'equipamento' => 'Equipamento', 'designacao' => 'Designação', 'tipo_contrato' => 'Tipo de contrato', 'estado' => 'Estado', 'data_inicio' => 'Data início', 'data_fim' => 'Data fim', 'periodicidade' => 'Periodicidade', 'valor' => 'Valor', 'documento' => 'Documento'];

    // Chamada da função de exportação para gerar o ficheiro no formato escolhido.
exportar_dados(
        $formato,
        'contratos_medcontrol',
        'Contratos MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar contratos.';
    // Redirecionamento do utilizador após a operação ou validação.
header('Location: index.php');
    exit;
}
