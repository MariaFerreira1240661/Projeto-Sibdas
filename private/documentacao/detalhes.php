<?php
$pagina_atual = 'documentacao';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Documento</h1>
                <p>Ficha detalhada da documentação associada ao inventário hospitalar.</p>
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
                    <h2>Manual de Utilizador</h2>
                    <p>Código interno: D001</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <div class="detalhes-grid">

                <div class="detalhe-card">
                    <h3>Identificação</h3>

                    <div class="detalhe-linha">
                        <span>Código</span>
                        <strong>D001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Nome do documento</span>
                        <strong>Manual de Utilizador</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Tipo</span>
                        <strong>Manual</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado</span>
                        <strong><span class="estado ativo">Válido</span></strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Associação</h3>

                    <div class="detalhe-linha">
                        <span>Equipamento associado</span>
                        <strong>Monitor Multiparamétrico</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Código do equipamento</span>
                        <strong>EQ001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Fornecedor associado</span>
                        <strong>Philips Healthcare</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Datas</h3>

                    <div class="detalhe-linha">
                        <span>Data do documento</span>
                        <strong>12/02/2025</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Data de validade</span>
                        <strong>Sem validade</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Data de registo</span>
                        <strong>15/02/2025</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Ficheiro</h3>

                    <div class="detalhe-linha">
                        <span>Nome do ficheiro</span>
                        <strong>manual_monitor.pdf</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Localização</span>
                        <strong>docs/equipamentos/</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Responsável</span>
                        <strong>Administrador</strong>
                    </div>
                </div>

            </div>
        </section>



        <section class="backend-box">
            <h2>Equipamento relacionado</h2>

            <div class="table-responsive">
                <table class="tabela-backend">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Equipamento</th>
                            <th>Marca / Modelo</th>
                            <th>Localização</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>EQ001</td>
                            <td>Monitor Multiparamétrico</td>
                            <td>Philips IntelliVue MP5</td>
                            <td>UCI</td>
                            <td><span class="estado ativo">Ativo</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>