<?php
$pagina_atual = 'conteudos';
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

                <div class="backend-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </div>
            </div>

            <section class="backend-box">
                <div class="backend-section-header">
                    <div>
                        <h2>Conteúdos da Área Pública</h2>
                        <p>Edite os textos principais sem alterar diretamente o HTML do site público.</p>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?php echo BASE_URL; ?>/private/index.php" class="btn-secundario">
                            <i class="bi bi-arrow-left-circle"></i>
                            Voltar à Dashboard
                        </a>
                    
                        <a href="<?php echo BASE_URL; ?>/public/index.php" class="btn-backend">
                            <i class="bi bi-eye"></i>
                            Ver site público
                        </a>
                    </div>
                </div>

            <form class="form-backend" id="formConteudosPublicos">

                <h3>Menu e cabeçalho</h3>

                <div class="form-grid">
                    <div>
                        <label for="conteudoLogoTexto">Texto do logótipo *</label>
                        <input type="text" id="conteudoLogoTexto" value="MedControl">
                    </div>

                    <div>
                        <label for="conteudoNavInicio">Menu - Início *</label>
                        <input type="text" id="conteudoNavInicio" value="Início">
                    </div>

                    <div>
                        <label for="conteudoNavSobre">Menu - Sobre Nós *</label>
                        <input type="text" id="conteudoNavSobre" value="Sobre Nós">
                    </div>

                    <div>
                        <label for="conteudoNavServicos">Menu - Serviços *</label>
                        <input type="text" id="conteudoNavServicos" value="Serviços">
                    </div>

                    <div>
                        <label for="conteudoNavContactos">Menu - Contactos *</label>
                        <input type="text" id="conteudoNavContactos" value="Contactos">
                    </div>

                    <div>
                        <label for="conteudoNavAreaReservada">Menu - Área Reservada *</label>
                        <input type="text" id="conteudoNavAreaReservada" value="Área Reservada">
                    </div>
                </div>

                <h3 class="mt-4">Página inicial</h3>

                <div class="form-full">
                    <label for="conteudoTituloInicio">Título da página inicial *</label>
                    <input type="text" id="conteudoTituloInicio" value="MedControl">
                </div>

                <div class="form-full">
                    <label for="conteudoTextoInicio">Texto da página inicial *</label>
                    <textarea id="conteudoTextoInicio" rows="3">Sistema web para gestão de inventário hospitalar de equipamentos médicos, documentação técnica, fornecedores, garantias e contratos.</textarea>
                </div>

                <div class="form-full">
                    <label for="conteudoBotaoInicio">Texto do botão principal *</label>
                    <input type="text" id="conteudoBotaoInicio" value="Conhecer serviços">
                </div>

                <h3 class="mt-4">Sobre Nós</h3>

                <div class="form-full">
                    <label for="conteudoSobreSubtitulo">Subtítulo da secção Sobre Nós *</label>
                    <input type="text" id="conteudoSobreSubtitulo" value="Sobre a MedControl">
                </div>

                <div class="form-full">
                    <label for="conteudoSobreTitulo">Título da secção Sobre Nós *</label>
                    <input type="text" id="conteudoSobreTitulo" value="Uma solução criada para simplificar a gestão hospitalar">
                </div>

                <div class="form-full">
                    <label for="conteudoSobreNos">Primeiro texto da secção Sobre Nós *</label>
                    <textarea id="conteudoSobreNos" rows="3">A MedControl é uma aplicação web desenvolvida para apoiar instituições de saúde na organização do inventário hospitalar de equipamentos médicos.</textarea>
                </div>

                <div class="form-full">
                    <label for="conteudoSobreTexto2">Segundo texto da secção Sobre Nós *</label>
                    <textarea id="conteudoSobreTexto2" rows="3">A plataforma permite centralizar informação sobre equipamentos, fornecedores, documentação técnica, garantias e contratos, tornando a consulta mais rápida, simples e organizada.</textarea>
                </div>

                <div class="form-full">
                    <label for="conteudoSobreListaTitulo">Título da lista Sobre Nós *</label>
                    <input type="text" id="conteudoSobreListaTitulo" value="O que torna a MedControl útil?">
                </div>

                <div class="form-grid">
                    <div>
                        <label for="conteudoSobreListaItem1">Ponto 1 *</label>
                        <input type="text" id="conteudoSobreListaItem1" value="Reduz a dependência de folhas de cálculo dispersas.">
                    </div>

                    <div>
                        <label for="conteudoSobreListaItem2">Ponto 2 *</label>
                        <input type="text" id="conteudoSobreListaItem2" value="Facilita a localização e consulta de equipamentos.">
                    </div>

                    <div>
                        <label for="conteudoSobreListaItem3">Ponto 3 *</label>
                        <input type="text" id="conteudoSobreListaItem3" value="Centraliza documentação técnica e administrativa.">
                    </div>

                    <div>
                        <label for="conteudoSobreListaItem4">Ponto 4 *</label>
                        <input type="text" id="conteudoSobreListaItem4" value="Apoia a rastreabilidade e a gestão do ciclo de vida dos equipamentos.">
                    </div>
                </div>

                <h3 class="mt-4">Serviços</h3>

                <div class="form-full">
                    <label for="conteudoServicosTitulo">Título da secção Serviços *</label>
                    <input type="text" id="conteudoServicosTitulo" value="Serviços">
                </div>

                <div class="form-full">
                    <label for="conteudoServicos">Texto introdutório dos serviços *</label>
                    <textarea id="conteudoServicos" rows="3">A MedControl disponibiliza ferramentas para apoiar a organização, consulta e controlo do inventário hospitalar.</textarea>
                </div>

                <div class="form-grid">
                    <div>
                        <label for="conteudoServicoEquipamentosTitulo">Serviço Equipamentos - Título *</label>
                        <input type="text" id="conteudoServicoEquipamentosTitulo" value="Gestão de Equipamentos">
                    </div>

                    <div>
                        <label for="conteudoServicoEquipamentosTexto">Serviço Equipamentos - Texto *</label>
                        <textarea id="conteudoServicoEquipamentosTexto" rows="2">Registo, consulta e atualização dos equipamentos médicos existentes no hospital.</textarea>
                    </div>

                    <div>
                        <label for="conteudoServicoLocalizacaoTitulo">Serviço Localizações - Título *</label>
                        <input type="text" id="conteudoServicoLocalizacaoTitulo" value="Gestão de Localizações">
                    </div>

                    <div>
                        <label for="conteudoServicoLocalizacaoTexto">Serviço Localizações - Texto *</label>
                        <textarea id="conteudoServicoLocalizacaoTexto" rows="2">Organização dos equipamentos por serviço, piso, sala ou unidade hospitalar.</textarea>
                    </div>

                    <div>
                        <label for="conteudoServicoFornecedoresTitulo">Serviço Fornecedores - Título *</label>
                        <input type="text" id="conteudoServicoFornecedoresTitulo" value="Gestão de Fornecedores">
                    </div>

                    <div>
                        <label for="conteudoServicoFornecedoresTexto">Serviço Fornecedores - Texto *</label>
                        <textarea id="conteudoServicoFornecedoresTexto" rows="2">Registo de fornecedores, fabricantes e entidades responsáveis pela assistência técnica.</textarea>
                    </div>

                    <div>
                        <label for="conteudoServicoDocumentacaoTitulo">Serviço Documentação - Título *</label>
                        <input type="text" id="conteudoServicoDocumentacaoTitulo" value="Gestão Documental">
                    </div>

                    <div>
                        <label for="conteudoServicoDocumentacaoTexto">Serviço Documentação - Texto *</label>
                        <textarea id="conteudoServicoDocumentacaoTexto" rows="2">Associação de manuais, certificados, contratos, faturas e relatórios técnicos.</textarea>
                    </div>

                    <div>
                        <label for="conteudoServicoContratosTitulo">Serviço Contratos - Título *</label>
                        <input type="text" id="conteudoServicoContratosTitulo" value="Garantias e Contratos">
                    </div>

                    <div>
                        <label for="conteudoServicoContratosTexto">Serviço Contratos - Texto *</label>
                        <textarea id="conteudoServicoContratosTexto" rows="2">Consulta de datas de garantia, contratos de manutenção e prazos importantes.</textarea>
                    </div>

                    <div>
                        <label for="conteudoServicoPesquisaTitulo">Serviço Pesquisa - Título *</label>
                        <input type="text" id="conteudoServicoPesquisaTitulo" value="Pesquisa e Filtragem">
                    </div>

                    <div>
                        <label for="conteudoServicoPesquisaTexto">Serviço Pesquisa - Texto *</label>
                        <textarea id="conteudoServicoPesquisaTexto" rows="2">Pesquisa rápida por nome, categoria, estado, localização ou fornecedor.</textarea>
                    </div>
                </div>

                <h3 class="mt-4">Contactos</h3>

                <div class="form-full">
                    <label for="conteudoContactosTitulo">Título da secção Contactos *</label>
                    <input type="text" id="conteudoContactosTitulo" value="Contactos">
                </div>

                <div class="form-full">
                    <label for="conteudoContactosIntro">Texto introdutório dos Contactos *</label>
                    <textarea id="conteudoContactosIntro" rows="2">Entre em contacto com a equipa MedControl para saber mais sobre a solução.</textarea>
                </div>

                <div class="form-grid">
                    <div>
                        <label for="conteudoContactosInfoTitulo">Título das informações *</label>
                        <input type="text" id="conteudoContactosInfoTitulo" value="Informações">
                    </div>

                    <div>
                        <label for="conteudoEmail">Email de contacto *</label>
                        <input type="email" id="conteudoEmail" value="geral@medcontrol.pt">
                    </div>

                    <div>
                        <label for="conteudoTelefone">Telefone *</label>
                        <input type="text" id="conteudoTelefone" value="+351 222 000 000">
                    </div>

                    <div>
                        <label for="conteudoLocalizacao">Localização *</label>
                        <input type="text" id="conteudoLocalizacao" value="Porto, Portugal">
                    </div>

                    <div>
                        <label for="conteudoLabelNome">Formulário - Label Nome *</label>
                        <input type="text" id="conteudoLabelNome" value="Nome">
                    </div>

                    <div>
                        <label for="conteudoLabelEmail">Formulário - Label Email *</label>
                        <input type="text" id="conteudoLabelEmail" value="Email">
                    </div>

                    <div>
                        <label for="conteudoLabelMensagem">Formulário - Label Mensagem *</label>
                        <input type="text" id="conteudoLabelMensagem" value="Mensagem">
                    </div>

                    <div>
                        <label for="conteudoBotaoContacto">Formulário - Botão *</label>
                        <input type="text" id="conteudoBotaoContacto" value="Enviar mensagem">
                    </div>
                </div>

                <h3 class="mt-4">Rodapé</h3>

                <div class="form-full">
                    <label for="conteudoFooterTexto1">Primeiro texto do rodapé *</label>
                    <input type="text" id="conteudoFooterTexto1" value="© 2025 MedControl. Todos os direitos reservados.">
                </div>

                <div class="form-full">
                    <label for="conteudoFooterTexto2">Segundo texto do rodapé *</label>
                    <input type="text" id="conteudoFooterTexto2" value="Sistema web de apoio ao inventário hospitalar de equipamentos médicos.">
                </div>

                <p id="mensagemConteudosPublicos" class="mensagem-login"></p>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar conteúdos
                    </button>

                    <button type="button" class="btn-secundario" id="reporConteudosPublicos">
                        Repor valores iniciais
                    </button>
                </div>

            </form>
            

        </main>

</div>

<?php include '../includes/footer.php'; ?>
