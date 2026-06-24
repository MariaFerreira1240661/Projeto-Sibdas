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

    // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Registo de evento relevante para auditoria e acompanhamento do sistema.
registar_evento(
        'exportacao_dados',
        'documentacao',
        null,
        'Exportação de dados do módulo documentacao em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'documento' => 'Documento', 'equipamento' => 'Equipamento', 'designacao' => 'Designação', 'tipo_documento' => 'Tipo de documento', 'estado' => 'Estado', 'data_documento' => 'Data do documento', 'data_validade' => 'Data de validade', 'fornecedor' => 'Fornecedor'];

    // Chamada da função de exportação para gerar o ficheiro no formato escolhido.
exportar_dados(
        $formato,
        'documentacao_medcontrol',
        'Documentação MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar documentacao.';
    // Redirecionamento do utilizador após a operação ou validação.
header('Location: index.php');
    exit;
}
