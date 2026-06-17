<?php
$pagina_atual = 'documentacao';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Remover Documento</h1>
                <p>Confirmação da remoção ou arquivo de documentação associada ao inventário.</p>
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

        <section class="backend-box text-center">

            <div class="mb-4">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 60px;"></i>
            </div>

            <h2>Tem a certeza que pretende remover este documento?</h2>

            <p>
                Está prestes a remover o documento <strong>Manual de Utilizador</strong>,
                com o código interno <strong>D001</strong>.
            </p>

            <div class="detalhe-card mt-4 text-start">
                <div class="detalhe-linha">
                    <span>Código</span>
                    <strong>D001</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Documento</span>
                    <strong>Manual de Utilizador</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Tipo</span>
                    <strong>Manual</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Equipamento associado</span>
                    <strong>Monitor Multiparamétrico</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Ficheiro</span>
                    <strong>manual_monitor.pdf</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Estado</span>
                    <strong><span class="estado ativo">Válido</span></strong>
                </div>
            </div>

            <div class="form-botoes justify-content-center">
                <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-4" id="confirmarRemocaoDocumento">
                    <i class="bi bi-trash"></i>
                    Confirmar remoção
                </button>

                <a href="index.php" class="btn-secundario">
                    Cancelar
                </a>
            </div>

            <p id="mensagemRemocaoDocumento" class="mensagem-login"></p>

        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>