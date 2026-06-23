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
    ");
    $stmt->execute();
    exportar_csv('localizacoes_medcontrol.csv',
        ['Codigo','Edificio','Numero Pisos','Responsavel','Contacto','Equipamentos','Estado'],
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
} catch (PDOException $erro) {
    $_SESSION['server_error'] = 'Erro ao exportar localizações.';
    header('Location: index.php');
    exit;
}
