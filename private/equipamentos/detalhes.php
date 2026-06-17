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
                <p>Consulta completa da ficha técnica e administrativa do equipamento médico.</p>
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
                        Fornecedor
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
                                <span>Estado atual</span>
                                <strong><span class="estado ativo">Ativo</span></strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Dados técnicos</h3>

                            <div class="detalhe-linha">
                                <span>Marca</span>
                                <strong>Philips</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Modelo</span>
                                <strong>IntelliVue MP5</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Número de série</span>
                                <strong>MP5-2022-45873</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Fabricante</span>
                                <strong>Philips Healthcare</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Aquisição</h3>

                            <div class="detalhe-linha">
                                <span>Data de aquisição</span>
                                <strong>12/02/2022</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Ano de fabrico</span>
                                <strong>2022</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Custo de aquisição</span>
                                <strong>4 850 €</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Tipo de entrada</span>
                                <strong>Compra</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Criticidade</h3>

                            <div class="detalhe-linha">
                                <span>Nível de criticidade</span>
                                <strong><span class="estado suporte">Suporte de vida</span></strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Prioridade de manutenção</span>
                                <strong>Elevada</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Utilização clínica</span>
                                <strong>Monitorização contínua</strong>
                            </div>
                        </div>
                    </div>

                    <div class="detalhes-grid mt-4">
                        <div class="detalhe-card">
                            <h3>Relações e consumíveis</h3>

                            <div class="detalhe-linha">
                                <span>É componente de outro equipamento</span>
                                <strong>Não</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Equipamento principal</span>
                                <strong>Não aplicável</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Tem consumíveis</span>
                                <strong>Sim</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Consumíveis utilizados</span>
                                <strong>Sensores SpO₂, cabos ECG e bateria</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-localizacao" role="tabpanel" tabindex="0">
                    <h3>Localização</h3>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Local atual</h3>

                            <div class="detalhe-linha">
                                <span>Serviço / Localização</span>
                                <strong>UCI</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Edifício</span>
                                <strong>Edifício Principal</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Piso</span>
                                <strong>2</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Sala / Unidade</span>
                                <strong>UCI Geral</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Responsável</h3>

                            <div class="detalhe-linha">
                                <span>Responsável da localização</span>
                                <strong>Enfermeiro responsável</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Contacto interno</span>
                                <strong>2201</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Estado da localização</span>
                                <strong><span class="estado ativo">Ativa</span></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-fornecedor" role="tabpanel" tabindex="0">
                    <h3>Fornecedores associados</h3>
                    <p>Fornecedores relacionados com este equipamento, de acordo com o tipo de relação.</p>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Fornecedor associado 1</h3>

                            <div class="detalhe-linha">
                                <span>Fornecedor</span>
                                <strong>Philips Healthcare</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Tipo de relação</span>
                                <strong>Fabricante</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Pessoa de contacto</span>
                                <strong>Ana Silva</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Telefone</span>
                                <strong>+351 222 100 100</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Telefone da pessoa de contacto</span>
                                <strong>+351 912 000 000</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Email</span>
                                <strong>geral@philips-health.pt</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>NIF</span>
                                <strong>501234567</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Website</span>
                                <strong>www.philips-health.pt</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Morada</span>
                                <strong>Rua Dr. António Bernardino de Almeida, 431, 4200-072 Porto</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Fornecedor associado 2</h3>

                            <div class="detalhe-linha">
                                <span>Fornecedor</span>
                                <strong>MedTech Portugal</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Tipo de relação</span>
                                <strong>Distribuidor</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Pessoa de contacto</span>
                                <strong>João Martins</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Telefone</span>
                                <strong>+351 222 111 111</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Telefone da pessoa de contacto</span>
                                <strong>+351 913 000 000</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Email</span>
                                <strong>apoio@medtech.pt</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>NIF</span>
                                <strong>509876543</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Website</span>
                                <strong>www.medtech.pt</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Morada</span>
                                <strong>Avenida da Boavista, 1200, 4100-130 Porto</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detalhe-card h-100">
                            <h3>Fornecedores adicionais</h3>

                            <div class="detalhe-linha">
                                <span>Outros fornecedores associados</span>
                                <strong>
                                    Fornecedor 3 - Assistência Técnica Norte, manutenção corretiva, contacto: Carla Rocha, telefone: +351 914 000 000, email: assistencia@tecnica-norte.pt.
                                    Fornecedor 4 - BioMed Supplies, fornecimento de acessórios e peças, contacto: Rui Almeida, telefone: +351 915 000 000, email: geral@biomedsupplies.pt.
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-documentacao" role="tabpanel" tabindex="0">
                    <h3>Documentação</h3>
                    <p>Documentos registados para este equipamento, com indicação de tipo, nome, datas, validade, fornecedor associado e ficheiro.</p>

                    <div class="table-responsive">
                        <table class="tabela-backend">
                            <thead>
                                <tr>
                                    <th>Tipo de documento</th>
                                    <th>Nome do documento</th>
                                    <th>Data do documento</th>
                                    <th>Validade</th>
                                    <th>Fornecedor associado</th>
                                    <th>Ficheiro</th>
                                    
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Manual de utilizador</td>
                                    <td>Manual de utilizador Philips IntelliVue MP5</td>
                                    <td>12/02/2022</td>
                                    <td>Não aplicável</td>
                                    <td>Philips Healthcare</td>
                                    <td>manual_monitor.pdf</td>
                                    
                                </tr>

                                <tr>
                                    <td>Manual de serviço</td>
                                    <td>Manual de serviço Philips IntelliVue MP5</td>
                                    <td>12/02/2022</td>
                                    <td>Não aplicável</td>
                                    <td>Philips Healthcare</td>
                                    <td>manual_servico_monitor.pdf</td>
                                    
                                </tr>

                                <tr>
                                    <td>Certificado de calibração</td>
                                    <td>Certificado de calibração 2026</td>
                                    <td>15/01/2026</td>
                                    <td>15/01/2027</td>
                                    <td>MedTech Portugal</td>
                                    <td>certificado_monitor.pdf</td>
                                    
                                </tr>

                                <tr>
                                    <td>Declaração de conformidade</td>
                                    <td>Declaração de conformidade CE</td>
                                    <td>12/02/2022</td>
                                    <td>Não aplicável</td>
                                    <td>Philips Healthcare</td>
                                    <td>declaracao_conformidade_monitor.pdf</td>
                                    
                                </tr>

                                <tr>
                                    <td>Relatório técnico</td>
                                    <td>Relatório técnico de instalação</td>
                                    <td>14/02/2022</td>
                                    <td>Não aplicável</td>
                                    <td>MedTech Portugal</td>
                                    <td>relatorio_monitor.pdf</td>
                                    
                                </tr>

                                <tr>
                                    <td>Fatura ou guia de aquisição</td>
                                    <td>Fatura de aquisição do equipamento</td>
                                    <td>12/02/2022</td>
                                    <td>Não aplicável</td>
                                    <td>MedTech Portugal</td>
                                    <td>fatura_aquisicao_monitor.pdf</td>
                                    
                                </tr>

                                <tr>
                                    <td>Contrato / garantia</td>
                                    <td>Contrato de garantia e manutenção</td>
                                    <td>12/02/2022</td>
                                    <td>12/02/2027</td>
                                    <td>Philips Healthcare</td>
                                    <td>contrato_monitor.pdf</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="detalhe-card mt-4">
                        <h3>Observações</h3>

                        <p>
                            Equipamento utilizado para monitorização contínua de sinais vitais em ambiente hospitalar.
                            A documentação associada deve ser mantida atualizada para facilitar auditorias,
                            manutenções e consultas técnicas.
                        </p>
                    </div>
                </div>

                <div class="tab-pane fade" id="detalhes-contratos" role="tabpanel" tabindex="0">
                    <h3>Contratos</h3>

                    <div class="detalhes-grid">
                        <div class="detalhe-card">
                            <h3>Garantia</h3>

                            <div class="detalhe-linha">
                                <span>Estado do contrato / garantia</span>
                                <strong><span class="estado ativo">Ativo</span></strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Data de início</span>
                                <strong>12/02/2022</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Data de fim</span>
                                <strong>12/02/2027</strong>
                            </div>
                        </div>

                        <div class="detalhe-card">
                            <h3>Contrato associado</h3>

                            <div class="detalhe-linha">
                                <span>Tipo de contrato</span>
                                <strong>Garantia</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Periodicidade de manutenção</span>
                                <strong>Anual</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Valor associado</span>
                                <strong>1 200 €</strong>
                            </div>

                            
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>