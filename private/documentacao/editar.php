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
                <p>Atualização da documentação técnica ou administrativa associada ao inventário.</p>
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
                    <h2>Dados do Documento</h2>
                    <p>Altere os campos necessários e guarde as alterações.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEditarDocumento">

                <div class="form-grid">
                    <div>
                        <label for="editCodigoDocumento">Código *</label>
                        <input type="text" id="editCodigoDocumento" value="D001">
                    </div>

                    <div>
                        <label for="editNomeDocumento">Nome do documento *</label>
                        <input type="text" id="editNomeDocumento" value="Manual de Utilizador">
                    </div>

                    <div>
                        <label for="editTipoDocumento">Tipo de documento *</label>
                        <select id="editTipoDocumento">
                            <option selected>Manual de Utilizador</option>
                            <option>Manual de Serviço</option>
                            <option>Certificado de Calibração</option>
                            <option>Contrato de Manutenção</option>
                            <option>Fatura</option>
                            <option>Declaração de Conformidade</option>
                            <option>Relatório Técnico</option>
                        </select>
                    </div>

                    <div>
                        <label for="editEquipamentoDocumento">Equipamento associado *</label>
                        <select id="editEquipamentoDocumento">
                            <option selected>Monitor Multiparamétrico</option>
                            <option>Ventilador Pulmonar</option>
                            <option>Bomba de Infusão</option>
                            <option>Desfibrilhador</option>
                            <option>Ecógrafo Portátil</option>
                        </select>
                    </div>

                    <div>
                        <label for="editFornecedorDocumento">Fornecedor associado</label>
                        <select id="editFornecedorDocumento">
                            <option selected>Philips Healthcare</option>
                            <option>MedTech Portugal</option>
                            <option>BioSupport Systems</option>
                            <option>InfuCare Medical</option>
                            <option>Sem fornecedor associado</option>
                        </select>
                    </div>

                    <div>
                        <label for="editDataDocumento">Data do documento *</label>
                        <input type="date" id="editDataDocumento" value="2025-02-12">
                    </div>

                    <div>
                        <label for="editValidadeDocumento">Data de validade</label>
                        <input type="date" id="editValidadeDocumento">
                    </div>

                    <div>
                        <label for="editEstadoDocumento">Estado *</label>
                        <select id="editEstadoDocumento">
                            <option selected>Válido</option>
                            <option>Pendente</option>
                            <option>Expirado</option>
                        </select>
                    </div>

                    <div>
                        <label for="editFicheiroDocumento">Nome/localização do ficheiro *</label>
                        <input type="text" id="editFicheiroDocumento" value="manual_monitor.pdf">
                    </div>

                    <div>
                        <label for="editResponsavelDocumento">Responsável pelo registo</label>
                        <input type="text" id="editResponsavelDocumento" value="Administrador">
                    </div>
                </div>

                <div class="form-full">
                    <label for="editObservacoesDocumento">Observações</label>
                    <textarea id="editObservacoesDocumento" rows="4">Documento associado ao monitor multiparamétrico utilizado na Unidade de Cuidados Intensivos.</textarea>
                </div>

                <p id="mensagemEditarDocumento" class="mensagem-login"></p>

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