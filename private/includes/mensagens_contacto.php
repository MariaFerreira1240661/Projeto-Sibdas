<?php
require_once __DIR__ . '/funcoes.php';

function garantir_tabela_mensagens_contacto($ligacao)
{
    $ligacao->exec("
        CREATE TABLE IF NOT EXISTS mensagens_contacto (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            mensagem TEXT NOT NULL,
            estado VARCHAR(30) NOT NULL DEFAULT 'Nova',
            data_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            lida_em DATETIME NULL,
            arquivada TINYINT(1) NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

function guardar_mensagem_contacto($nome, $email, $mensagem)
{
    $ligacao = ligar_bd();

    if (!$ligacao) {
        return false;
    }

    try {
        garantir_tabela_mensagens_contacto($ligacao);

        $stmt = $ligacao->prepare("
            INSERT INTO mensagens_contacto (nome, email, mensagem, estado, data_envio, arquivada)
            VALUES (:nome, :email, :mensagem, 'Nova', NOW(), 0)
        ");

        $stmt->execute([
            ':nome' => trim((string) $nome),
            ':email' => trim((string) $email),
            ':mensagem' => trim((string) $mensagem)
        ]);

        return true;
    } catch (PDOException $erro) {
        return false;
    }
}

function contar_mensagens_contacto($ligacao, $condicao = '1 = 1')
{
    garantir_tabela_mensagens_contacto($ligacao);

    return (int) $ligacao
        ->query("SELECT COUNT(*) FROM mensagens_contacto WHERE $condicao")
        ->fetchColumn();
}

function obter_mensagens_contacto_recentes($ligacao, $limite = 5)
{
    garantir_tabela_mensagens_contacto($ligacao);

    $stmt = $ligacao->prepare("
        SELECT id, nome, email, mensagem, estado, data_envio, lida_em, arquivada
        FROM mensagens_contacto
        WHERE arquivada = 0
        ORDER BY data_envio DESC
        LIMIT :limite
    ");

    $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function obter_mensagem_contacto($ligacao, $id)
{
    garantir_tabela_mensagens_contacto($ligacao);

    $stmt = $ligacao->prepare("
        SELECT id, nome, email, mensagem, estado, data_envio, lida_em, arquivada
        FROM mensagens_contacto
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([':id' => (int) $id]);

    return $stmt->fetch();
}

function marcar_mensagem_contacto_lida($ligacao, $id)
{
    garantir_tabela_mensagens_contacto($ligacao);

    $stmt = $ligacao->prepare("
        UPDATE mensagens_contacto
        SET estado = 'Lida',
            lida_em = COALESCE(lida_em, NOW())
        WHERE id = :id
    ");

    return $stmt->execute([':id' => (int) $id]);
}

function arquivar_mensagem_contacto($ligacao, $id)
{
    garantir_tabela_mensagens_contacto($ligacao);

    $stmt = $ligacao->prepare("
        UPDATE mensagens_contacto
        SET estado = 'Arquivada',
            arquivada = 1,
            lida_em = COALESCE(lida_em, NOW())
        WHERE id = :id
    ");

    return $stmt->execute([':id' => (int) $id]);
}

function classe_estado_mensagem($estado)
{
    $classes = [
        'Nova' => 'pendente',
        'Lida' => 'ativo',
        'Arquivada' => 'inativo'
    ];

    return $classes[$estado] ?? '';
}

function data_mensagem_pt($data)
{
    if (empty($data)) {
        return 'Não aplicável';
    }

    return date('d/m/Y H:i', strtotime($data));
}
