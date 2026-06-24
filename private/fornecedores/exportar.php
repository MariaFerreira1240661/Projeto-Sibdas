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
SELECT f.codigo, f.nome AS fornecedor, f.nif, f.telefone, f.email, f.website, f.morada,
       COUNT(ef.id) AS equipamentos,
       CASE WHEN f.ativo = 1 THEN 'Ativo' ELSE 'Inativo' END AS estado
FROM fornecedores f
LEFT JOIN equipamento_fornecedores ef ON ef.fornecedor_id = f.id
GROUP BY f.id, f.codigo, f.nome, f.nif, f.telefone, f.email, f.website, f.morada, f.ativo
ORDER BY f.codigo
SQL;

    // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare($sql);
    $stmt->execute();

    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Registo de evento relevante para auditoria e acompanhamento do sistema.
registar_evento(
        'exportacao_dados',
        'fornecedores',
        null,
        'Exportação de dados do módulo fornecedores em formato ' . strtoupper($formato) . '.'
    );

    $colunas = ['codigo' => 'Código', 'fornecedor' => 'Fornecedor', 'nif' => 'NIF', 'telefone' => 'Telefone', 'email' => 'Email', 'website' => 'Website', 'morada' => 'Morada', 'equipamentos' => 'Equipamentos', 'estado' => 'Estado'];

    // Chamada da função de exportação para gerar o ficheiro no formato escolhido.
exportar_dados(
        $formato,
        'fornecedores_medcontrol',
        'Fornecedores MedControl',
        $colunas,
        $linhas
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar fornecedores.';
    // Redirecionamento do utilizador após a operação ou validação.
header('Location: index.php');
    exit;
}
