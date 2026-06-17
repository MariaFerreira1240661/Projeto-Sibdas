<?php
$pagina_atual = 'contratos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Novo Contrato</h1>
                <p>Registo de garantias e contratos associados aos equipamentos médicos.</p>
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
                    <p>Preencha os campos principais para simular o registo de um contrato ou garantia.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formContrato">

                <div class="form-grid">
                    <div>
                        <label for="codigoContrato">Código *</label>
                        <input type="text" id="codigoContrato" placeholder="Ex: C005">
                    </div>

                    <div>
                        <label for="equipamentoContrato">Equipamento associado *</label>
                        <select id="equipamentoContrato">
                            <option value="">Selecione o equipamento</option>
                            <option>Monitor Multiparamétrico</option>
                            <option>Ventilador Pulmonar</option>
                            <option>Bomba de Infusão</option>
                            <option>Desfibrilhador</option>
                            <option>Ecógrafo Portátil</option>
                        </select>
                    </div>

                    <div>
                        <label for="fornecedorContrato">Entidade responsável *</label>
                        <select id="fornecedorContrato">
                            <option value="">Selecione a entidade</option>
                            <option>Philips Healthcare</option>
                            <option>MedTech Portugal</option>
                            <option>BioSupport Systems</option>
                            <option>InfuCare Medical</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipoContrato">Tipo *</label>
                        <select id="tipoContrato">
                            <option value="">Selecione o tipo</option>
                            <option>Garantia</option>
                            <option>Manutenção preventiva</option>
                            <option>Assistência técnica</option>
                        </select>
                    </div>

                    <div>
                        <label for="dataInicioContrato">Data de início *</label>
                        <input type="date" id="dataInicioContrato">
                    </div>

                    <div>
                        <label for="dataFimContrato">Data de fim *</label>
                        <input type="date" id="dataFimContrato">
                    </div>

                    <div>
                        <label for="periodicidadeContrato">Periodicidade</label>
                        <select id="periodicidadeContrato">
                            <option>Não aplicável</option>
                            <option>Mensal</option>
                            <option>Trimestral</option>
                            <option>Semestral</option>
                            <option>Anual</option>
                        </select>
                    </div>

                    <div>
                        <label for="estadoContrato">Estado *</label>
                        <select id="estadoContrato">
                            <option value="">Selecione o estado</option>
                            <option>Ativo</option>
                            <option>A terminar</option>
                            <option>Expirado</option>
                        </select>
                    </div>

                    <div>
                        <label for="valorContrato">Valor associado</label>
                        <input type="number" id="valorContrato" placeholder="Ex: 1200">
                    </div>

                    <div>
                        <label for="documentoContrato">Documento associado</label>
                        <input type="text" id="documentoContrato" placeholder="Ex: contrato_monitor.pdf">
                    </div>
                </div>

                <div class="form-full">
                    <label for="observacoesContrato">Observações</label>
                    <textarea id="observacoesContrato" rows="4" placeholder="Observações adicionais sobre o contrato ou garantia"></textarea>
                </div>

                <p id="mensagemContrato" class="mensagem-login"></p>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar contrato
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