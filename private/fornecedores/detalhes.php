<?php
$pagina_atual = 'fornecedores';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Fornecedor</h1>
                <p>Consulta visual da entidade fornecedora registada no sistema.</p>
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
                    <h2>MedTech Portugal</h2>
                    <p>Código interno: FOR001</p>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="editar.php" class="btn-backend">
                        <i class="bi bi-pencil-square"></i>
                        Editar fornecedor
                    </a>

                    <a href="index.php" class="btn-secundario">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>
            </div>

            <div class="detalhes-grid">
                <div class="detalhe-card">
                    <h3>Identificação</h3>

                    <div class="detalhe-linha">
                        <span>Código interno</span>
                        <strong>FOR001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Fornecedor</span>
                        <strong>MedTech Portugal</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>NIF</span>
                        <strong>501234567</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado</span>
                        <strong><span class="estado ativo">Ativo</span></strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Contactos gerais</h3>

                    <div class="detalhe-linha">
                        <span>Telefone do fornecedor</span>
                        <strong>+351 912 345 678</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Email do fornecedor</span>
                        <strong>geral@medtech.pt</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Website</span>
                        <strong>https://www.medtech.pt</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Morada</h3>
                    <p>Rua da Tecnologia, 100<br>1000-000 Lisboa</p>
                </div>

                <div class="detalhe-card">
                    <h3>Observações</h3>
                    <p>Fornecedor geral que pode ser associado a equipamentos com diferentes tipos de relação.</p>
                </div>
            </div>
        </section>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
