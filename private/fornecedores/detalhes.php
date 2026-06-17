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
                <p>Ficha detalhada da entidade associada aos equipamentos médicos.</p>
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
                    <h2>Philips Healthcare</h2>
                    <p>Código interno: F001</p>
                </div>

                <a href="editar.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <div class="detalhes-grid">

                <div class="detalhe-card">
                    <h3>Identificação</h3>

                    <div class="detalhe-linha">
                        <span>Código interno</span>
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


                </div>

                <div class="detalhe-card">
                    <h3>Tipo e contactos</h3>

                    <div class="detalhe-linha">
                        <span>Tipo de fornecedor</span>
                        <strong>Fabricante</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Email</span>
                        <strong>geral@philips-health.pt</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Telefone</span>
                        <strong>+351 222 100 100</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Website</span>
                        <strong>www.philips.pt</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Pessoa de contacto</h3>

                    <div class="detalhe-linha">
                        <span>Nome</span>
                        <strong>Ana Silva</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Telefone</span>
                        <strong>+351 912 000 000</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Email</span>
                        <strong>ana.silva@philips-health.pt</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Morada</h3>

                    <p>
                        Avenida da Saúde, nº 120<br>
                        4100-000 Porto<br>
                        Portugal
                    </p>
                </div>

            </div>
        </section>

        <section class="backend-box">
            <h2>Equipamentos associados</h2>

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

        <section class="backend-box">
            <h2>Observações</h2>
            <p>
                Entidade registada como fabricante de equipamentos de monitorização.
                Poderá estar associada a fornecedores comerciais ou entidades de assistência técnica.
            </p>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>