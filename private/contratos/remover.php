<?php
$pagina_atual = 'contratos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

<main class="backend-content">

            <div class="backend-topbar">
                <div>
                    <h1>Remover Contrato</h1>
                    <p>Confirmação da remoção ou arquivo de contrato associado ao inventário.</p>
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

                <h2>Tem a certeza que pretende remover este contrato?</h2>

                <p>
                    Está prestes a remover o contrato <strong>Manutenção preventiva</strong>,
                    com o código interno <strong>C001</strong>.
                </p>

                <div class="detalhe-card mt-4 text-start">
                    <div class="detalhe-linha">
                        <span>Código</span>
                        <strong>C001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Tipo</span>
                        <strong>Manutenção preventiva</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Equipamento associado</span>
                        <strong>Monitor Multiparamétrico</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Entidade responsável</span>
                        <strong>MedTech Portugal</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Data de fim</span>
                        <strong>31/12/2025</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado</span>
                        <strong><span class="estado ativo">Ativo</span></strong>
                    </div>
                </div>

                <div class="form-botoes justify-content-center">
                    <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-4" id="confirmarRemocaoContrato">
                        <i class="bi bi-trash"></i>
                        Confirmar remoção
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

                <p id="mensagemRemocaoContrato" class="mensagem-login"></p>

            </section>

        </main>

</div>

<?php include '../includes/footer.php'; ?>