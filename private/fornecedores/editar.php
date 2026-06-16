<?php
$pagina_atual = 'fornecedores';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

<main class="backend-content">

            <div class="backend-topbar">
                <div>
                    <h1>Editar Fornecedor</h1>
                    <p>Atualização dos dados de uma entidade associada aos equipamentos médicos.</p>
                </div>

                <div class="backend-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </div>
            </div>

            <section class="backend-box">
                <div class="backend-section-header">
                    <div>
                        <h2>Dados do Fornecedor</h2>
                        <p>Altere os campos necessários e guarde as alterações.</p>
                    </div>

                    <a href=".php" class="btn-backend">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>

                <form class="form-backend" id="formEditarFornecedor">

                    <div class="form-grid">
                        <div>
                            <label for="editCodigoFornecedor">Código interno *</label>
                            <input type="text" id="editCodigoFornecedor" value="F001" required>
                        </div>

                        <div>
                            <label for="editNomeFornecedor">Nome da empresa *</label>
                            <input type="text" id="editNomeFornecedor" value="Philips Healthcare" required>
                        </div>

                        <div>
                            <label for="editNifFornecedor">NIF *</label>
                            <input type="text" id="editNifFornecedor" value="501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9" required>
                        </div>

                        <div>
                            <label for="editTipoFornecedor">Tipo de fornecedor *</label>
                            <select id="editTipoFornecedor" required>
                                <option selected>Fabricante</option>
                                <option>Distribuidor</option>
                                <option>Assistência técnica</option>
                                <option>Consumíveis</option>
                            </select>
                        </div>

                        <div>
                            <label for="editTelefoneFornecedor">Telefone *</label>
                            <input type="tel" id="editTelefoneFornecedor" value="+351 222 000 000" inputmode="tel" required>
                        </div>

                        <div>
                            <label for="editEmailFornecedor">Email *</label>
                            <input type="email" id="editEmailFornecedor" value="geral@philips-health.pt" required>
                        </div>

                        <div>
                            <label for="editWebsiteFornecedor">Website</label>
                            <input type="text" id="editWebsiteFornecedor" value="www.philips.pt">
                        </div>

                        

                        <div>
                            <label for="editPessoaContactoFornecedor">Pessoa de contacto *</label>
                            <input type="text" id="editPessoaContactoFornecedor" value="Ana Silva" placeholder="Ex: Ana Silva" required>
                        </div>

                        <div>
                            <label for="editTelefoneContactoFornecedor">Telefone da pessoa de contacto</label>
                            <input type="tel" id="editTelefoneContactoFornecedor" value="+351 912 000 000" inputmode="tel" required>
                        </div>
                    </div>

                    <div class="form-full">
                        <label for="editMoradaFornecedor">Morada</label>
                        <textarea id="editMoradaFornecedor" rows="3">Avenida da Saúde, nº 120, 4100-000 Porto, Portugal</textarea>
                    </div>

                    <div class="form-full">
                        <label for="editObservacoesFornecedor">Observações</label>
                        <textarea id="editObservacoesFornecedor" rows="4">Entidade registada como fabricante de equipamentos de monitorização.</textarea>
                    </div>

                    <p id="mensagemEditarFornecedor" class="mensagem-login"></p>

                    <div class="form-botoes">
                        <button type="submit" class="btn-backend">
                            <i class="bi bi-check-circle"></i>
                            Guardar alterações
                        </button>

                        <a href=".php" class="btn-secundario">
                            Cancelar
                        </a>
                    </div>

                </form>
            </section>

        </main>

</div>

<?php include '../includes/footer.php'; ?>
