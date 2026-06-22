<?php
$pagina_atual = 'contratos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Editar Contrato</h1>
                <p>Atualização dos dados de garantia, manutenção ou assistência técnica.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                   

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
                    <h2>Ficha do Contrato</h2>
                    <p>Edite os dados principais do contrato associado ao equipamento.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEditarContrato" action="#" method="post" novalidate>

                <div class="form-grid">
                    <div>
                        <label for="editCodigoContrato">Código *</label>
                        <input type="text" id="editCodigoContrato" placeholder="Ex: CON006" value="CON001">
                    </div>

                    <div>
                        <label for="editEquipamentoContrato">Equipamento associado *</label>
                        <select id="editEquipamentoContrato">
                            <option value="">Selecione o equipamento</option>
                            <option selected>EQ001 - Monitor Multiparamétrico Philips IntelliVue MP5</option>
                            <option>EQ002 - Ventilador Pulmonar Dräger Evita V500</option>
                            <option>EQ003 - Bomba de Infusão B. Braun Infusomat Space</option>
                            <option>EQ004 - Desfibrilhador Zoll R Series</option>
                        </select>
                    </div>

                    <div>
                        <label for="editFornecedorContrato">Fornecedor associado *</label>
                        <select id="editFornecedorContrato">
                            <option value="">Selecione o fornecedor</option>
                            <option selected>Philips Healthcare</option>
                            <option>Dräger Portugal</option>
                            <option>B. Braun Medical</option>
                            <option>Zoll Medical</option>
                        </select>
                    </div>

                    <div>
                        <label for="editTipoContrato">Tipo de contrato *</label>
                        <select id="editTipoContrato">
                            <option value="">Selecione o tipo</option>
                            <option selected>Garantia</option>
                            <option>Contrato de manutenção</option>
                            <option>Assistência técnica</option>
                            <option>Aluguer</option>
                        </select>
                    </div>

                    <div>
                        <label for="editEstadoContrato">Estado *</label>
                        <select id="editEstadoContrato">
                            <option value="">Selecione o estado</option>
                            <option selected>Ativo</option>
                            <option>A terminar</option>
                            <option>Expirado</option>
                            <option>Cancelado</option>
                        </select>
                    </div>

                    <div>
                        <label for="editDataInicioContrato">Data de início *</label>
                        <input type="date" id="editDataInicioContrato" value="2024-01-01">
                    </div>

                    <div>
                        <label for="editDataFimContrato">Data de fim *</label>
                        <input type="date" id="editDataFimContrato" value="2026-12-31">
                    </div>

                    <div>
                        <label for="editPeriodicidadeContrato">Periodicidade de manutenção *</label>
                        <select id="editPeriodicidadeContrato">
                            <option value="">Selecione a periodicidade</option>
                            <option>Não aplicável</option>
                            <option>Mensal</option>
                            <option>Trimestral</option>
                            <option selected>Semestral</option>
                            <option>Anual</option>
                        </select>
                    </div>

                    <div>
                        <label for="editValorContrato">Valor associado *</label>
                        <input type="number" id="editValorContrato" placeholder="Ex: 1200" value="1200">
                    </div>

                    <div>
                        <label for="editDocumentoContrato">Documento associado</label>
                        <select id="editDocumentoContrato">
                            <option value="">Sem documento associado</option>
                            <option selected>DOC001 - Contrato / garantia</option>
                            <option>DOC002 - Fatura ou guia de aquisição</option>
                            <option>DOC003 - Relatório técnico</option>
                        </select>
                    </div>
                </div>

                <div class="form-full">
                    <label for="editObservacoesContrato">Observações</label>
                    <textarea id="editObservacoesContrato" rows="4" placeholder="Observações adicionais sobre o contrato">Contrato associado ao equipamento médico.</textarea>
                </div>

                <div class="form-botoes">
                    <button type="button" class="btn-backend" id="guardarEdicaoContrato">
                        <i class="bi bi-check-circle"></i>
                        Guardar alterações
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

                <p id="mensagemEditarContrato" class="mensagem-login mt-4"></p>
            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>
