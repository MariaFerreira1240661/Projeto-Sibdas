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
    ");
    $stmt->execute();
    exportar_csv('equipamentos_medcontrol.csv',
        ['Codigo','Equipamento','Marca Modelo','Numero Serie','Categoria','Localizacao','Estado','Criticidade','Data Aquisicao','Custo'],
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar equipamentos.';
    header('Location: index.php');
    exit;
}
