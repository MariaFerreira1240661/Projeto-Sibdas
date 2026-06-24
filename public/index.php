<?php
require_once __DIR__ . '/../private/includes/conteudos_publicos.php';
require_once __DIR__ . '/../private/includes/mensagens_contacto.php';

$conteudos = obter_conteudos_publicos();
// Variáveis utilizadas para apresentar feedback e manter os dados do formulário de contacto.
$mensagem_contacto_sucesso = '';
$mensagem_contacto_erro = '';
$nome_contacto = '';
$email_contacto = '';
$texto_contacto = '';

// Processa o formulário de contacto quando este é submetido pelo utilizador.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formulario_contacto'] ?? '') === '1') {
    $nome_contacto = trim((string) ($_POST['nome'] ?? ''));
    $email_contacto = trim((string) ($_POST['email'] ?? ''));
    $texto_contacto = trim((string) ($_POST['mensagem'] ?? ''));

    // Valida os campos obrigatórios e o formato do email antes de guardar a mensagem.
    if ($nome_contacto === '' || $email_contacto === '' || $texto_contacto === '') {
        $mensagem_contacto_erro = 'Preencha todos os campos do formulário.';
    } elseif (!filter_var($email_contacto, FILTER_VALIDATE_EMAIL)) {
        $mensagem_contacto_erro = 'Indique um email válido.';
    } elseif (guardar_mensagem_contacto($nome_contacto, $email_contacto, $texto_contacto)) {
        $mensagem_contacto_sucesso = 'Mensagem enviada com sucesso. A equipa MedControl entrará em contacto brevemente.';
        $nome_contacto = '';
        $email_contacto = '';
        $texto_contacto = '';
    } else {
        $mensagem_contacto_erro = 'Não foi possível enviar a mensagem. Tente novamente mais tarde.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= conteudo_publico($conteudos, 'logo_texto') ?></title>

    <link rel="shortcut icon" href="../assets/img/medcontrol-logo1.png" type="image/png">
    <link href="../assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/1240661.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="#inicio">
                <img src="../assets/img/medcontrol-logo1.png" alt="Logótipo da MedControl">
            </a>
            <h1><?= conteudo_publico($conteudos, 'logo_texto') ?></h1>
        </div>

        <!-- Menu com ligações para as secções da página e para a área reservada -->
        <nav>
            <a href="#inicio"><?= conteudo_publico($conteudos, 'nav_inicio') ?></a>
            <a href="#sobre-nos"><?= conteudo_publico($conteudos, 'nav_sobre') ?></a>
            <a href="#servicos"><?= conteudo_publico($conteudos, 'nav_servicos') ?></a>
            <a href="#contactos"><?= conteudo_publico($conteudos, 'nav_contactos') ?></a>
            <a href="login.php"><?= conteudo_publico($conteudos, 'nav_area_reservada') ?></a>
        </nav>
    </header>

    <main>

    <!-- Secção inicial de apresentação da aplicação MedControl -->
    <section id="inicio">
        <div class="inicio-conteudo">
            <h2><?= conteudo_publico($conteudos, 'inicio_titulo') ?></h2>
            <p><?= conteudo_publico($conteudos, 'inicio_texto') ?></p>
            <a href="#servicos" class="botao-principal"><?= conteudo_publico($conteudos, 'inicio_botao') ?></a>
        </div>
    </section>

    <!-- Secção Sobre Nós com enquadramento e vantagens da solução -->
    <section id="sobre-nos">
        <div class="sobre-layout">

            <div class="sobre-texto">
                <span class="subtitulo"><?= conteudo_publico($conteudos, 'sobre_subtitulo') ?></span>
                <h2><?= conteudo_publico($conteudos, 'sobre_titulo') ?></h2>

                <p><?= conteudo_publico($conteudos, 'sobre_texto_1') ?></p>
                <p><?= conteudo_publico($conteudos, 'sobre_texto_2') ?></p>
            </div>

            <div class="sobre-lista">
                <h3><?= conteudo_publico($conteudos, 'sobre_lista_titulo') ?></h3>

                <ul>
                    <li><?= conteudo_publico($conteudos, 'sobre_lista_item_1') ?></li>
                    <li><?= conteudo_publico($conteudos, 'sobre_lista_item_2') ?></li>
                    <li><?= conteudo_publico($conteudos, 'sobre_lista_item_3') ?></li>
                    <li><?= conteudo_publico($conteudos, 'sobre_lista_item_4') ?></li>
                </ul>
            </div>

        </div>
    </section>

    <!-- Secção de serviços com os principais módulos funcionais da aplicação -->
    <section id="servicos">
        <h2><?= conteudo_publico($conteudos, 'servicos_titulo') ?></h2>
        <p class="servicos-intro"><?= conteudo_publico($conteudos, 'servicos_texto') ?></p>

        <div class="servicos-container">

            <div class="cartao-servico">
                <i class="bi bi-hospital"></i>
                <h3><?= conteudo_publico($conteudos, 'servico_equipamentos_titulo') ?></h3>
                <p><?= conteudo_publico($conteudos, 'servico_equipamentos_texto') ?></p>
            </div>

            <div class="cartao-servico">
                <i class="bi bi-geo-alt"></i>
                <h3><?= conteudo_publico($conteudos, 'servico_localizacao_titulo') ?></h3>
                <p><?= conteudo_publico($conteudos, 'servico_localizacao_texto') ?></p>
            </div>

            <div class="cartao-servico">
                <i class="bi bi-truck"></i>
                <h3><?= conteudo_publico($conteudos, 'servico_fornecedores_titulo') ?></h3>
                <p><?= conteudo_publico($conteudos, 'servico_fornecedores_texto') ?></p>
            </div>

            <div class="cartao-servico">
                <i class="bi bi-file-earmark-text"></i>
                <h3><?= conteudo_publico($conteudos, 'servico_documentacao_titulo') ?></h3>
                <p><?= conteudo_publico($conteudos, 'servico_documentacao_texto') ?></p>
            </div>

            <div class="cartao-servico">
                <i class="bi bi-shield-check"></i>
                <h3><?= conteudo_publico($conteudos, 'servico_contratos_titulo') ?></h3>
                <p><?= conteudo_publico($conteudos, 'servico_contratos_texto') ?></p>
            </div>

            <div class="cartao-servico">
                <i class="bi bi-search"></i>
                <h3><?= conteudo_publico($conteudos, 'servico_pesquisa_titulo') ?></h3>
                <p><?= conteudo_publico($conteudos, 'servico_pesquisa_texto') ?></p>
            </div>

        </div>
    </section>

    <!-- Secção de contactos com dados institucionais e formulário de mensagem -->
    <section id="contactos">
        <h2><?= conteudo_publico($conteudos, 'contactos_titulo') ?></h2>
        <p class="contactos-intro"><?= conteudo_publico($conteudos, 'contactos_intro') ?></p>

        <div class="contactos-container">

            <div class="contactos-info">
                <h3><?= conteudo_publico($conteudos, 'contactos_info_titulo') ?></h3>

                <p><i class="bi bi-envelope"></i> <span><?= conteudo_publico($conteudos, 'contactos_email') ?></span></p>
                <p><i class="bi bi-telephone"></i> <span><?= conteudo_publico($conteudos, 'contactos_telefone') ?></span></p>
                <p><i class="bi bi-geo-alt"></i> <span><?= conteudo_publico($conteudos, 'contactos_localizacao') ?></span></p>
            </div>

            <!-- Formulário público para envio de mensagens para o backoffice -->
            <form class="contactos-formulario" method="post" action="#contactos">
                <input type="hidden" name="formulario_contacto" value="1">

                <!-- Mensagem de confirmação apresentada após envio correto do formulário -->
                <?php if (!empty($mensagem_contacto_sucesso)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= h_conteudo($mensagem_contacto_sucesso) ?>
                    </div>
                <?php endif; ?>

                <!-- Mensagem de erro apresentada quando a validação ou gravação falha -->
                <?php if (!empty($mensagem_contacto_erro)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= h_conteudo($mensagem_contacto_erro) ?>
                    </div>
                <?php endif; ?>

                <label for="nome"><?= conteudo_publico($conteudos, 'contactos_label_nome') ?></label>
                <input type="text" id="nome" name="nome" placeholder="O seu nome" value="<?= h_conteudo($nome_contacto) ?>">

                <label for="email"><?= conteudo_publico($conteudos, 'contactos_label_email') ?></label>
                <input type="email" id="email" name="email" placeholder="O seu email" value="<?= h_conteudo($email_contacto) ?>">

                <label for="mensagem"><?= conteudo_publico($conteudos, 'contactos_label_mensagem') ?></label>
                <textarea id="mensagem" name="mensagem" rows="5" placeholder="Escreva a sua mensagem"><?= h_conteudo($texto_contacto) ?></textarea>

                <button type="submit"><?= conteudo_publico($conteudos, 'contactos_botao') ?></button>
            </form>

        </div>
    </section>

    </main>

    <!-- Rodapé com textos configuráveis da área pública -->
    <footer>
        <p><?= conteudo_publico($conteudos, 'footer_texto_1') ?></p>
        <p><?= conteudo_publico($conteudos, 'footer_texto_2') ?></p>
    </footer>

    <!-- Scripts necessários para os componentes Bootstrap e comportamentos personalizados -->
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/1240661.js"></script>

</body>
</html>
