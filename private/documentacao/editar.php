<?php
$pagina_atual = 'documentacao';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Editar Documento</h1>
                <p>Atualização dos dados de um documento técnico ou administrativo.</p>
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
                    <h2>Ficha do Documento</h2>
                    <p>Edite os dados principais do documento associado ao equipamento.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEditarDocumento" action="#" method="post" enctype="multipart/form-data" novalidate>

                <div class="form-grid">
                    <div>
                        <label for="editCodigoDocumento">Código *</label>
                        <input type="text" id="editCodigoDocumento" placeholder="Ex: DOC006" value="DOC001">
                    </div>

                    <div>
                        <label for="editNomeDocumento">Nome do documento *</label>
                        <input type="text" id="editNomeDocumento" placeholder="Ex: Manual de utilizador" value="Manual de utilizador Philips IntelliVue MP5">
                    </div>

                    <div>
                        <label for="editTipoDocumento">Tipo de documento *</label>
                        <select id="editTipoDocumento">
                            <option value="">Selecione o tipo</option>
                            <option selected>Manual de utilizador</option>
                            <option>Manual de serviço</option>
                            <option>Certificado de calibração</option>
                            <option>Declaração de conformidade</option>
                            <option>Relatório técnico</option>
                            <option>Fatura ou guia de aquisição</option>
                            <option>Contrato / garantia</option>
                        </select>
                    </div>

                    <div>
                        <label for="editEquipamentoDocumento">Equipamento associado *</label>
                        <select id="editEquipamentoDocumento">
                            <option value="">Selecione o equipamento</option>
                            <option selected>EQ001 - Monitor Multiparamétrico Philips IntelliVue MP5</option>
                            <option>EQ002 - Ventilador Pulmonar Dräger Evita V500</option>
                            <option>EQ003 - Bomba de Infusão B. Braun Infusomat Space</option>
                            <option>EQ004 - Desfibrilhador Zoll R Series</option>
                        </select>
                    </div>

                    <div>
                        <label for="editFornecedorDocumento">Fornecedor associado</label>
                        <select id="editFornecedorDocumento">
                            <option value="">Sem fornecedor associado</option>
                            <option selected>Philips Healthcare</option>
                            <option>Dräger Portugal</option>
                            <option>B. Braun Medical</option>
                            <option>Zoll Medical</option>
                        </select>
                    </div>

                    <div>
                        <label for="editDataDocumento">Data do documento *</label>
                        <input type="date" id="editDataDocumento" value="2022-02-12">
                    </div>

                    <div>
                        <label for="editValidadeDocumento">Data de validade</label>
                        <input type="date" id="editValidadeDocumento">
                    </div>

                    <div>
                        <label for="editEstadoDocumento">Estado *</label>
                        <select id="editEstadoDocumento">
                            <option value="">Selecione o estado</option>
                            <option selected>Válido</option>
                            <option>Expirado</option>
                            <option>Em revisão</option>
                            <option>Substituído</option>
                        </select>
                    </div>

                    <div>
                        <label for="editFicheiroDocumento">Substituir ficheiro PDF</label>
                        <input type="file" id="editFicheiroDocumento" accept=".pdf,application/pdf">
                    </div>

                    <div>
                        <label for="editResponsavelDocumento">Responsável pelo registo</label>
                        <input type="text" id="editResponsavelDocumento" placeholder="Ex: Administrador" value="Administrador MedControl">
                    </div>
                </div>

                <div class="form-full">
                    <label>Ficheiro atual</label>
                    <p>
                        <a href="#" target="_blank">
                            manual_monitor.pdf
                        </a>
                    </p>
                </div>

                <div class="form-full">
                    <label for="editObservacoesDocumento">Observações</label>
                    <textarea id="editObservacoesDocumento" rows="4" placeholder="Observações adicionais sobre o documento">Documento técnico associado ao equipamento.</textarea>
                </div>

                <div class="form-botoes">
                    <button type="button" class="btn-backend" id="guardarEdicaoDocumento">
                        <i class="bi bi-check-circle"></i>
                        Guardar alterações
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

                <p id="mensagemEditarDocumento" class="mensagem-login mt-4"></p>
            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>
