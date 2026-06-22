<?php
require_once __DIR__ . '/funcoes.php';

function h_conteudo($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function conteudos_publicos_default()
{
    return [
        'logo_texto' => 'MedControl',
        'nav_inicio' => 'Início',
        'nav_sobre' => 'Sobre Nós',
        'nav_servicos' => 'Serviços',
        'nav_contactos' => 'Contactos',
        'nav_area_reservada' => 'Área Reservada',

        'inicio_titulo' => 'MedControl',
        'inicio_texto' => 'Sistema web para gestão de inventário hospitalar de equipamentos médicos, documentação técnica, fornecedores, garantias e contratos.',
        'inicio_botao' => 'Conhecer serviços',

        'sobre_subtitulo' => 'Sobre a MedControl',
        'sobre_titulo' => 'Uma solução criada para simplificar a gestão hospitalar',
        'sobre_texto_1' => 'A MedControl é uma aplicação web desenvolvida para apoiar instituições de saúde na organização do inventário hospitalar de equipamentos médicos.',
        'sobre_texto_2' => 'A plataforma permite centralizar informação sobre equipamentos, fornecedores, documentação técnica, garantias e contratos, tornando a consulta mais rápida, simples e organizada.',
        'sobre_lista_titulo' => 'O que torna a MedControl útil?',
        'sobre_lista_item_1' => 'Reduz a dependência de folhas de cálculo dispersas.',
        'sobre_lista_item_2' => 'Facilita a localização e consulta de equipamentos.',
        'sobre_lista_item_3' => 'Centraliza documentação técnica e administrativa.',
        'sobre_lista_item_4' => 'Apoia a rastreabilidade e a gestão do ciclo de vida dos equipamentos.',

        'servicos_titulo' => 'Serviços',
        'servicos_texto' => 'A MedControl disponibiliza ferramentas para apoiar a organização, consulta e controlo do inventário hospitalar.',
        'servico_equipamentos_titulo' => 'Gestão de Equipamentos',
        'servico_equipamentos_texto' => 'Registo, consulta e atualização dos equipamentos médicos existentes no hospital.',
        'servico_localizacao_titulo' => 'Gestão de Localizações',
        'servico_localizacao_texto' => 'Organização dos equipamentos por edifício, piso, serviço e sala.',
        'servico_fornecedores_titulo' => 'Gestão de Fornecedores',
        'servico_fornecedores_texto' => 'Registo de fornecedores, fabricantes e entidades responsáveis pela assistência técnica.',
        'servico_documentacao_titulo' => 'Gestão Documental',
        'servico_documentacao_texto' => 'Associação de manuais, certificados, faturas, relatórios técnicos e outros documentos relevantes.',
        'servico_contratos_titulo' => 'Garantias e Contratos',
        'servico_contratos_texto' => 'Consulta de datas de garantia, contratos de manutenção e prazos importantes.',
        'servico_pesquisa_titulo' => 'Pesquisa e Filtragem',
        'servico_pesquisa_texto' => 'Pesquisa rápida por nome, categoria, estado, localização ou fornecedor.',

        'contactos_titulo' => 'Contactos',
        'contactos_intro' => 'Entre em contacto com a equipa MedControl para saber mais sobre a solução.',
        'contactos_info_titulo' => 'Informações',
        'contactos_email' => 'geral@medcontrol.pt',
        'contactos_telefone' => '+351 222 000 000',
        'contactos_localizacao' => 'Porto, Portugal',
        'contactos_label_nome' => 'Nome',
        'contactos_label_email' => 'Email',
        'contactos_label_mensagem' => 'Mensagem',
        'contactos_botao' => 'Enviar mensagem',

        'footer_texto_1' => '© 2025 MedControl. Todos os direitos reservados.',
        'footer_texto_2' => 'Sistema web de apoio ao inventário hospitalar de equipamentos médicos.'
    ];
}

function garantir_tabela_conteudos_publicos($ligacao)
{
    $ligacao->exec("
        CREATE TABLE IF NOT EXISTS conteudos_publicos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            chave VARCHAR(100) NOT NULL UNIQUE,
            valor TEXT NOT NULL,
            atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $defaults = conteudos_publicos_default();

    $stmt = $ligacao->prepare("
        INSERT IGNORE INTO conteudos_publicos (chave, valor)
        VALUES (:chave, :valor)
    ");

    foreach ($defaults as $chave => $valor) {
        $stmt->execute([
            ':chave' => $chave,
            ':valor' => $valor
        ]);
    }
}

function obter_conteudos_publicos()
{
    $defaults = conteudos_publicos_default();
    $ligacao = ligar_bd();

    if (!$ligacao) {
        return $defaults;
    }

    try {
        garantir_tabela_conteudos_publicos($ligacao);

        $stmt = $ligacao->query("SELECT chave, valor FROM conteudos_publicos");
        $linhas = $stmt->fetchAll();

        foreach ($linhas as $linha) {
            $defaults[$linha->chave] = $linha->valor;
        }
    } catch (PDOException $erro) {
        return $defaults;
    }

    return $defaults;
}

function guardar_conteudos_publicos($dados)
{
    $ligacao = ligar_bd();

    if (!$ligacao) {
        return false;
    }

    try {
        garantir_tabela_conteudos_publicos($ligacao);

        $stmt = $ligacao->prepare("
            INSERT INTO conteudos_publicos (chave, valor)
            VALUES (:chave, :valor)
            ON DUPLICATE KEY UPDATE
                valor = VALUES(valor),
                atualizado_em = NOW()
        ");

        foreach (conteudos_publicos_default() as $chave => $valor_default) {
            $stmt->execute([
                ':chave' => $chave,
                ':valor' => trim((string) ($dados[$chave] ?? ''))
            ]);
        }

        return true;
    } catch (PDOException $erro) {
        return false;
    }
}

function conteudo_publico($conteudos, $chave)
{
    return h_conteudo($conteudos[$chave] ?? '');
}
