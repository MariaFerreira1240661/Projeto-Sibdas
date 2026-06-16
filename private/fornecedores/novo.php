<?php
$pagina_atual = 'fornecedores';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

<main class="backend-content">

            <div class="backend-topbar">
                <div>
                    <h1>Novo Fornecedor</h1>
                    <p>Registo de uma entidade associada aos equipamentos médicos.</p>
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
                        <p>Preencha os campos principais para simular o registo do fornecedor.</p>
                    </div>

                    <a href=".php" class="btn-backend">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>

                <form class="form-backend" id="formFornecedor">

                    <div class="form-grid">
                        <div>
                            <label for="codigoFornecedor">Código interno *</label>
                            <input type="text" id="codigoFornecedor" placeholder="Ex: F005">
                        </div>

                        <div>
                            <label for="nomeFornecedor">Fornecedor *</label>
                            <input type="text" id="nomeFornecedor" placeholder="Ex: MedTech Portugal">
                        </div>

                        <div>
                            <label for="nifFornecedor">NIF *</label>
                            <input type="text" id="nifFornecedor" placeholder="Ex: 501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9">
                        </div>

                        <div>
                            <label for="tipoFornecedor">Tipo de fornecedor *</label>
                            <select id="tipoFornecedor">
                                <option value="">Selecione o tipo</option>
                                <option>Fabricante</option>
                                <option>Distribuidor</option>
                                <option>Assistência técnica</option>
                                <option>Consumíveis</option>
                            </select>
                        </div>

                        <div>
                            <label for="telefoneFornecedor">Telefone *</label>
                            <input type="tel" id="telefoneFornecedor" placeholder="Ex: +351 222 000 000" inputmode="tel">
                        </div>

                        <div>
                            <label for="emailFornecedor">Email *</label>
                            <input type="email" id="emailFornecedor" placeholder="Ex: geral@empresa.pt">
                        </div>

                        <div>
                            <label for="websiteFornecedor">Website </label>
                            <input type="text" id="websiteFornecedor" placeholder="Ex: www.empresa.pt">
                        </div>

                        <div>
                            <label for="pessoaContactoFornecedor">Pessoa de contacto *</label>
                            <input type="text" id="pessoaContactoFornecedor" placeholder="Ex: Ana Silva" required>
                        </div>

                        <div>
                            <label for="telefoneContactoFornecedor">Telefone da pessoa de contacto</label>
                            <input type="tel" id="telefoneContactoFornecedor" placeholder="Ex: +351 912 000 000" inputmode="tel">
                        </div>
                    </div>

                    <div class="form-full">
                        <label for="moradaFornecedor">Morada</label>
                        <textarea id="moradaFornecedor" rows="3" placeholder="Morada do fornecedor"></textarea>
                    </div>

                    <div class="form-full">
                        <label for="observacoesFornecedor">Observações</label>
                        <textarea id="observacoesFornecedor" rows="4" placeholder="Observações adicionais"></textarea>
                    </div>

                    <p id="mensagemFornecedor" class="mensagem-login"></p>

                    <div class="form-botoes">
                        <button type="submit" class="btn-backend">
                            <i class="bi bi-check-circle"></i>
                            Guardar fornecedor
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
