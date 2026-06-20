<?php
$pagina_atual = 'equipamentos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Equipamento</h1>
                <p>Consulta visual da ficha técnica e administrativa do equipamento médico.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/index.php">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Sair para o site público
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php">
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
                    <h2>Monitor Multiparamétrico</h2>
                    <p>Código interno: EQ001</p>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="editar.php" class="btn-backend">
                        <i class="bi bi-pencil-square"></i>
                        Editar equipamento
                    </a>

                    <a href="index.php" class="btn-secundario">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>
            </div>

            <ul class="nav nav-tabs mb-4 tabs-equipamento" id="tabsDetalhesEquipamento" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="detalhes-info-tab" data-bs-toggle="tab" data-bs-target="#detalhes-info" type="button" role="tab">
                        <i class="bi bi-info-circle"></i>
                        Informação geral
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detalhes-localizacao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-localizacao" type="button" role="tab">
                        <i class="bi bi-geo-alt"></i>
                        Localização
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detalhes-fornecedor-tab" data-bs-toggle="tab" data-bs-target="#detalhes-fornecedor" type="button" role="tab">
                        <i class="bi bi-truck"></i>
                        Fornecedores
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detalhes-documentacao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-documentacao" type="button" role="tab">
                        <i class="bi bi-folder"></i>
                        Documentação
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detalhes-contratos-tab" data-bs-toggle="tab" data-bs-target="#detalhes-contratos" type="button" role="tab">
                        <i class="bi bi-shield-check"></i>
                        Contratos
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="tabsDetalhesEquipamentoConteudo">

                <div class="tab-pane fade show active" id="detalhes-info" role="tabpanel" tabindex="0">
                    <h3>Informação geral</h3>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Identificação</h3>

                            <div class="detalhe-linha">
                                <span>Código interno</span>
                                <strong>EQ001</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Designação</span>
                                <strong>Monitor Multiparamétrico</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Categoria</span>
                                <strong>Monitorização</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Estado</span>
                                <strong><span class="estado ativo">Ativo</span></strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Características técnicas</h3>

                            <div class="detalhe-linha">
                                <span>Marca</span>
                                <strong>Philips</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Modelo</span>
                                <strong>IntelliVue MX450</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Número de série</span>
                                <strong>SN-2026-001</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Inventário</span>
                                <strong>INV-001</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Datas e garantia</h3>

                            <div class="detalhe-linha">
                                <span>Data de aquisição</span>
                                <strong>10/01/2026</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Data de instalação</span>
                                <strong>15/01/2026</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Fim da garantia</span>
                                <strong>15/01/2028</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-localizacao" role="tabpanel" tabindex="0">
                    <h3>Localização do equipamento</h3>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Localização geral</h3>

                            <div class="detalhe-linha">
                                <span>Código da localização</span>
                                <strong>LOC001</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Edifício</span>
                                <strong>Edifício Central</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Número de pisos</span>
                                <strong>8</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Localização específica</h3>

                            <div class="detalhe-linha">
                                <span>Piso</span>
                                <strong>2</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Serviço</span>
                                <strong>Urgência</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Sala</span>
                                <strong>Sala 204</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-fornecedor" role="tabpanel" tabindex="0">
                    <h3>Fornecedores associados</h3>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Fornecedor principal</h3>

                            <div class="detalhe-linha">
                                <span>Código</span>
                                <strong>FOR001</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Fornecedor</span>
                                <strong>MedTech Portugal</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Tipo de relação</span>
                                <strong>Assistência técnica</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Pessoa de contacto</span>
                                <strong>Ana Martins</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Telefone contacto</span>
                                <strong>+351 912 345 678</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Email contacto</span>
                                <strong>ana.martins@medtech.pt</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-documentacao" role="tabpanel" tabindex="0">
                    <h3>Documentação associada</h3>

                    <div class="tabela-responsive">
                        <table class="tabela-backend">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Documento</th>
                                    <th>Fornecedor associado</th>
                                    <th>Validade</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Manual de utilizador</td>
                                    <td>manual_utilizador.pdf</td>
                                    <td>Sem fornecedor associado</td>
                                    <td>Sem validade</td>
                                    <td><span class="estado ativo">Ativo</span></td>
                                </tr>

                                <tr>
                                    <td>Certificado</td>
                                    <td>certificado_conformidade.pdf</td>
                                    <td>FOR001 - MedTech Portugal</td>
                                    <td>15/01/2028</td>
                                    <td><span class="estado ativo">Ativo</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-contratos" role="tabpanel" tabindex="0">
                    <h3>Contratos associados</h3>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Contrato de manutenção</h3>

                            <div class="detalhe-linha">
                                <span>Código</span>
                                <strong>CON001</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Fornecedor</span>
                                <strong>MedTech Portugal</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Tipo</span>
                                <strong>Manutenção preventiva</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Validade</span>
                                <strong>31/12/2026</strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
