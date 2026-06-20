<?php
$pagina_atual = 'localizacoes';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes da Localização</h1>
                <p>Consulta visual da localização geral registada no sistema.</p>
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
                    <h2>Edifício Central</h2>
                    <p>Código interno: LOC001</p>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="editar.php" class="btn-backend">
                        <i class="bi bi-pencil-square"></i>
                        Editar localização
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
                        <strong>LOC001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Edifício</span>
                        <strong>Edifício Central</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Número de pisos</span>
                        <strong>8</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado</span>
                        <strong><span class="estado ativo">Ativa</span></strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Responsável</h3>

                    <div class="detalhe-linha">
                        <span>Responsável interno</span>
                        <strong>Maria Costa</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Contacto interno</span>
                        <strong>+351 910 000 000</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Observações</h3>
                    <p>Localização geral usada para associar equipamentos a pisos, serviços e salas específicas.</p>
                </div>
            </div>
        </section>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
