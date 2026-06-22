<?php
require_once __DIR__ . '/../includes/conteudos_publicos.php';

start_session();
redirect_if_not_logged();

$pagina_atual = 'conteudos';

$mensagem_sucesso = '';
$erros = [];

$conteudos = obter_conteudos_publicos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [];

    foreach (conteudos_publicos_default() as $chave => $valor_default) {
        $dados[$chave] = trim((string) ($_POST[$chave] ?? ''));

        if ($dados[$chave] === '') {
            $erros[] = 'Preencha todos os campos obrigatórios.';
            break;
        }
    }

    if (!empty($dados['contactos_email']) && !filter_var($dados['contactos_email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'O email de contacto não é válido.';
    }

    if (empty($erros)) {
        if (guardar_conteudos_publicos($dados)) {
            $mensagem_sucesso = 'Conteúdos guardados com sucesso.';
            $conteudos = obter_conteudos_publicos();
        } else {
            $erros[] = 'Não foi possível guardar os conteúdos na base de dados.';
            $conteudos = array_merge($conteudos, $dados);
        }
    } else {
        $conteudos = array_merge($conteudos, $dados);
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Gestão de Conteúdos</h1>
                <p>Atualização dos textos e informações apresentados na área pública do site.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/public/index.php">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Sair para o site público
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/public/logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <section class="backend-box">
            <div class="backend-section-header">
                <div>
                    <h2>Conteúdos da Área Pública</h2>
                    <p>Edite os textos principais sem alterar diretamente o HTML do site público.</p>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>/private/index.php" class="btn-secundario">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à Dashboard
                    </a>

                    <a href="<?= BASE_URL ?>/public/index.php" class="btn-backend">
                        <i class="bi bi-eye"></i>
                        Ver site público
                    </a>
                </div>
            </div>

            <?php if (!empty($mensagem_sucesso)) : ?>
                <div class="alert alert-success" role="alert">
                    <?= h_conteudo($mensagem_sucesso) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($erros)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($erros as $erro) : ?>
                        <div><?= h_conteudo($erro) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="form-backend" method="post" action="index.php">

                <h3>Menu e cabeçalho</h3>
                <div class="form-grid">
                <div>
                    <label for="logo_texto">Texto do logótipo *</label>
                    <input type="text" id="logo_texto" name="logo_texto" value="<?= conteudo_publico($conteudos, 'logo_texto') ?>">
                </div>
                <div>
                    <label for="nav_inicio">Menu - Início *</label>
                    <input type="text" id="nav_inicio" name="nav_inicio" value="<?= conteudo_publico($conteudos, 'nav_inicio') ?>">
                </div>
                <div>
                    <label for="nav_sobre">Menu - Sobre Nós *</label>
                    <input type="text" id="nav_sobre" name="nav_sobre" value="<?= conteudo_publico($conteudos, 'nav_sobre') ?>">
                </div>
                <div>
                    <label for="nav_servicos">Menu - Serviços *</label>
                    <input type="text" id="nav_servicos" name="nav_servicos" value="<?= conteudo_publico($conteudos, 'nav_servicos') ?>">
                </div>
                <div>
                    <label for="nav_contactos">Menu - Contactos *</label>
                    <input type="text" id="nav_contactos" name="nav_contactos" value="<?= conteudo_publico($conteudos, 'nav_contactos') ?>">
                </div>
                <div>
                    <label for="nav_area_reservada">Menu - Área Reservada *</label>
                    <input type="text" id="nav_area_reservada" name="nav_area_reservada" value="<?= conteudo_publico($conteudos, 'nav_area_reservada') ?>">
                </div>
                </div>

                <h3 class="mt-4">Página inicial</h3>
                <div class="form-full">
                    <label for="inicio_titulo">Título da página inicial *</label>
                    <input type="text" id="inicio_titulo" name="inicio_titulo" value="<?= conteudo_publico($conteudos, 'inicio_titulo') ?>">
                </div>
                <div class="form-full">
                    <label for="inicio_texto">Texto da página inicial *</label>
                    <textarea id="inicio_texto" name="inicio_texto" rows="3"><?= conteudo_publico($conteudos, 'inicio_texto') ?></textarea>
                </div>
                <div class="form-full">
                    <label for="inicio_botao">Texto do botão principal *</label>
                    <input type="text" id="inicio_botao" name="inicio_botao" value="<?= conteudo_publico($conteudos, 'inicio_botao') ?>">
                </div>

                <h3 class="mt-4">Sobre Nós</h3>
                <div class="form-full">
                    <label for="sobre_subtitulo">Subtítulo da secção Sobre Nós *</label>
                    <input type="text" id="sobre_subtitulo" name="sobre_subtitulo" value="<?= conteudo_publico($conteudos, 'sobre_subtitulo') ?>">
                </div>
                <div class="form-full">
                    <label for="sobre_titulo">Título da secção Sobre Nós *</label>
                    <input type="text" id="sobre_titulo" name="sobre_titulo" value="<?= conteudo_publico($conteudos, 'sobre_titulo') ?>">
                </div>
                <div class="form-full">
                    <label for="sobre_texto_1">Primeiro texto da secção Sobre Nós *</label>
                    <textarea id="sobre_texto_1" name="sobre_texto_1" rows="3"><?= conteudo_publico($conteudos, 'sobre_texto_1') ?></textarea>
                </div>
                <div class="form-full">
                    <label for="sobre_texto_2">Segundo texto da secção Sobre Nós *</label>
                    <textarea id="sobre_texto_2" name="sobre_texto_2" rows="3"><?= conteudo_publico($conteudos, 'sobre_texto_2') ?></textarea>
                </div>
                <div class="form-full">
                    <label for="sobre_lista_titulo">Título da lista Sobre Nós *</label>
                    <input type="text" id="sobre_lista_titulo" name="sobre_lista_titulo" value="<?= conteudo_publico($conteudos, 'sobre_lista_titulo') ?>">
                </div>
                <div class="form-grid">
                <div>
                    <label for="sobre_lista_item_1">Ponto 1 *</label>
                    <input type="text" id="sobre_lista_item_1" name="sobre_lista_item_1" value="<?= conteudo_publico($conteudos, 'sobre_lista_item_1') ?>">
                </div>
                <div>
                    <label for="sobre_lista_item_2">Ponto 2 *</label>
                    <input type="text" id="sobre_lista_item_2" name="sobre_lista_item_2" value="<?= conteudo_publico($conteudos, 'sobre_lista_item_2') ?>">
                </div>
                <div>
                    <label for="sobre_lista_item_3">Ponto 3 *</label>
                    <input type="text" id="sobre_lista_item_3" name="sobre_lista_item_3" value="<?= conteudo_publico($conteudos, 'sobre_lista_item_3') ?>">
                </div>
                <div>
                    <label for="sobre_lista_item_4">Ponto 4 *</label>
                    <input type="text" id="sobre_lista_item_4" name="sobre_lista_item_4" value="<?= conteudo_publico($conteudos, 'sobre_lista_item_4') ?>">
                </div>
                </div>

                <h3 class="mt-4">Serviços</h3>
                <div class="form-full">
                    <label for="servicos_titulo">Título da secção Serviços *</label>
                    <input type="text" id="servicos_titulo" name="servicos_titulo" value="<?= conteudo_publico($conteudos, 'servicos_titulo') ?>">
                </div>
                <div class="form-full">
                    <label for="servicos_texto">Texto introdutório dos serviços *</label>
                    <textarea id="servicos_texto" name="servicos_texto" rows="3"><?= conteudo_publico($conteudos, 'servicos_texto') ?></textarea>
                </div>
                <div class="form-grid">
                <div>
                    <label for="servico_equipamentos_titulo">Serviço Equipamentos - Título *</label>
                    <input type="text" id="servico_equipamentos_titulo" name="servico_equipamentos_titulo" value="<?= conteudo_publico($conteudos, 'servico_equipamentos_titulo') ?>">
                </div>
                <div>
                    <label for="servico_equipamentos_texto">Serviço Equipamentos - Texto *</label>
                    <textarea id="servico_equipamentos_texto" name="servico_equipamentos_texto" rows="3"><?= conteudo_publico($conteudos, 'servico_equipamentos_texto') ?></textarea>
                </div>
                <div>
                    <label for="servico_localizacao_titulo">Serviço Localizações - Título *</label>
                    <input type="text" id="servico_localizacao_titulo" name="servico_localizacao_titulo" value="<?= conteudo_publico($conteudos, 'servico_localizacao_titulo') ?>">
                </div>
                <div>
                    <label for="servico_localizacao_texto">Serviço Localizações - Texto *</label>
                    <textarea id="servico_localizacao_texto" name="servico_localizacao_texto" rows="3"><?= conteudo_publico($conteudos, 'servico_localizacao_texto') ?></textarea>
                </div>
                <div>
                    <label for="servico_fornecedores_titulo">Serviço Fornecedores - Título *</label>
                    <input type="text" id="servico_fornecedores_titulo" name="servico_fornecedores_titulo" value="<?= conteudo_publico($conteudos, 'servico_fornecedores_titulo') ?>">
                </div>
                <div>
                    <label for="servico_fornecedores_texto">Serviço Fornecedores - Texto *</label>
                    <textarea id="servico_fornecedores_texto" name="servico_fornecedores_texto" rows="3"><?= conteudo_publico($conteudos, 'servico_fornecedores_texto') ?></textarea>
                </div>
                <div>
                    <label for="servico_documentacao_titulo">Serviço Documentação - Título *</label>
                    <input type="text" id="servico_documentacao_titulo" name="servico_documentacao_titulo" value="<?= conteudo_publico($conteudos, 'servico_documentacao_titulo') ?>">
                </div>
                <div>
                    <label for="servico_documentacao_texto">Serviço Documentação - Texto *</label>
                    <textarea id="servico_documentacao_texto" name="servico_documentacao_texto" rows="3"><?= conteudo_publico($conteudos, 'servico_documentacao_texto') ?></textarea>
                </div>
                <div>
                    <label for="servico_contratos_titulo">Serviço Contratos - Título *</label>
                    <input type="text" id="servico_contratos_titulo" name="servico_contratos_titulo" value="<?= conteudo_publico($conteudos, 'servico_contratos_titulo') ?>">
                </div>
                <div>
                    <label for="servico_contratos_texto">Serviço Contratos - Texto *</label>
                    <textarea id="servico_contratos_texto" name="servico_contratos_texto" rows="3"><?= conteudo_publico($conteudos, 'servico_contratos_texto') ?></textarea>
                </div>
                <div>
                    <label for="servico_pesquisa_titulo">Serviço Pesquisa - Título *</label>
                    <input type="text" id="servico_pesquisa_titulo" name="servico_pesquisa_titulo" value="<?= conteudo_publico($conteudos, 'servico_pesquisa_titulo') ?>">
                </div>
                <div>
                    <label for="servico_pesquisa_texto">Serviço Pesquisa - Texto *</label>
                    <textarea id="servico_pesquisa_texto" name="servico_pesquisa_texto" rows="3"><?= conteudo_publico($conteudos, 'servico_pesquisa_texto') ?></textarea>
                </div>
                </div>

                <h3 class="mt-4">Contactos</h3>
                <div class="form-full">
                    <label for="contactos_titulo">Título da secção Contactos *</label>
                    <input type="text" id="contactos_titulo" name="contactos_titulo" value="<?= conteudo_publico($conteudos, 'contactos_titulo') ?>">
                </div>
                <div class="form-full">
                    <label for="contactos_intro">Texto introdutório dos Contactos *</label>
                    <textarea id="contactos_intro" name="contactos_intro" rows="3"><?= conteudo_publico($conteudos, 'contactos_intro') ?></textarea>
                </div>
                <div class="form-grid">
                <div>
                    <label for="contactos_info_titulo">Título das informações *</label>
                    <input type="text" id="contactos_info_titulo" name="contactos_info_titulo" value="<?= conteudo_publico($conteudos, 'contactos_info_titulo') ?>">
                </div>
                <div>
                    <label for="contactos_email">Email de contacto *</label>
                    <input type="email" id="contactos_email" name="contactos_email" value="<?= conteudo_publico($conteudos, 'contactos_email') ?>">
                </div>
                <div>
                    <label for="contactos_telefone">Telefone *</label>
                    <input type="text" id="contactos_telefone" name="contactos_telefone" value="<?= conteudo_publico($conteudos, 'contactos_telefone') ?>">
                </div>
                <div>
                    <label for="contactos_localizacao">Localização *</label>
                    <input type="text" id="contactos_localizacao" name="contactos_localizacao" value="<?= conteudo_publico($conteudos, 'contactos_localizacao') ?>">
                </div>
                <div>
                    <label for="contactos_label_nome">Formulário - Label Nome *</label>
                    <input type="text" id="contactos_label_nome" name="contactos_label_nome" value="<?= conteudo_publico($conteudos, 'contactos_label_nome') ?>">
                </div>
                <div>
                    <label for="contactos_label_email">Formulário - Label Email *</label>
                    <input type="text" id="contactos_label_email" name="contactos_label_email" value="<?= conteudo_publico($conteudos, 'contactos_label_email') ?>">
                </div>
                <div>
                    <label for="contactos_label_mensagem">Formulário - Label Mensagem *</label>
                    <input type="text" id="contactos_label_mensagem" name="contactos_label_mensagem" value="<?= conteudo_publico($conteudos, 'contactos_label_mensagem') ?>">
                </div>
                <div>
                    <label for="contactos_botao">Formulário - Botão *</label>
                    <input type="text" id="contactos_botao" name="contactos_botao" value="<?= conteudo_publico($conteudos, 'contactos_botao') ?>">
                </div>
                </div>

                <h3 class="mt-4">Rodapé</h3>
                <div class="form-full">
                    <label for="footer_texto_1">Primeiro texto do rodapé *</label>
                    <input type="text" id="footer_texto_1" name="footer_texto_1" value="<?= conteudo_publico($conteudos, 'footer_texto_1') ?>">
                </div>
                <div class="form-full">
                    <label for="footer_texto_2">Segundo texto do rodapé *</label>
                    <input type="text" id="footer_texto_2" name="footer_texto_2" value="<?= conteudo_publico($conteudos, 'footer_texto_2') ?>">
                </div>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar conteúdos
                    </button>

                    <a href="<?= BASE_URL ?>/public/index.php" class="btn-secundario">
                        Ver site público
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>
