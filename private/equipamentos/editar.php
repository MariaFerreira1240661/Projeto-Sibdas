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
                                <label for="localizacao">Serviço / Localização *</label>
                                <select id="localizacao">
                                    <option selected>UCI</option>
                                    <option>Bloco Operatório</option>
                                    <option>Medicina Interna</option>
                                    <option>Urgência</option>
                                    <option>Imagiologia</option>
                                </select>
                            </div>

                            <div>
                                <label for="edificioEquipamento">Edifício *</label>
                                <input type="text" id="edificioEquipamento" value="Edifício Principal">
                            </div>

                            <div>
                                <label for="pisoEquipamento">Piso *</label>
                                <input type="text" id="pisoEquipamento" value="2" inputmode="numeric" pattern="[0-9]*" maxlength="3">
                            </div>

                            <div>
                                <label for="salaEquipamento">Sala / Unidade *</label>
                                <input type="text" id="salaEquipamento" value="UCI Geral">
                            </div>

                            <div>
                                <label for="responsavelLocalizacaoEquipamento">Responsável da localização *</label>
                                <input type="text" id="responsavelLocalizacaoEquipamento" value="Enfermeiro responsável">
                            </div>

                            <div>
                                <label for="contactoLocalizacaoEquipamento">Contacto interno *</label>
                                <input type="text" id="contactoLocalizacaoEquipamento" value="2201" inputmode="numeric" pattern="[0-9]*" maxlength="6">
                            </div>

                            <div>
                                <label for="estadoLocalizacaoEquipamento">Estado da localização *</label>
                                <select id="estadoLocalizacaoEquipamento">
                                    <option selected>Ativa</option>
                                    <option>Em manutenção</option>
                                    <option>Inativa</option>
                                    <option>Temporariamente indisponível</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="fornecedor-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Fornecedor</h3>

                        <div class="form-grid">
                            <div>
                                <label for="fornecedorEquipamento">Fornecedor *</label>
                                <input type="text" id="fornecedorEquipamento" value="Philips Healthcare">
                            </div>

                            <div>
                                <label for="tipoFornecedorEquipamento">Tipo de fornecedor *</label>
                                <select id="tipoFornecedorEquipamento">
                                    <option selected>Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Assistência técnica</option>
                                    <option>Consumíveis</option>
                                </select>
                            </div>

                            <div>
                                <label for="contactoFornecedorEquipamento">Pessoa de contacto *</label>
                                <input type="text" id="contactoFornecedorEquipamento" value="Ana Silva">
                            </div>

                            <div>
                                <label for="telefoneFornecedorEquipamento">Telefone *</label>
                                <input type="tel" id="telefoneFornecedorEquipamento" value="+351 222 100 100">
                            </div>

                            <div>
                                <label for="emailFornecedorEquipamento">Email *</label>
                                <input type="email" id="emailFornecedorEquipamento" value="geral@philips-health.pt">
                            </div>

                            <div>
                                <label for="nifFornecedorEquipamento">NIF *</label>
                                <input type="text" id="nifFornecedorEquipamento" value="501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="documentacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Documentação</h3>
                        <p>Atualize os documentos PDF associados ao equipamento.</p>

                        <div class="detalhe-card mb-4">
                            <h3>Documentos atualmente associados</h3>

                            <div class="detalhe-linha">
                                <span>Manual de utilizador</span>
                                <strong>manual_monitor.pdf</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Certificado de calibração</span>
                                <strong>certificado_monitor.pdf</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Relatório técnico</span>
                                <strong>relatorio_monitor.pdf</strong>
                            </div>

                            <div class="detalhe-linha">
                                <span>Contrato / garantia</span>
                                <strong>contrato_monitor.pdf</strong>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="manualEquipamento">Substituir manual de utilizador</label>
                                <input type="file" id="manualEquipamento" accept=".pdf,application/pdf">
                            </div>

                            <div>
                                <label for="certificadoEquipamento">Substituir certificado de calibração</label>
                                <input type="file" id="certificadoEquipamento" accept=".pdf,application/pdf">
                            </div>

                            <div>
                                <label for="relatorioTecnicoEquipamento">Substituir relatório técnico</label>
                                <input type="file" id="relatorioTecnicoEquipamento" accept=".pdf,application/pdf">
                            </div>

                            <div>
                                <label for="contratoDocumentoEquipamento">Substituir contrato / garantia</label>
                                <input type="file" id="contratoDocumentoEquipamento" accept=".pdf,application/pdf">
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
                                <label for="estadoGarantiaEquipamento">Estado da garantia *</label>
                                <select id="estadoGarantiaEquipamento">
                                    <option selected>Válida</option>
                                    <option>A expirar</option>
                                    <option>Expirada</option>
                                    <option>Sem garantia registada</option>
                                </select>
                            </div>

                            <div>
                                <label for="dataInicioGarantiaEquipamento">Data de início da garantia *</label>
                                <input type="date" id="dataInicioGarantiaEquipamento" value="2022-02-12">
                            </div>

                            <div>
                                <label for="dataFimGarantiaEquipamento">Data de fim da garantia *</label>
                                <input type="date" id="dataFimGarantiaEquipamento" value="2027-02-12">
                            </div>

                            <div>
                                <label for="tipoContratoEquipamento">Tipo de contrato *</label>
                                <select id="tipoContratoEquipamento">
                                    <option selected>Garantia</option>
                                    <option>Manutenção preventiva</option>
                                    <option>Assistência técnica</option>
                                </select>
                            </div>

                            <div>
                                <label for="periodicidadeManutencaoEquipamento">Periodicidade de manutenção *</label>
                                <select id="periodicidadeManutencaoEquipamento">
                                    <option>Não aplicável</option>
                                    <option>Mensal</option>
                                    <option>Trimestral</option>
                                    <option>Semestral</option>
                                    <option selected>Anual</option>
                                </select>
                            </div>

                            <div>
                                <label for="valorContratoEquipamento">Valor associado *</label>
                                <input type="number" id="valorContratoEquipamento" value="1200">
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