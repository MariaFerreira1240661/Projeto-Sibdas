<?php
$pagina_atual = 'equipamentos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Remover Equipamento</h1>
                <p>Confirmação da remoção ou arquivo de equipamento médico.</p>
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

            <h2>Tem a certeza que pretende remover este equipamento?</h2>

            <p>
                Está prestes a remover o equipamento <strong>Monitor Multiparamétrico</strong>,
                com o código interno <strong>EQ001</strong>.
            </p>

            <div class="detalhe-card mt-4 text-start">
                <div class="detalhe-linha">
                    <span>Código interno</span>
                    <strong>EQ001</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Equipamento</span>
                    <strong>Monitor Multiparamétrico</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Marca / Modelo</span>
                    <strong>Philips IntelliVue MP5</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Localização</span>
                    <strong>UCI</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Estado atual</span>
                    <strong><span class="estado ativo">Ativo</span></strong>
                </div>
            </div>

            <div class="form-botoes justify-content-center">
                <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-4" id="confirmarRemocao">
                    <i class="bi bi-trash"></i>
                    Confirmar remoção
                </button>

                <a href="index.php" class="btn-secundario">
                    Cancelar
                </a>
            </div>

            <p id="mensagemRemocao" class="mensagem-login"></p>

        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>