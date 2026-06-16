<?php
$pagina_atual = 'fornecedores';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

<main class="backend-content">

            <div class="backend-topbar">
                <div>
                    <h1>Remover Fornecedor</h1>
                    <p>Confirmação da remoção ou arquivo de fornecedor.</p>
                </div>

                <div class="backend-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </div>
            </div>

            <section class="backend-box text-center">

                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 60px;"></i>
                </div>

                <h2>Tem a certeza que pretende remover este fornecedor?</h2>

                <p>
                    Está prestes a remover o fornecedor <strong>Philips Healthcare</strong>,
                    com o código interno <strong>F001</strong>.
                </p>

                <div class="detalhe-card mt-4 text-start">
                    <div class="detalhe-linha">
                        <span>Código</span>
                        <strong>F001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Fornecedor</span>
                        <strong>Philips Healthcare</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>NIF</span>
                        <strong>501234567</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Tipo</span>
                        <strong>Fabricante</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado</span>
                        <strong><span class="estado ativo">Ativo</span></strong>
                    </div>
                </div>

                <div class="form-botoes justify-content-center">
                    <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-4" id="confirmarRemocaoFornecedor">
                        <i class="bi bi-trash"></i>
                        Confirmar remoção
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

                <p id="mensagemRemocaoFornecedor" class="mensagem-login"></p>

            </section>

        </main>

</div>

<?php include '../includes/footer.php'; ?>
