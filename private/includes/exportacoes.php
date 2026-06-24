<?php


// Gera um ficheiro CSV a partir dos dados recebidos.
function exportar_csv_generico($nome_ficheiro, $colunas, $linhas)
{
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $saida = fopen('php://output', 'w');
    fwrite($saida, "\xEF\xBB\xBF");
    fputcsv($saida, array_values($colunas), ';');

    foreach ($linhas as $linha) {
        $dados = [];

        foreach ($colunas as $chave => $titulo) {
            $dados[] = $linha[$chave] ?? '';
        }

        fputcsv($saida, $dados, ';');
    }

    fclose($saida);
    exit;
}

// Gera um ficheiro JSON a partir dos dados recebidos.
function exportar_json_generico($nome_ficheiro, $titulo, $linhas)
{
    header('Content-Type: application/json; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode([
        'titulo' => $titulo,
        'gerado_em' => date('c'),
        'total_registos' => count($linhas),
        'dados' => $linhas
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function texto_pdf($texto)
{
    $texto = trim((string) $texto);
    $texto = str_replace(["\r", "\n", "\t"], ' ', $texto);

    if (function_exists('iconv')) {
        $convertido = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
        if ($convertido !== false) {
            $texto = $convertido;
        }
    }

    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $texto);
}

function construir_pdf_simples($paginas)
{
    $objetos = [];
    $objetos[1] = '<< /Type /Catalog /Pages 2 0 R >>';
    $objetos[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

    $referencias_paginas = [];
    $numero_objeto = 4;

    foreach ($paginas as $pagina) {
        $conteudo = "BT\n/F1 9 Tf\n50 800 Td\n12 TL\n";

        foreach ($pagina as $linha) {
            $conteudo .= '(' . texto_pdf($linha) . ") Tj\nT*\n";
        }

        $conteudo .= "ET\n";

        $objeto_conteudo = $numero_objeto++;
        $objeto_pagina = $numero_objeto++;

        $objetos[$objeto_conteudo] = '<< /Length ' . strlen($conteudo) . " >>\nstream\n" . $conteudo . "endstream";
        $objetos[$objeto_pagina] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents ' . $objeto_conteudo . ' 0 R >>';
        $referencias_paginas[] = $objeto_pagina . ' 0 R';
    }

    $objetos[2] = '<< /Type /Pages /Kids [' . implode(' ', $referencias_paginas) . '] /Count ' . count($referencias_paginas) . ' >>';
    ksort($objetos);

    $pdf = "%PDF-1.4\n";
    $offsets = [0];

    foreach ($objetos as $numero => $conteudo_objeto) {
        $offsets[$numero] = strlen($pdf);
        $pdf .= $numero . " 0 obj\n" . $conteudo_objeto . "\nendobj\n";
    }

    $inicio_xref = strlen($pdf);
    $total_objetos = max(array_keys($objetos)) + 1;
    $pdf .= "xref\n0 " . $total_objetos . "\n";
    $pdf .= "0000000000 65535 f \n";

    for ($i = 1; $i < $total_objetos; $i++) {
        $pdf .= sprintf('%010d 00000 n ', $offsets[$i] ?? 0) . "\n";
    }

    $pdf .= "trailer\n<< /Size " . $total_objetos . " /Root 1 0 R >>\nstartxref\n" . $inicio_xref . "\n%%EOF";

    return $pdf;
}

// Gera uma versão simples em PDF/HTML dos dados exportados.
function exportar_pdf_generico($nome_ficheiro, $titulo, $colunas, $linhas)
{
    $linhas_pdf = [];
    $linhas_pdf[] = $titulo;
    $linhas_pdf[] = 'Gerado em: ' . date('Y-m-d H:i:s');
    $linhas_pdf[] = 'Total de registos: ' . count($linhas);
    $linhas_pdf[] = str_repeat('-', 95);

    foreach ($linhas as $indice => $linha) {
        $linhas_pdf[] = 'Registo ' . ($indice + 1);

        foreach ($colunas as $chave => $titulo_coluna) {
            $valor = $linha[$chave] ?? '';
            $texto = $titulo_coluna . ': ' . $valor;
            $partes = explode("\n", wordwrap($texto, 95, "\n", true));

            foreach ($partes as $parte) {
                $linhas_pdf[] = $parte;
            }
        }

        $linhas_pdf[] = '';
    }

    $paginas = array_chunk($linhas_pdf, 58);

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo construir_pdf_simples($paginas);
    exit;
}

function exportar_dados($formato, $nome_base, $titulo, $colunas, $linhas)
{
    $formato = strtolower(trim((string) $formato));

    if ($formato === '') {
        $formato = 'csv';
    }

    if ($formato === 'excel') {
        $formato = 'csv';
    }

    if (!in_array($formato, ['csv', 'json', 'pdf'], true)) {
        $formato = 'csv';
    }

    if ($formato === 'json') {
        exportar_json_generico($nome_base . '.json', $titulo, $linhas);
    }

    if ($formato === 'pdf') {
        exportar_pdf_generico($nome_base . '.pdf', $titulo, $colunas, $linhas);
    }

    exportar_csv_generico($nome_base . '.csv', $colunas, $linhas);
}
