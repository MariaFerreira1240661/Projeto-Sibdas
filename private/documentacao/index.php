<?php
$pagina_atual = 'documentacao';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

<main class="backend-content">

            <div class="backend-topbar">
                <div>
                    <h1>Documentação</h1>
                    <p>Gestão da documentação técnica e administrativa associada aos equipamentos médicos.</p>
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
                        <h2>Listagem de Documentos</h2>
                        <p>Consulta e gestão da documentação técnica e administrativa associada aos equipamentos.</p>
                    </div>

                </div>

                <div class="filtros-backend">
                    <div>
                        <label for="pesquisaDocumento">Pesquisar</label>
                        <input type="text" id="pesquisaDocumento" placeholder="Documento, equipamento, fornecedor ou ficheiro">
                    </div>

                    <div>
                        <label for="filtroTipoDocumento">Tipo</label>
                        <select id="filtroTipoDocumento">
                            <option value="">Todos</option>
                            <option value="manual">Manual</option>
                            <option value="certificado">Certificado</option>
                            <option value="contrato">Contrato</option>
                            <option value="relatorio">Relatório técnico</option>
                            <option value="fatura">Fatura</option>
                        </select>
                    </div>

                    <div>
                        <label for="filtroEstadoDocumento">Estado</label>
                        <select id="filtroEstadoDocumento">
                            <option value="">Todos</option>
                            <option value="valido">Válido</option>
                            <option value="pendente">Pendente</option>
                            <option value="expirado">Expirado</option>
                        </select>
                    </div>

                    <div>
                        <label for="filtroEquipamentoDocumento">Equipamento</label>
                        <select id="filtroEquipamentoDocumento">
                            <option value="">Todos</option>
                            <option value="monitor">Monitor Multiparamétrico</option>
                            <option value="ventilador">Ventilador Pulmonar</option>
                            <option value="bomba">Bomba de Infusão</option>
                            <option value="desfibrilhador">Desfibrilhador</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="tabela-backend" id="tabelaDocumentos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tipo de documento</th>
                                <th>Nome do documento</th>
                                <th>Equipamento</th>
                                <th>Data do documento</th>
                                <th>Validade</th>
                                <th>Fornecedor associado</th>
                                <th>Ficheiro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr data-tipo="manual" data-estado="valido" data-equipamento="monitor">
                                <td>D001</td>
                                <td>Manual de Utilizador</td>
                                <td>Manual de utilizador Philips IntelliVue MP5</td>
                                <td>Monitor Multiparamétrico</td>
                                <td>12/02/2025</td>
                                <td>Sem validade</td>
                                <td>Philips Healthcare</td>
                                <td>manual_monitor.pdf</td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                
                                
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <tr data-tipo="contrato" data-estado="pendente" data-equipamento="ventilador">
                                <td>D002</td>
                                <td>Contrato de Manutenção</td>
                                <td>Contrato de garantia e manutençao</td>
                                <td>Ventilador Pulmonar</td>
                                <td>05/03/2025</td>
                                <td>05/03/2026</td>
                                <td>BioSupport Systems</td>
                                <td>contrato_ventilador.pdf</td>
                                <td class="acoes-tabela">
                                        <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    
                                       
                                    
                                        <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                </td>   
                            </tr>

                            <tr data-tipo="certificado" data-estado="expirado" data-equipamento="bomba">
                                <td>D003</td>
                                <td>Certificado de Calibração</td>
                                <td>Certificado de Calibração 2026</td>
                                <td>Bomba de Infusão</td>
                                <td>18/01/2024</td>
                                <td>18/01/2025</td>
                                <td>InfuCare Medical</td>
                                <td>calibracao_bomba.pdf</td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                
                                
                                
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <tr data-tipo="relatorio" data-estado="valido" data-equipamento="desfibrilhador">
                                <td>D004</td>
                                <td>Relatório Técnico</td>
                                <td>Relatório técnico de instalação</td>
                                <td>Desfibrilhador</td>
                                <td>20/03/2025</td>
                                <td>Sem validade</td>
                                <td>MedTech Portugal</td>
                                <td>relatorio_desfibrilhador.pdf</td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                            
                                    
                            
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                 </td>
                            </tr>

                            <tr data-tipo="fatura" data-estado="valido" data-equipamento="monitor">
                                <td>D005</td>
                                <td>Fatura de Aquisição</td>
                                <td>Fatura de aquisição do equipamento</td>
                                <td>Monitor Multiparamétrico</td>
                                <td>12/02/2022</td>
                                <td>Sem validade</td>
                                <td>MedTech Portugal</td>
                                <td>fatura_monitor.pdf</td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                
                                    
                                
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p id="semResultadosDocumentos" class="sem-resultados">
                    Não foram encontrados documentos com os filtros selecionados.
                </p>
            </section>

        </main>

</div>

<?php include '../includes/footer.php'; ?>