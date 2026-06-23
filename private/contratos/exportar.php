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
        SELECT c.codigo, e.codigo AS equipamento, e.designacao,
               tc.nome AS tipo_contrato, ec.nome AS estado, c.data_inicio,
               c.data_fim, c.periodicidade, c.valor, d.nome AS documento
        FROM contratos c
        LEFT JOIN equipamentos e ON e.id = c.equipamento_id
        LEFT JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id
        LEFT JOIN estados_contrato ec ON ec.id = c.estado_contrato_id
        LEFT JOIN documentos d ON d.id = c.documento_id
        ORDER BY c.codigo
    ");
    $stmt->execute();
    exportar_csv('contratos_medcontrol.csv',
        ['Codigo','Equipamento','Designacao','Tipo Contrato','Estado','Data Inicio','Data Fim','Periodicidade','Valor','Documento'],
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar contratos.';
    header('Location: index.php');
    exit;
}
