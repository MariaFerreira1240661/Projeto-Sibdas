<?php
$pagina_atual = 'equipamentos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Editar Equipamento</h1>
                <p>Atualização dos dados técnicos, administrativos e documentais do equipamento médico.</p>
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
                    <h2>Editar ficha do equipamento</h2>
                    <p>Equipamento selecionado: <strong>EQ001 — Monitor Multiparamétrico</strong></p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEquipamento">

                <ul class="nav nav-tabs mb-4 tabs-equipamento" id="tabsEditarEquipamento" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                            <i class="bi bi-info-circle"></i>
                            Informação geral
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="localizacao-tab" data-bs-toggle="tab" data-bs-target="#localizacao-tab-pane" type="button" role="tab">
                            <i class="bi bi-geo-alt"></i>
                            Localização
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fornecedor-tab" data-bs-toggle="tab" data-bs-target="#fornecedor-tab-pane" type="button" role="tab">
                            <i class="bi bi-truck"></i>
                            Fornecedor
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documentacao-tab" data-bs-toggle="tab" data-bs-target="#documentacao-tab-pane" type="button" role="tab">
                            <i class="bi bi-folder"></i>
                            Documentação
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="garantia-tab" data-bs-toggle="tab" data-bs-target="#garantia-tab-pane" type="button" role="tab">
                            <i class="bi bi-shield-check"></i>
                            Contratos
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="tabsEditarEquipamentoConteudo">

                    <div class="tab-pane fade show active" id="info" role="tabpanel" tabindex="0">
                        <h3>Informação geral</h3>

                        <div class="form-grid">
                            <div>
                                <label for="codigo">Código interno *</label>
                                <input type="text" id="codigo" value="EQ001">
                            </div>

                            <div>
                                <label for="designacao">Designação *</label>
                                <input type="text" id="designacao" value="Monitor Multiparamétrico">
                            </div>

                            <div>
                                <label for="categoria">Categoria *</label>
                                <select id="categoria">
                                    <option>Monitorização</option>
                                    <option>Suporte de vida</option>
                                    <option>Terapia</option>
                                    <option>Diagnóstico</option>
                                    <option>Laboratório</option>
                                    <option>Esterilização</option>
                                </select>
                            </div>

                            <div>
                                <label for="marca">Marca *</label>
                                <input type="text" id="marca" value="Philips">
                            </div>

                            <div>
                                <label for="modelo">Modelo *</label>
                                <input type="text" id="modelo" value="IntelliVue MP5">
                            </div>

                            <div>
                                <label for="serie">Número de série *</label>
                                <input type="text" id="serie" value="MP5-2022-45873">
                            </div>

                            <div>
                                <label for="fabricante">Fabricante *</label>
                                <input type="text" id="fabricante" value="Philips Healthcare">
                            </div>

                            <div>
                                <label for="anoFabrico">Ano de fabrico *</label>
                                <input type="number" id="anoFabrico" value="2022">
                            </div>

                            <div>
                                <label for="estado">Estado atual *</label>
                                <select id="estado">
                                    <option selected>Ativo</option>
                                    <option>Em manutenção</option>
                                    <option>Inativo</option>
                                    <option>Em calibração</option>
                                    <option>Em quarentena</option>
                                    <option>Abatido</option>
                                </select>
                            </div>

                            <div>
                                <label for="criticidade">Criticidade *</label>
                                <select id="criticidade">
                                    <option>Baixa</option>
                                    <option>Média</option>
                                    <option>Alta</option>
                                    <option selected>Suporte de vida</option>
                                </select>
                            </div>

                            <div>
                                <label for="prioridadeManutencao">Prioridade de manutenção *</label>
                                <select id="prioridadeManutencao">
                                    <option>Baixa</option>
                                    <option>Moderada</option>
                                    <option selected>Elevada</option>
                                    <option>Urgente</option>
                                </select>
                            </div>

                            <div>
                                <label for="utilizacaoClinica">Utilização clínica *</label>
                                <select id="utilizacaoClinica">
                                    <option selected>Monitorização contínua</option>
                                    <option>Suporte ventilatório</option>
                                    <option>Terapia de infusão</option>
                                    <option>Diagnóstico</option>
                                    <option>Reanimação</option>
                                    <option>Esterilização</option>
                                    <option>Outro</option>
                                </select>
                            </div>

                            <div>
                                <label for="dataAquisicao">Data de aquisição *</label>
                                <input type="date" id="dataAquisicao" value="2022-02-12">
                            </div>

                            <div>
                                <label for="custo">Custo de aquisição *</label>
                                <input type="number" id="custo" value="4850">
                            </div>

                            <div>
                                <label for="tipoEntrada">Tipo de entrada *</label>
                                <select id="tipoEntrada">
                                    <option selected>Compra</option>
                                    <option>Doação</option>
                                    <option>Aluguer</option>
                                    <option>Empréstimo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-full">
                            <hr>
                            <h3>Relações e consumíveis</h3>
                            <p>Atualize a relação com outros equipamentos e consumíveis associados.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="componenteOutroEquipamento">É componente de outro equipamento? *</label>
                                <select id="componenteOutroEquipamento">
                                    <option selected>Não</option>
                                    <option>Sim</option>
                                </select>
                            </div>

                            <div>
                                <label for="equipamentoPrincipal">Equipamento principal</label>
                                <select id="equipamentoPrincipal" disabled>
                                    <option selected>Não aplicável</option>
                                    <option>Monitor Multiparamétrico Philips IntelliVue MP5</option>
                                    <option>Ventilador Pulmonar Dräger Evita V500</option>
                                    <option>Bomba de Infusão B. Braun Infusomat Space</option>
                                    <option>Desfibrilhador Zoll R Series</option>
                                </select>
                            </div>

                            <div>
                                <label for="temConsumiveis">Tem consumíveis? *</label>
                                <select id="temConsumiveis">
                                    <option>Não</option>
                                    <option selected>Sim</option>
                                </select>
                            </div>

                            <div>
                                <label for="consumiveisEquipamento">Consumíveis utilizados</label>
                                <input type="text" id="consumiveisEquipamento" value="Sensores SpO₂, cabos ECG e bateria">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="localizacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Localização</h3>

                        <div class="form-grid">
                            <div>
                                <label for="editLocalizacaoGeralEquipamento">Localização geral *</label>
                                <select id="editLocalizacaoGeralEquipamento" required>
                                    <option value="">Selecione uma localização geral</option>
                                    <option selected>LOC001 - Edifício Principal (5 pisos)</option>
                                    <option>LOC002 - Edifício A (3 pisos)</option>
                                    <option>LOC003 - Edifício B (4 pisos)</option>
                                </select>
                            </div>

                            <div>
                                <label for="editPisoEquipamento">Piso do equipamento *</label>
                                <input type="text" id="editPisoEquipamento" value="2" inputmode="numeric" pattern="[0-9]*" maxlength="3" required>
                            </div>

                            <div>
                                <label for="editServicoEquipamento">Serviço *</label>
                                <select id="editServicoEquipamento" required>
                                    <option value="">Selecione o serviço</option>
                                    <option selected>UCI</option>
                                    <option>Bloco Operatório</option>
                                    <option>Medicina Interna</option>
                                    <option>Urgência</option>
                                    <option>Imagiologia</option>
                                    <option>Laboratório</option>
                                    <option>Esterilização</option>
                                    <option>Consulta Externa</option>
                                </select>
                            </div>

                            <div>
                                <label for="editSalaEquipamento">Sala / Unidade *</label>
                                <input type="text" id="editSalaEquipamento" value="UCI Geral" required>
                            </div>
                        </div>

                        <p class="mt-3">
                            A localização geral contém o edifício, número de pisos, responsável, contacto e estado.
                            Aqui fica apenas o local específico do equipamento dentro dessa localização.
                        </p>
                    </div>


                    <div class="tab-pane fade" id="fornecedor-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Fornecedores associados</h3>
                        <p>Atualize visualmente os fornecedores associados ao equipamento.</p>

                        <div class="form-full">
                            <h3>Fornecedor associado 1 *</h3>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="editFornecedorEquipamento">Fornecedor *</label>
                                <select id="editFornecedorEquipamento" required>
                                    <option value="">Selecione um fornecedor já criado</option>
                                    <option selected>FOR001 - Philips Healthcare</option>
                                    <option>FOR002 - MedTech Portugal</option>
                                    <option>FOR003 - B. Braun Medical</option>
                                </select>
                            </div>

                            <div>
                                <label for="editTipoFornecedorEquipamento">Tipo de fornecedor / relação *</label>
                                <select id="editTipoFornecedorEquipamento" required>
                                    <option value="">Selecione o tipo</option>
                                    <option selected>Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Manutenção</option>
                                    <option>Fornecedor adicional</option>
                                </select>
                            </div>

                            <div>
                                <label for="editContactoFornecedorEquipamento">Pessoa de contacto *</label>
                                <input type="text" id="editContactoFornecedorEquipamento" value="Ana Silva" required>
                            </div>

                            <div>
                                <label for="editTelefoneContactoFornecedorEquipamento">Telefone da pessoa de contacto *</label>
                                <input type="tel" id="editTelefoneContactoFornecedorEquipamento" value="+351 912 000 000" inputmode="tel" required>
                            </div>

                            <div>
                                <label for="editEmailContactoFornecedorEquipamento">Email da pessoa de contacto *</label>
                                <input type="email" id="editEmailContactoFornecedorEquipamento" value="ana.silva@empresa.pt" required>
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="editObservacoesFornecedorEquipamento">Observações da relação</label>
                            <textarea id="editObservacoesFornecedorEquipamento" rows="3">Contacto preferencial para este equipamento.</textarea>
                        </div>

                        <hr>

                        <div class="form-full">
                            <h3>Fornecedor associado 2</h3>
                            <p>Opcional. Preencha apenas se existir outro fornecedor associado ao equipamento.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="editFornecedorEquipamento2">Fornecedor </label>
                                <select id="editFornecedorEquipamento2" >
                                    <option value="">Selecione um fornecedor já criado</option>
                                    <option >FOR001 - Philips Healthcare</option>
                                    <option>FOR002 - MedTech Portugal</option>
                                    <option>FOR003 - B. Braun Medical</option>
                                </select>
                            </div>

                            <div>
                                <label for="editTipoFornecedorEquipamento2">Tipo de fornecedor / relação </label>
                                <select id="editTipoFornecedorEquipamento2" >
                                    <option value="">Selecione o tipo</option>
                                    <option >Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Manutenção</option>
                                    <option>Fornecedor adicional</option>
                                </select>
                            </div>

                            <div>
                                <label for="editContactoFornecedorEquipamento2">Pessoa de contacto </label>
                                <input type="text" id="editContactoFornecedorEquipamento2" value="Ana Silva" >
                            </div>

                            <div>
                                <label for="editTelefoneContactoFornecedorEquipamento2">Telefone da pessoa de contacto </label>
                                <input type="tel" id="editTelefoneContactoFornecedorEquipamento2" value="+351 912 000 000" inputmode="tel" >
                            </div>

                            <div>
                                <label for="editEmailContactoFornecedorEquipamento2">Email da pessoa de contacto </label>
                                <input type="email" id="editEmailContactoFornecedorEquipamento2" value="ana.silva@empresa.pt" >
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="editObservacoesFornecedorEquipamento2">Observações da relação</label>
                            <textarea id="editObservacoesFornecedorEquipamento2" rows="3">Contacto preferencial para este equipamento.</textarea>
                        </div>

                        <hr>

                        <div class="form-full">
                            <h3>Fornecedor associado 3</h3>
                            <p>Opcional. Preencha apenas se existir outro fornecedor associado ao equipamento.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="editFornecedorEquipamento3">Fornecedor </label>
                                <select id="editFornecedorEquipamento3" >
                                    <option value="">Selecione um fornecedor já criado</option>
                                    <option >FOR001 - Philips Healthcare</option>
                                    <option>FOR002 - MedTech Portugal</option>
                                    <option>FOR003 - B. Braun Medical</option>
                                </select>
                            </div>

                            <div>
                                <label for="editTipoFornecedorEquipamento3">Tipo de fornecedor / relação </label>
                                <select id="editTipoFornecedorEquipamento3" >
                                    <option value="">Selecione o tipo</option>
                                    <option >Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Manutenção</option>
                                    <option>Fornecedor adicional</option>
                                </select>
                            </div>

                            <div>
                                <label for="editContactoFornecedorEquipamento3">Pessoa de contacto </label>
                                <input type="text" id="editContactoFornecedorEquipamento3" value="Ana Silva" >
                            </div>

                            <div>
                                <label for="editTelefoneContactoFornecedorEquipamento3">Telefone da pessoa de contacto </label>
                                <input type="tel" id="editTelefoneContactoFornecedorEquipamento3" value="+351 912 000 000" inputmode="tel" >
                            </div>

                            <div>
                                <label for="editEmailContactoFornecedorEquipamento3">Email da pessoa de contacto </label>
                                <input type="email" id="editEmailContactoFornecedorEquipamento3" value="ana.silva@empresa.pt" >
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="editObservacoesFornecedorEquipamento3">Observações da relação</label>
                            <textarea id="editObservacoesFornecedorEquipamento3" rows="3">Contacto preferencial para este equipamento.</textarea>
                        </div>

                        <div class="detalhe-card mt-4">
                            <h3>Fornecedores adicionais</h3>

                            <p class="mb-3">
                                Use este campo apenas se o equipamento tiver mais de três fornecedores associados.
                            </p>

                            <div class="form-full">
                                <label for="editFornecedoresAdicionaisEquipamento">Outros fornecedores associados</label>
                                <textarea id="editFornecedoresAdicionaisEquipamento" rows="5" placeholder="Ex: Fornecedor 4 - Nome: ..., Tipo: ..., Contacto: ..., Telefone: ..., Email: ..., Observações: ..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="documentacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Documentação</h3>
                        <p>Atualize os documentos associados ao equipamento. Os ficheiros só precisam de ser escolhidos se quiser substituir os PDFs atuais.</p>

                        <div class="detalhe-card mb-4">
                            <h3>Manual de utilizador</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>manual_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeManualUtilizadorEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeManualUtilizadorEquipamento" value="Manual de utilizador Philips IntelliVue MP5" required>
                                </div>

                                <div>
                                    <label for="dataManualUtilizadorEquipamento">Data do documento *</label>
                                    <input type="date" id="dataManualUtilizadorEquipamento" value="2022-02-12" required>
                                </div>

                                <div>
                                    <label for="validadeManualUtilizadorEquipamento">Data de validade</label>
                                    <input type="date" id="validadeManualUtilizadorEquipamento" value="">
                                </div>

                                <div>
                                    <label for="fornecedorManualUtilizadorEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorManualUtilizadorEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1" selected>Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="manualEquipamento">Substituir pdf do manual de utilizador</label>
                                    <input type="file" id="manualEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Manual de serviço</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>manual_servico_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeManualServicoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeManualServicoEquipamento" value="Manual de serviço Philips IntelliVue MP5" required>
                                </div>

                                <div>
                                    <label for="dataManualServicoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataManualServicoEquipamento" value="2022-02-12" required>
                                </div>

                                <div>
                                    <label for="validadeManualServicoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeManualServicoEquipamento" value="">
                                </div>

                                <div>
                                    <label for="fornecedorManualServicoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorManualServicoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1" selected>Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="manualServicoEquipamento">Substituir pdf do manual de serviço</label>
                                    <input type="file" id="manualServicoEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Certificado de calibração</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>certificado_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeCertificadoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeCertificadoEquipamento" value="Certificado de calibração 2026" required>
                                </div>

                                <div>
                                    <label for="dataCertificadoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataCertificadoEquipamento" value="2026-01-15" required>
                                </div>

                                <div>
                                    <label for="validadeCertificadoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeCertificadoEquipamento" value="2027-01-15">
                                </div>

                                <div>
                                    <label for="fornecedorCertificadoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorCertificadoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2" selected>Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="certificadoEquipamento">Substituir pdf do certificado de calibração</label>
                                    <input type="file" id="certificadoEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Declaração de conformidade</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>declaracao_conformidade_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeDeclaracaoConformidadeEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeDeclaracaoConformidadeEquipamento" value="Declaração de conformidade CE" required>
                                </div>

                                <div>
                                    <label for="dataDeclaracaoConformidadeEquipamento">Data do documento *</label>
                                    <input type="date" id="dataDeclaracaoConformidadeEquipamento" value="2022-02-12" required>
                                </div>

                                <div>
                                    <label for="validadeDeclaracaoConformidadeEquipamento">Data de validade</label>
                                    <input type="date" id="validadeDeclaracaoConformidadeEquipamento" value="">
                                </div>

                                <div>
                                    <label for="fornecedorDeclaracaoConformidadeEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorDeclaracaoConformidadeEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1" selected>Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="declaracaoConformidadeEquipamento">Substituir pdf da declaração de conformidade</label>
                                    <input type="file" id="declaracaoConformidadeEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Relatório técnico</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>relatorio_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeRelatorioTecnicoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeRelatorioTecnicoEquipamento" value="Relatório técnico de instalação" required>
                                </div>

                                <div>
                                    <label for="dataRelatorioTecnicoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataRelatorioTecnicoEquipamento" value="2022-02-14" required>
                                </div>

                                <div>
                                    <label for="validadeRelatorioTecnicoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeRelatorioTecnicoEquipamento" value="">
                                </div>

                                <div>
                                    <label for="fornecedorRelatorioTecnicoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorRelatorioTecnicoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2" selected>Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="relatorioTecnicoEquipamento">Substituir pdf do relatório técnico</label>
                                    <input type="file" id="relatorioTecnicoEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Fatura ou guia de aquisição</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>fatura_aquisicao_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeFaturaAquisicaoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeFaturaAquisicaoEquipamento" value="Fatura de aquisição do equipamento" required>
                                </div>

                                <div>
                                    <label for="dataFaturaAquisicaoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataFaturaAquisicaoEquipamento" value="2022-02-12" required>
                                </div>

                                <div>
                                    <label for="validadeFaturaAquisicaoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeFaturaAquisicaoEquipamento" value="">
                                </div>

                                <div>
                                    <label for="fornecedorFaturaAquisicaoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorFaturaAquisicaoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2" selected>Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="faturaAquisicaoEquipamento">Substituir pdf da fatura ou guia de aquisição</label>
                                    <input type="file" id="faturaAquisicaoEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Contrato / garantia</h3>

                            <div class="detalhe-linha">
                                <span>Ficheiro atualmente associado</span>
                                <strong>contrato_monitor.pdf</strong>
                            </div>

                            <div class="form-grid mt-3">
                                <div>
                                    <label for="nomeContratoDocumentoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeContratoDocumentoEquipamento" value="Contrato de garantia e manutenção" required>
                                </div>

                                <div>
                                    <label for="dataContratoDocumentoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataContratoDocumentoEquipamento" value="2022-02-12" required>
                                </div>

                                <div>
                                    <label for="validadeContratoDocumentoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeContratoDocumentoEquipamento" value="2027-02-12">
                                </div>

                                <div>
                                    <label for="fornecedorContratoDocumentoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorContratoDocumentoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1" selected>Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="contratoDocumentoEquipamento">Substituir pdf do contrato / garantia</label>
                                    <input type="file" id="contratoDocumentoEquipamento" accept=".pdf,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="observacoes">Observações</label>
                            <textarea id="observacoes" rows="4">Equipamento utilizado para monitorização contínua de sinais vitais em ambiente hospitalar.</textarea>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="garantia-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Contratos</h3>

                        <div class="form-grid">
                            <div>
                                <label for="estadoGarantiaEquipamento">Estado do contrato / garantia *</label>
                                <select id="estadoGarantiaEquipamento" required>
                                    <option selected>Ativo</option>
                                    <option>A terminar</option>
                                    <option>Expirado</option>
                                    <option>Sem garantia registada</option>
                                </select>
                            </div>

                            <div>
                                <label for="dataInicioGarantiaEquipamento">Data de início da garantia *</label>
                                <input type="date" id="dataInicioGarantiaEquipamento" value="2022-02-12" required>
                            </div>

                            <div>
                                <label for="dataFimGarantiaEquipamento">Data de fim da garantia *</label>
                                <input type="date" id="dataFimGarantiaEquipamento" value="2027-02-12" required>
                            </div>

                            <div>
                                <label for="tipoContratoEquipamento">Tipo de contrato *</label>
                                <select id="tipoContratoEquipamento" required>
                                    <option selected>Garantia</option>
                                    <option>Manutenção preventiva</option>
                                    <option>Assistência técnica</option>
                                </select>
                            </div>

                            <div>
                                <label for="periodicidadeManutencaoEquipamento">Periodicidade de manutenção *</label>
                                <select id="periodicidadeManutencaoEquipamento" required>
                                    <option>Não aplicável</option>
                                    <option>Mensal</option>
                                    <option>Trimestral</option>
                                    <option>Semestral</option>
                                    <option selected>Anual</option>
                                </select>
                            </div>

                            <div>
                                <label for="valorContratoEquipamento">Valor associado *</label>
                                <input type="number" id="valorContratoEquipamento" value="1200" required>
                            </div>
                        </div>
                    </div>

                </div>
                <p id="mensagemEquipamento" class="mensagem-login mt-4"></p>

                <div class="form-botoes">
                    <button type="button" class="btn-backend" id="guardarEquipamento">
                        <i class="bi bi-check-circle"></i>
                        Guardar alterações
                    </button>

                    <a href="detalhes.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>