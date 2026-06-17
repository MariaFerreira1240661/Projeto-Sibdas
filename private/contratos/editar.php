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
                <p>Atualização dos dados de garantia ou contrato associado ao inventário.</p>
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
                    <h2>Dados do Contrato</h2>
                    <p>Altere os campos necessários e guarde as alterações.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEditarContrato">

                <div class="form-grid">
                    <div>
                        <label for="editCodigoContrato">Código *</label>
                        <input type="text" id="editCodigoContrato" value="C001">
                    </div>

                    <div>
                        <label for="editEquipamentoContrato">Equipamento associado *</label>
                        <select id="editEquipamentoContrato">
                            <option selected>Monitor Multiparamétrico</option>
                            <option>Ventilador Pulmonar</option>
                            <option>Bomba de Infusão</option>
                            <option>Desfibrilhador</option>
                            <option>Ecógrafo Portátil</option>
                        </select>
                    </div>

                    <div>
                        <label for="editFornecedorContrato">Entidade responsável *</label>
                        <select id="editFornecedorContrato">
                            <option>Philips Healthcare</option>
                            <option selected>MedTech Portugal</option>
                            <option>BioSupport Systems</option>
                            <option>InfuCare Medical</option>
                        </select>
                    </div>

                    <div>
                        <label for="editTipoContrato">Tipo *</label>
                        <select id="editTipoContrato">
                            <option>Garantia</option>
                            <option selected>Manutenção preventiva</option>
                            <option>Assistência técnica</option>
                        </select>
                    </div>

                    <div>
                        <label for="editDataInicioContrato">Data de início *</label>
                        <input type="date" id="editDataInicioContrato" value="2025-01-01">
                    </div>

                    <div>
                        <label for="editDataFimContrato">Data de fim *</label>
                        <input type="date" id="editDataFimContrato" value="2025-12-31">
                    </div>

                    <div>
                        <label for="editPeriodicidadeContrato">Periodicidade</label>
                        <select id="editPeriodicidadeContrato">
                            <option>Não aplicável</option>
                            <option>Mensal</option>
                            <option>Trimestral</option>
                            <option>Semestral</option>
                            <option selected>Anual</option>
                        </select>
                    </div>

                    <div>
                        <label for="editEstadoContrato">Estado *</label>
                        <select id="editEstadoContrato">
                            <option selected>Ativo</option>
                            <option>A terminar</option>
                            <option>Expirado</option>
                        </select>
                    </div>

                    <div>
                        <label for="editValorContrato">Valor associado</label>
                        <input type="number" id="editValorContrato" value="1200">
                    </div>

                    <div>
                        <label for="editDocumentoContrato">Documento associado</label>
                        <input type="text" id="editDocumentoContrato" value="contrato_monitor.pdf">
                    </div>
                </div>

                <div class="form-full">
                    <label for="editObservacoesContrato">Observações</label>
                    <textarea id="editObservacoesContrato" rows="4">Contrato de manutenção preventiva associado ao monitor multiparamétrico. Deve ser revisto antes da data de fim.</textarea>
                </div>

                <p id="mensagemEditarContrato" class="mensagem-login"></p>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
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