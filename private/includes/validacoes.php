<?php

function normalizar_codigo(string $codigo): string
{
    return strtoupper(preg_replace('/\s+/', '', trim($codigo)));
}

function validar_campo_obrigatorio($valor, string $nome_campo): array
{
    // Array utilizado para acumular mensagens de erro de validação.
$erros = [];

    if (trim((string) $valor) === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    }

    return $erros;
}

function validar_codigo_prefixo($valor, string $prefixo, string $exemplo, string $nome_campo = 'código interno'): array
{
    $erros = [];
    $codigo = normalizar_codigo((string) $valor);

    if ($codigo === '') {
        $erros[] = 'O ' . $nome_campo . ' é obrigatório.';
    } elseif (!preg_match('/^' . preg_quote($prefixo, '/') . '[0-9]{3}$/', $codigo)) {
        $erros[] = 'O ' . $nome_campo . ' deve seguir o formato ' . $exemplo . '.';
    }

    return $erros;
}

function validar_inteiro_positivo($valor, string $nome_campo): array
{
    $erros = [];
    $valor = trim((string) $valor);

    if ($valor === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    } elseif (!ctype_digit($valor) || (int) $valor <= 0) {
        $erros[] = 'O campo ' . $nome_campo . ' deve ser um número inteiro superior a 0.';
    }

    return $erros;
}

function validar_numero_decimal_positivo($valor, string $nome_campo): array
{
    $erros = [];
    $valor = str_replace(',', '.', trim((string) $valor));

    if ($valor === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    } elseif (!is_numeric($valor) || (float) $valor < 0) {
        $erros[] = 'O campo ' . $nome_campo . ' deve ser um número válido.';
    }

    return $erros;
}

function validar_apenas_letras($valor, string $nome_campo): array
{
    $erros = [];
    $valor = trim((string) $valor);

    if ($valor === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/u', $valor)) {
        $erros[] = 'O campo ' . $nome_campo . ' deve conter apenas letras.';
    }

    return $erros;
}

function validar_email_php($valor, string $nome_campo = 'email'): array
{
    $erros = [];
    $valor = trim((string) $valor);

    if ($valor === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    } elseif (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'O campo ' . $nome_campo . ' deve conter um email válido.';
    }

    return $erros;
}

function validar_telefone_php($valor, string $nome_campo = 'telefone'): array
{
    $erros = [];
    $valor = trim((string) $valor);
    $quantidade_numeros = strlen(preg_replace('/[^0-9]/', '', $valor));

    if ($valor === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    } elseif (!preg_match('/^[0-9+\s]+$/', $valor) || $quantidade_numeros < 9 || $quantidade_numeros > 15) {
        $erros[] = 'O campo ' . $nome_campo . ' deve conter apenas números, espaços ou o sinal +, e ter entre 9 e 15 números.';
    }

    return $erros;
}

function validar_nif_php($valor): array
{
    $erros = [];
    $valor = trim((string) $valor);

    if ($valor === '') {
        $erros[] = 'O NIF é obrigatório.';
    } elseif (!preg_match('/^[0-9]{9}$/', $valor)) {
        $erros[] = 'O NIF deve conter exatamente 9 números.';
    }

    return $erros;
}

function validar_website_php($valor): array
{
    $erros = [];
    $valor = trim((string) $valor);

    if ($valor === '') {
        $erros[] = 'O website do fornecedor é obrigatório.';
    } elseif (!preg_match('/^(https?:\/\/)?([\w-]+\.)+[\w-]{2,}(\/.*)?$/i', $valor)) {
        $erros[] = 'Insira um website válido, por exemplo www.empresa.pt.';
    }

    return $erros;
}

function validar_data_php($valor, string $nome_campo): array
{
    $erros = [];
    $valor = trim((string) $valor);

    if ($valor === '') {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
        return $erros;
    }

    $data = DateTime::createFromFormat('Y-m-d', $valor);

    if (!$data || $data->format('Y-m-d') !== $valor) {
        $erros[] = 'O campo ' . $nome_campo . ' deve conter uma data válida.';
    }

    return $erros;
}

function validar_opcao_id($valor, array $ids_validos, string $nome_campo): array
{
    $erros = [];
    $id = (int) $valor;

    if ($id <= 0) {
        $erros[] = 'O campo ' . $nome_campo . ' é obrigatório.';
    } elseif (!in_array($id, $ids_validos, true)) {
        $erros[] = 'A opção selecionada no campo ' . $nome_campo . ' não existe na base de dados.';
    }

    return $erros;
}
