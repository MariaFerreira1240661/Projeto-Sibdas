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
                <p>Atualização dos dados gerais da entidade fornecedora.</p>
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
                    <h2>Dados do Fornecedor Geral</h2>
                    <p>Altere apenas os dados gerais da entidade fornecedora.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEditarFornecedor">

                <div class="form-grid">
                    <div>
                        <label for="editCodigoFornecedor">Código interno *</label>
                        <input type="text" id="editCodigoFornecedor" value="FOR001" required>
                    </div>

                    <div>
                        <label for="editNomeFornecedor">Fornecedor *</label>
                        <input type="text" id="editNomeFornecedor" value="Philips Healthcare" required>
                    </div>

                    <div>
                        <label for="editNifFornecedor">NIF *</label>
                        <input type="text" id="editNifFornecedor" value="501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9" required>
                    </div>

                    <div>
                        <label for="editTelefoneFornecedor">Telefone do fornecedor *</label>
                        <input type="tel" id="editTelefoneFornecedor" value="+351 222 000 000" required>
                    </div>

                    <div>
                        <label for="editEmailFornecedor">Email do fornecedor *</label>
                        <input type="email" id="editEmailFornecedor" value="geral@empresa.pt" required>
                    </div>

                    <div>
                        <label for="editWebsiteFornecedor">Website *</label>
                        <input type="text" id="editWebsiteFornecedor" value="www.empresa.pt" required>
                    </div>
                </div>

                <div class="form-full">
                    <label for="editMoradaFornecedor">Morada *</label>
                    <textarea id="editMoradaFornecedor" rows="3" required>Rua Principal, 100, Porto</textarea>
                </div>

                <div class="form-full">
                    <label for="editObservacoesFornecedor">Observações</label>
                    <textarea id="editObservacoesFornecedor" rows="4">Fornecedor geral registado no sistema.</textarea>
                </div>

                <p id="mensagemEditarFornecedor" class="mensagem-login"></p>

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
