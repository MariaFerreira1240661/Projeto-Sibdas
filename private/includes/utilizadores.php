<?php
require_once __DIR__ . '/funcoes.php';

function coluna_existe_utilizadores($ligacao, $nome_coluna)
{
    $stmt = $ligacao->prepare("
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'utilizadores'
          AND COLUMN_NAME = :coluna
    ");

    $stmt->execute([':coluna' => $nome_coluna]);

    return (int) $stmt->fetchColumn() > 0;
}

function garantir_tabela_utilizadores($ligacao)
{
    $ligacao->exec("
        CREATE TABLE IF NOT EXISTS utilizadores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            perfil VARCHAR(60) NOT NULL,
            ativo TINYINT(1) NOT NULL DEFAULT 1,
            ultimo_login DATETIME NULL,
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            atualizado_em DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    if (!coluna_existe_utilizadores($ligacao, 'nome')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN nome VARCHAR(150) NULL AFTER id");
    }

    if (!coluna_existe_utilizadores($ligacao, 'email')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN email VARCHAR(150) NULL AFTER nome");
    }

    if (!coluna_existe_utilizadores($ligacao, 'password_hash')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN password_hash VARCHAR(255) NULL AFTER email");
    }

    if (!coluna_existe_utilizadores($ligacao, 'perfil')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN perfil VARCHAR(60) NULL AFTER password_hash");
    }

    if (!coluna_existe_utilizadores($ligacao, 'ativo')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1 AFTER perfil");
    }

    if (!coluna_existe_utilizadores($ligacao, 'ultimo_login')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN ultimo_login DATETIME NULL AFTER ativo");
    }

    if (!coluna_existe_utilizadores($ligacao, 'criado_em')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER ultimo_login");
    }

    if (!coluna_existe_utilizadores($ligacao, 'atualizado_em')) {
        $ligacao->exec("ALTER TABLE utilizadores ADD COLUMN atualizado_em DATETIME NULL AFTER criado_em");
    }

    inserir_utilizadores_default($ligacao);
}

function inserir_utilizadores_default($ligacao)
{
    inserir_utilizador_default($ligacao, 'Maria Ferreira', 'admin@medcontrol.pt', '$2y$12$KFKwhtt/xvZ/tx0.FhroouIjSb8B2INpQnL4ND.4sl7H0mydbkiii', 'admin');
    inserir_utilizador_default($ligacao, 'Tiago Martins', 'tecnico@medcontrol.pt', '$2y$12$9hWurEcA7GjevKMGfH8uweQbc/b9i3PEwhl7nG7K5/1lv83/rM9WG', 'tecnico');
    inserir_utilizador_default($ligacao, 'Ana Costa', 'profissional@medcontrol.pt', '$2y$12$t.KCdBWkrHwMQ6gfPcJ/fOBzuIc4Iq0DX0dHtCXRhZzfz.1Tnpct2', 'profissional_saude');
    inserir_utilizador_default($ligacao, 'João Pereira', 'logistica@medcontrol.pt', '$2y$12$uwTSCHQr8xWRLEFxGFx7AevKwC39W7/W97N8ylZ17gi3ZbDczYz6u', 'gestor_logistica');
}

function inserir_utilizador_default($ligacao, $nome, $email, $password_hash, $perfil)
{
    $stmt = $ligacao->prepare("
        SELECT id
        FROM utilizadores
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([':email' => $email]);
    $utilizador_existente = $stmt->fetch();

    if ($utilizador_existente) {
        $update = $ligacao->prepare("
            UPDATE utilizadores
            SET nome = :nome,
                password_hash = :password_hash,
                perfil = :perfil,
                ativo = 1,
                atualizado_em = NOW()
            WHERE id = :id
        ");

        $update->execute([
            ':nome' => $nome,
            ':password_hash' => $password_hash,
            ':perfil' => $perfil,
            ':id' => $utilizador_existente->id
        ]);

        return;
    }

    $insert = $ligacao->prepare("
        INSERT INTO utilizadores (nome, email, password_hash, perfil, ativo, criado_em, atualizado_em)
        VALUES (:nome, :email, :password_hash, :perfil, 1, NOW(), NOW())
    ");

    $insert->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':password_hash' => $password_hash,
        ':perfil' => $perfil
    ]);
}

function validar_login_utilizador($email, $password)
{
    $ligacao = ligar_bd();

    if (!$ligacao) {
        return null;
    }

    try {
        garantir_tabela_utilizadores($ligacao);

        $stmt = $ligacao->prepare("
            SELECT id, nome, email, password_hash, perfil, ativo, ultimo_login
            FROM utilizadores
            WHERE email = :email
              AND ativo = 1
            LIMIT 1
        ");

        $stmt->execute([
            ':email' => $email
        ]);

        $utilizador = $stmt->fetch();

        if (!$utilizador || !password_verify($password, $utilizador->password_hash)) {
            return null;
        }

        $update = $ligacao->prepare("
            UPDATE utilizadores
            SET ultimo_login = NOW(),
                atualizado_em = NOW()
            WHERE id = :id
        ");

        $update->execute([
            ':id' => $utilizador->id
        ]);

        return $utilizador;
    } catch (PDOException $erro) {
        return null;
    }
}
