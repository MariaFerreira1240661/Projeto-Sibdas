<?php
$pagina_atual = 'localizacoes';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Remover Localização</h1>
                <p>Confirmação da remoção ou arquivo de uma localização hospitalar.</p>
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

            <h2>Tem a certeza que pretende remover esta localização?</h2>

            <p>
                Está prestes a remover a localização <strong>Unidade de Cuidados Intensivos</strong>,
                com o código interno <strong>L001</strong>.
            </p>

            <div class="detalhe-card mt-4 text-start">
                <div class="detalhe-linha">
                    <span>Código</span>
                    <strong>L001</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Edifício</span>
                    <strong>Edifício Principal</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Piso</span>
                    <strong>2</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Serviço</span>
                    <strong>UCI</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Sala / Unidade</span>
                    <strong>UCI Geral</strong>
                </div>

                <div class="detalhe-linha">
                    <span>Estado</span>
                    <strong><span class="estado ativo">Ativo</span></strong>
                </div>
            </div>

            <div class="form-botoes justify-content-center">
                <button type="button" class="btn btn-danger fw-bold px-4 py-2 rounded-4" id="confirmarRemocaoLocalizacao">
                    <i class="bi bi-trash"></i>
                    Confirmar remoção
                </button>

                <a href="index.php" class="btn-secundario">
                    Cancelar
                </a>
            </div>

            <p id="mensagemRemocaoLocalizacao" class="mensagem-login"></p>

        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>