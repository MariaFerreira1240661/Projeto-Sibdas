<?php
require_once __DIR__ . '/../includes/funcoes.php';
redirect_if_not_logged();

function exportar_csv($nome_ficheiro, $cabecalhos, $linhas)
{
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $saida = fopen('php://output', 'w');
    fwrite($saida, "\xEF\xBB\xBF");
    fputcsv($saida, $cabecalhos, ';');

    foreach ($linhas as $linha) {
        $dados = [];
        foreach ($cabecalhos as $campo) {
            $chave = strtolower($campo);
            $chave = str_replace(
                [' ', 'ç', 'ã', 'á', 'à', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'º', '.', '/'],
                ['_', 'c', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', '', '', '_'],
                $chave
            );
            $dados[] = $linha[$chave] ?? '';
        }
        fputcsv($saida, $dados, ';');
    }

    fclose($saida);
    exit;
}

try {
    $ligacao = ligar_bd();
    $stmt = $ligacao->prepare("
        SELECT f.codigo, f.nome AS fornecedor, f.nif, f.telefone, f.email, f.website, f.morada,
               COUNT(ef.id) AS equipamentos,
               CASE WHEN f.ativo = 1 THEN 'Ativo' ELSE 'Inativo' END AS estado
        FROM fornecedores f
        LEFT JOIN equipamento_fornecedores ef ON ef.fornecedor_id = f.id
        GROUP BY f.id, f.codigo, f.nome, f.nif, f.telefone, f.email, f.website, f.morada, f.ativo
        ORDER BY f.codigo
    ");
    $stmt->execute();
    exportar_csv('fornecedores_medcontrol.csv',
        ['Codigo','Fornecedor','NIF','Telefone','Email','Website','Morada','Equipamentos','Estado'],
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar fornecedores.';
    header('Location: index.php');
    exit;
}
