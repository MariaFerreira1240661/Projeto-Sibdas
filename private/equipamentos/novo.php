<?php
$pagina_atual = 'equipamentos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Novo Equipamento</h1>
                <p>Registo completo de um novo equipamento médico no inventário hospitalar.</p>
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
                    <h2>Ficha do Equipamento</h2>
                    <p>Preencha cada etapa antes de avançar para a seguinte.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEquipamento">

                <ul class="nav nav-tabs mb-4 tabs-equipamento" id="tabsNovoEquipamento" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                            <i class="bi bi-info-circle"></i>
                            Informação geral
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" id="localizacao-tab" data-bs-toggle="tab" data-bs-target="#localizacao-tab-pane" type="button" role="tab">
                            <i class="bi bi-geo-alt"></i>
                            Localização
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" id="fornecedor-tab" data-bs-toggle="tab" data-bs-target="#fornecedor-tab-pane" type="button" role="tab">
                            <i class="bi bi-truck"></i>
                            Fornecedor
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" id="documentacao-tab" data-bs-toggle="tab" data-bs-target="#documentacao-tab-pane" type="button" role="tab">
                            <i class="bi bi-folder"></i>
                            Documentação
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" id="garantia-tab" data-bs-toggle="tab" data-bs-target="#garantia-tab-pane" type="button" role="tab">
                            <i class="bi bi-shield-check"></i>
                            Contratos
                        </button>
                    </li>
                </ul>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="textoProgressoEquipamento">
                            Etapa 1 de 5: Informação geral
                        </span>
                        <strong id="percentagemProgressoEquipamento">20%</strong>
                    </div>

                    <div class="progress" role="progressbar" aria-label="Progresso do registo do equipamento" aria-valuenow="20"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" id="barraProgressoEquipamento" style="width: 20%; background-color: #f28c8c;"></div>
                    </div>
                </div>

                <div class="tab-content" id="tabsNovoEquipamentoConteudo">

                    <div class="tab-pane fade show active" id="info" role="tabpanel" tabindex="0">
                        <h3>Informação geral</h3>

                        <div class="form-grid">
                            <div>
                                <label for="codigo">Código interno *</label>
                                <input type="text" id="codigo" placeholder="Ex: EQ006">
                            </div>

                            <div>
                                <label for="designacao">Designação *</label>
                                <input type="text" id="designacao" placeholder="Nome do equipamento">
                            </div>

                            <div>
                                <label for="categoria">Categoria *</label>
                                <select id="categoria">
                                    <option value="">Selecione uma categoria</option>
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
                                <input type="text" id="marca" placeholder="Ex: Philips">
                            </div>

                            <div>
                                <label for="modelo">Modelo *</label>
                                <input type="text" id="modelo" placeholder="Ex: IntelliVue MP5">
                            </div>

                            <div>
                                <label for="serie">Número de série *</label>
                                <input type="text" id="serie" placeholder="Ex: MP5-2022-45873">
                            </div>

                            <div>
                                <label for="fabricante">Fabricante *</label>
                                <input type="text" id="fabricante" placeholder="Ex: Philips Healthcare">
                            </div>

                            <div>
                                <label for="anoFabrico">Ano de fabrico *</label>
                                <input type="number" id="anoFabrico" placeholder="Ex: 2024">
                            </div>

                            <div>
                                <label for="estado">Estado atual *</label>
                                <select id="estado">
                                    <option value="">Selecione o estado</option>
                                    <option>Ativo</option>
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
                                    <option value="">Selecione a criticidade</option>
                                    <option>Baixa</option>
                                    <option>Média</option>
                                    <option>Alta</option>
                                    <option>Suporte de vida</option>
                                </select>
                            </div>

                            <div>
                                <label for="prioridadeManutencao">Prioridade de manutenção *</label>
                                <select id="prioridadeManutencao">
                                    <option value="">Selecione a prioridade</option>
                                    <option>Baixa</option>
                                    <option>Moderada</option>
                                    <option>Elevada</option>
                                    <option>Urgente</option>
                                </select>
                            </div>

                            <div>
                                <label for="utilizacaoClinica">Utilização clínica *</label>
                                <select id="utilizacaoClinica">
                                    <option value="">Selecione a utilização clínica</option>
                                    <option>Monitorização contínua</option>
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
                                <input type="date" id="dataAquisicao">
                            </div>

                            <div>
                                <label for="custo">Custo de aquisição *</label>
                                <input type="number" id="custo" placeholder="Ex: 4850">
                            </div>

                            <div>
                                <label for="tipoEntrada">Tipo de entrada *</label>
                                <select id="tipoEntrada">
                                    <option>Compra</option>
                                    <option>Doação</option>
                                    <option>Aluguer</option>
                                    <option>Empréstimo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-full">
                            <hr>
                            <h3>Relações e consumíveis</h3>
                            <p>Indique se este equipamento pertence a outro equipamento e se utiliza consumíveis.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="componenteOutroEquipamento">É componente de outro equipamento? *</label>
                                <select id="componenteOutroEquipamento">
                                    <option>Não</option>
                                    <option>Sim</option>
                                </select>
                            </div>

                            <div>
                                <label for="equipamentoPrincipal">Equipamento principal</label>
                                <select id="equipamentoPrincipal" disabled>
                                    <option value="">Selecione o equipamento principal</option>
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
                                    <option>Sim</option>
                                </select>
                            </div>

                            <div>
                                <label for="consumiveisEquipamento">Consumíveis utilizados</label>
                                <input type="text" id="consumiveisEquipamento" placeholder="Ex: sensores, filtros, cabos, baterias" disabled>
                            </div>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="button" class="btn-backend" id="avancarLocalizacao">
                                Próximo: Localização
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="localizacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Localização</h3>

                        <div class="form-grid">
                            <div>
                                <label for="localizacao">Serviço / Localização *</label>
                                <select id="localizacao">
                                    <option value="">Selecione uma localização</option>
                                    <option>UCI</option>
                                    <option>Bloco Operatório</option>
                                    <option>Medicina Interna</option>
                                    <option>Urgência</option>
                                    <option>Imagiologia</option>
                                    <option>Laboratório</option>
                                </select>
                            </div>

                            <div>
                                <label for="edificioEquipamento">Edifício *</label>
                                <input type="text" id="edificioEquipamento" placeholder="Ex: Edifício Principal">
                            </div>

                            <div>
                                <label for="pisoEquipamento">Piso *</label>
                                <input type="text" id="pisoEquipamento" placeholder="Ex: 2" inputmode="numeric" pattern="[0-9]*" maxlength="3">
                            </div>

                            <div>
                                <label for="salaEquipamento">Sala / Unidade *</label>
                                <input type="text" id="salaEquipamento" placeholder="Ex: UCI Geral">
                            </div>

                            <div>
                                <label for="responsavelLocalizacaoEquipamento">Responsável da localização *</label>
                                <input type="text" id="responsavelLocalizacaoEquipamento" placeholder="Ex: Enfermeiro responsável">
                            </div>

                            <div>
                                <label for="contactoLocalizacaoEquipamento">Contacto interno *</label>
                                <input type="text" id="contactoLocalizacaoEquipamento" placeholder="Ex: 2201" inputmode="numeric" pattern="[0-9]*" maxlength="6">
                            </div>

                            <div>
                                <label for="estadoLocalizacaoEquipamento">Estado da localização *</label>
                                <select id="estadoLocalizacaoEquipamento">
                                    <option value="">Selecione o estado</option>
                                    <option>Ativa</option>
                                    <option>Em manutenção</option>
                                    <option>Inativa</option>
                                    <option>Temporariamente indisponível</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="button" class="btn-secundario" id="voltarInfo">
                                <i class="bi bi-arrow-left-circle"></i>
                                Voltar
                            </button>

                            <button type="button" class="btn-backend" id="avancarFornecedor">
                                Próximo: Fornecedor
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="fornecedor-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Fornecedores associados</h3>
                        <p>Associe um ou mais fornecedores ao equipamento. O primeiro fornecedor é obrigatório.</p>

                        <div class="form-full">
                            <h3>Fornecedor associado 1 *</h3>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="fornecedorEquipamento">Fornecedor *</label>
                                <input type="text" id="fornecedorEquipamento" placeholder="Ex: MedTech Portugal" required>
                            </div>

                            <div>
                                <label for="tipoFornecedorEquipamento">Tipo de relação *</label>
                                <select id="tipoFornecedorEquipamento" required>
                                    <option value="">Selecione o tipo</option>
                                    <option>Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Assistência técnica</option>
                                    <option>Consumíveis</option>
                                </select>
                            </div>

                            <div>
                                <label for="contactoFornecedorEquipamento">Pessoa de contacto *</label>
                                <input type="text" id="contactoFornecedorEquipamento" placeholder="Ex: Ana Silva" required>
                            </div>

                            <div>
                                <label for="telefoneFornecedorEquipamento">Telefone *</label>
                                <input type="tel" id="telefoneFornecedorEquipamento" placeholder="Ex: +351 222 000 000" inputmode="tel" required>
                            </div>

                            <div>
                                <label for="telefoneContactoFornecedorEquipamento">Telefone da pessoa de contacto *</label>
                                <input type="tel" id="telefoneContactoFornecedorEquipamento" placeholder="Ex: +351 912 000 000" inputmode="tel" required>
                            </div>

                            <div>
                                <label for="emailFornecedorEquipamento">Email *</label>
                                <input type="email" id="emailFornecedorEquipamento" placeholder="Ex: geral@empresa.pt" required>
                            </div>

                            <div>
                                <label for="nifFornecedorEquipamento">NIF *</label>
                                <input type="text" id="nifFornecedorEquipamento" placeholder="Ex: 501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9" required>
                            </div>

                            <div>
                                <label for="websiteFornecedorEquipamento">Website *</label>
                                <input type="text" id="websiteFornecedorEquipamento" placeholder="Ex: www.empresa.pt" required>
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="moradaFornecedorEquipamento">Morada *</label>
                            <textarea id="moradaFornecedorEquipamento" rows="3" placeholder="Morada do fornecedor" required></textarea>
                        </div>

                        <hr>

                        <div class="form-full">
                            <h3>Fornecedor associado 2</h3>
                            <p>Opcional. Preencha apenas se existir outro fornecedor associado ao equipamento.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="fornecedorEquipamento2">Fornecedor</label>
                                <input type="text" id="fornecedorEquipamento2" placeholder="Ex: BioSupport Systems">
                            </div>

                            <div>
                                <label for="tipoFornecedorEquipamento2">Tipo de relação</label>
                                <select id="tipoFornecedorEquipamento2">
                                    <option value="">Selecione o tipo</option>
                                    <option>Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Assistência técnica</option>
                                    <option>Consumíveis</option>
                                </select>
                            </div>

                            <div>
                                <label for="contactoFornecedorEquipamento2">Pessoa de contacto</label>
                                <input type="text" id="contactoFornecedorEquipamento2" placeholder="Ex: João Martins">
                            </div>

                            <div>
                                <label for="telefoneFornecedorEquipamento2">Telefone</label>
                                <input type="tel" id="telefoneFornecedorEquipamento2" placeholder="Ex: +351 222 111 111" inputmode="tel">
                            </div>

                            <div>
                                <label for="telefoneContactoFornecedorEquipamento2">Telefone da pessoa de contacto</label>
                                <input type="tel" id="telefoneContactoFornecedorEquipamento2" placeholder="Ex: +351 913 000 000" inputmode="tel">
                            </div>

                            <div>
                                <label for="emailFornecedorEquipamento2">Email</label>
                                <input type="email" id="emailFornecedorEquipamento2" placeholder="Ex: apoio@empresa.pt">
                            </div>

                            <div>
                                <label for="nifFornecedorEquipamento2">NIF</label>
                                <input type="text" id="nifFornecedorEquipamento2" placeholder="Ex: 509876543" inputmode="numeric" pattern="[0-9]{9}" maxlength="9">
                            </div>

                            <div>
                                <label for="websiteFornecedorEquipamento2">Website</label>
                                <input type="text" id="websiteFornecedorEquipamento2" placeholder="Ex: www.empresa.pt">
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="moradaFornecedorEquipamento2">Morada</label>
                            <textarea id="moradaFornecedorEquipamento2" rows="3" placeholder="Morada do fornecedor"></textarea>
                        </div>

                        <hr>

                        <div class="form-full">
                            <h3>Fornecedor associado 3</h3>
                            <p>Opcional. Preencha apenas se existir outro fornecedor associado ao equipamento.</p>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label for="fornecedorEquipamento3">Fornecedor</label>
                                <input type="text" id="fornecedorEquipamento3" placeholder="Ex: MedTech Portugal">
                            </div>

                            <div>
                                <label for="tipoFornecedorEquipamento3">Tipo de relação</label>
                                <select id="tipoFornecedorEquipamento3">
                                    <option value="">Selecione o tipo</option>
                                    <option>Fabricante</option>
                                    <option>Distribuidor</option>
                                    <option>Assistência técnica</option>
                                    <option>Consumíveis</option>
                                </select>
                            </div>

                            <div>
                                <label for="contactoFornecedorEquipamento3">Pessoa de contacto</label>
                                <input type="text" id="contactoFornecedorEquipamento3" placeholder="Ex: Carla Pereira">
                            </div>

                            <div>
                                <label for="telefoneFornecedorEquipamento3">Telefone</label>
                                <input type="tel" id="telefoneFornecedorEquipamento3" placeholder="Ex: +351 222 222 222" inputmode="tel">
                            </div>

                            <div>
                                <label for="telefoneContactoFornecedorEquipamento3">Telefone da pessoa de contacto</label>
                                <input type="tel" id="telefoneContactoFornecedorEquipamento3" placeholder="Ex: +351 914 000 000" inputmode="tel">
                            </div>

                            <div>
                                <label for="emailFornecedorEquipamento3">Email</label>
                                <input type="email" id="emailFornecedorEquipamento3" placeholder="Ex: geral@fornecedor.pt">
                            </div>

                            <div>
                                <label for="nifFornecedorEquipamento3">NIF</label>
                                <input type="text" id="nifFornecedorEquipamento3" placeholder="Ex: 506789123" inputmode="numeric" pattern="[0-9]{9}" maxlength="9">
                            </div>

                            <div>
                                <label for="websiteFornecedorEquipamento3">Website</label>
                                <input type="text" id="websiteFornecedorEquipamento3" placeholder="Ex: www.fornecedor.pt">
                            </div>
                        </div>


                        <div class="form-full">
                            <label for="moradaFornecedorEquipamento3">Morada</label>
                            <textarea id="moradaFornecedorEquipamento3" rows="3" placeholder="Morada do fornecedor"></textarea>
                        </div>

                        <div class="detalhe-card mt-4">
                            <h3>Fornecedores adicionais</h3>

                            <p class="mb-3">
                                Use este campo apenas se o equipamento tiver mais de três fornecedores associados.
                                Indique o nome do fornecedor, tipo de relação, contacto, telefone, email e NIF, se aplicável.
                            </p>

                            <div class="form-full">
                                <label for="fornecedoresAdicionaisEquipamento">Outros fornecedores associados</label>
                                <textarea
                                    id="fornecedoresAdicionaisEquipamento"
                                    rows="5"
                                    placeholder="Ex: Fornecedor 4 - Nome: ..., Tipo de relação: ..., Contacto: ..., Telefone: ..., Email: ..., NIF: ..."></textarea>
                            </div>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="button" class="btn-secundario" id="voltarLocalizacao">
                                <i class="bi bi-arrow-left-circle"></i>
                                Voltar
                            </button>

                            <button type="button" class="btn-backend" id="avancarDocumentacao">
                                Próximo: Documentação
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="documentacao-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Documentação</h3>
                        <p>Associe os documentos PDF ao equipamento. Para cada documento, indique o tipo, nome, data, validade quando aplicável, fornecedor associado e ficheiro.</p>

                        <div class="detalhe-card mb-4">
                            <h3>Manual de utilizador</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeManualUtilizadorEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeManualUtilizadorEquipamento" placeholder="Ex: Manual de utilizador Philips IntelliVue MP5" required>
                                </div>

                                <div>
                                    <label for="dataManualUtilizadorEquipamento">Data do documento *</label>
                                    <input type="date" id="dataManualUtilizadorEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeManualUtilizadorEquipamento">Data de validade</label>
                                    <input type="date" id="validadeManualUtilizadorEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorManualUtilizadorEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorManualUtilizadorEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="manualEquipamento">PDF do manual de utilizador *</label>
                                    <input type="file" id="manualEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Manual de serviço</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeManualServicoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeManualServicoEquipamento" placeholder="Ex: Manual de serviço Philips IntelliVue MP5" required>
                                </div>

                                <div>
                                    <label for="dataManualServicoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataManualServicoEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeManualServicoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeManualServicoEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorManualServicoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorManualServicoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="manualServicoEquipamento">PDF do manual de serviço *</label>
                                    <input type="file" id="manualServicoEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Certificado de calibração</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeCertificadoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeCertificadoEquipamento" placeholder="Ex: Certificado de calibração 2026" required>
                                </div>

                                <div>
                                    <label for="dataCertificadoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataCertificadoEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeCertificadoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeCertificadoEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorCertificadoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorCertificadoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="certificadoEquipamento">PDF do certificado de calibração *</label>
                                    <input type="file" id="certificadoEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Declaração de conformidade</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeDeclaracaoConformidadeEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeDeclaracaoConformidadeEquipamento" placeholder="Ex: Declaração de conformidade CE" required>
                                </div>

                                <div>
                                    <label for="dataDeclaracaoConformidadeEquipamento">Data do documento *</label>
                                    <input type="date" id="dataDeclaracaoConformidadeEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeDeclaracaoConformidadeEquipamento">Data de validade</label>
                                    <input type="date" id="validadeDeclaracaoConformidadeEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorDeclaracaoConformidadeEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorDeclaracaoConformidadeEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="declaracaoConformidadeEquipamento">PDF da declaração de conformidade *</label>
                                    <input type="file" id="declaracaoConformidadeEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Relatório técnico</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeRelatorioTecnicoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeRelatorioTecnicoEquipamento" placeholder="Ex: Relatório técnico de instalação" required>
                                </div>

                                <div>
                                    <label for="dataRelatorioTecnicoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataRelatorioTecnicoEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeRelatorioTecnicoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeRelatorioTecnicoEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorRelatorioTecnicoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorRelatorioTecnicoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="relatorioTecnicoEquipamento">PDF do relatório técnico *</label>
                                    <input type="file" id="relatorioTecnicoEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Fatura ou guia de aquisição</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeFaturaAquisicaoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeFaturaAquisicaoEquipamento" placeholder="Ex: Fatura de aquisição do equipamento" required>
                                </div>

                                <div>
                                    <label for="dataFaturaAquisicaoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataFaturaAquisicaoEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeFaturaAquisicaoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeFaturaAquisicaoEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorFaturaAquisicaoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorFaturaAquisicaoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="faturaAquisicaoEquipamento">PDF da fatura ou guia de aquisição *</label>
                                    <input type="file" id="faturaAquisicaoEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="detalhe-card mb-4">
                            <h3>Contrato / garantia</h3>

                            <div class="form-grid">
                                <div>
                                    <label for="nomeContratoDocumentoEquipamento">Nome do documento *</label>
                                    <input type="text" id="nomeContratoDocumentoEquipamento" placeholder="Ex: Contrato de garantia e manutenção" required>
                                </div>

                                <div>
                                    <label for="dataContratoDocumentoEquipamento">Data do documento *</label>
                                    <input type="date" id="dataContratoDocumentoEquipamento" required>
                                </div>

                                <div>
                                    <label for="validadeContratoDocumentoEquipamento">Data de validade</label>
                                    <input type="date" id="validadeContratoDocumentoEquipamento">
                                </div>

                                <div>
                                    <label for="fornecedorContratoDocumentoEquipamento">Fornecedor associado *</label>
                                    <select id="fornecedorContratoDocumentoEquipamento" required>
                                        <option value="">Selecione o fornecedor associado</option>
                                        <option value="Fornecedor associado 1">Fornecedor associado 1</option>
                                        <option value="Fornecedor associado 2">Fornecedor associado 2</option>
                                        <option value="Fornecedor associado 3">Fornecedor associado 3</option>
                                        <option value="Não aplicável">Não aplicável</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="contratoDocumentoEquipamento">PDF do contrato / garantia *</label>
                                    <input type="file" id="contratoDocumentoEquipamento" accept=".pdf,application/pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-full">
                            <label for="observacoes">Observações</label>
                            <textarea id="observacoes" rows="4" placeholder="Observações adicionais sobre o equipamento"></textarea>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="button" class="btn-secundario" id="voltarFornecedor">
                                <i class="bi bi-arrow-left-circle"></i>
                                Voltar
                            </button>

                            <button type="button" class="btn-backend" id="avancarContratos">
                                Próximo: Contratos
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="garantia-tab-pane" role="tabpanel" tabindex="0">
                        <h3>Contratos</h3>
                        <p>Associe a garantia ou contrato ao equipamento.</p>

                        <div class="form-grid">
                            <div>
                                <label for="estadoGarantiaEquipamento">Estado do contrato / garantia *</label>
                                <select id="estadoGarantiaEquipamento" required>
                                    <option value="">Selecione o estado</option>
                                    <option>Ativo</option>
                                    <option>A terminar</option>
                                    <option>Expirado</option>
                                    <option>Sem garantia registada</option>
                                </select>
                            </div>

                            <div>
                                <label for="dataInicioGarantiaEquipamento">Data de início da garantia *</label>
                                <input type="date" id="dataInicioGarantiaEquipamento">
                            </div>

                            <div>
                                <label for="dataFimGarantiaEquipamento">Data de fim da garantia *</label>
                                <input type="date" id="dataFimGarantiaEquipamento">
                            </div>

                            <div>
                                <label for="tipoContratoEquipamento">Tipo de contrato *</label>
                                <select id="tipoContratoEquipamento">
                                    <option value="">Selecione o tipo</option>
                                    <option>Garantia</option>
                                    <option>Manutenção preventiva</option>
                                    <option>Assistência técnica</option>
                                </select>
                            </div>

                            <div>
                                <label for="periodicidadeManutencaoEquipamento">Periodicidade de manutenção *</label>
                                <select id="periodicidadeManutencaoEquipamento">
                                    <option value="">Selecione a periodicidade</option>
                                    <option>Não aplicável</option>
                                    <option>Mensal</option>
                                    <option>Trimestral</option>
                                    <option>Semestral</option>
                                    <option>Anual</option>
                                </select>
                            </div>

                            <div>
                                <label for="valorContratoEquipamento">Valor associado *</label>
                                <input type="number" id="valorContratoEquipamento" placeholder="Ex: 1200">
                            </div>
                        </div>

                        <div class="form-botoes mt-4">
                            <button type="button" class="btn-backend" id="guardarEquipamento">
                                <i class="bi bi-check-circle"></i>
                                Guardar equipamento
                            </button>

                            <a href="index.php" class="btn-secundario">
                                Cancelar
                            </a>
                        </div>
                    </div>

                </div>

                <p id="mensagemEquipamento" class="mensagem-login mt-4"></p>

                <div class="form-botoes">
                    <button type="button" class="btn-backend" id="guardarEquipamento">
                        <i class="bi bi-check-circle"></i>
                        Guardar equipamento
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>