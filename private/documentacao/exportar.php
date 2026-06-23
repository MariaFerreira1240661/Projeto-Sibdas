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
        SELECT d.codigo, d.nome AS documento, e.codigo AS equipamento, e.designacao,
               td.nome AS tipo_documento, ed.nome AS estado, d.data_documento,
               d.data_validade, f.nome AS fornecedor
        FROM documentos d
        LEFT JOIN equipamentos e ON e.id = d.equipamento_id
        LEFT JOIN tipos_documento td ON td.id = d.tipo_documento_id
        LEFT JOIN estados_documento ed ON ed.id = d.estado_documento_id
        LEFT JOIN fornecedores f ON f.id = d.fornecedor_id
        ORDER BY d.codigo
    ");
    $stmt->execute();
    exportar_csv('documentacao_medcontrol.csv',
        ['Codigo','Documento','Equipamento','Designacao','Tipo Documento','Estado','Data Documento','Data Validade','Fornecedor'],
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar documentação.';
    header('Location: index.php');
    exit;
}
