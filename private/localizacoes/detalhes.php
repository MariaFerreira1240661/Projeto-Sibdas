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
                <p>Ficha detalhada da área hospitalar associada ao inventário.</p>
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
                    <h2>Unidade de Cuidados Intensivos</h2>
                    <p>Código interno: L001</p>
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
                        <strong>L001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Serviço / Localização</span>
                        <strong>UCI</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado da localização</span>
                        <strong><span class="estado ativo">Ativo</span></strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Localização física</h3>

                    <div class="detalhe-linha">
                        <span>Edifício</span>
                        <strong>Edifício Principal</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Piso</span>
                        <strong>2</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Sala / Unidade</span>
                        <strong>UCI Geral</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Responsável</h3>

                    <div class="detalhe-linha">
                        <span>Responsável interno</span>
                        <strong>Enfermeiro responsável</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Contacto interno</span>
                        <strong>2201</strong>
                    </div>
                </div>



            </div>
        </section>

        <section class="backend-box">
            <h2>Equipamentos nesta localização</h2>

            <div class="table-responsive">
                <table class="tabela-backend">
                    <thead>
                        <tr>
                            <th>Código </th>
                            <th>Equipamento</th>
                            <th>Marca / Modelo</th>
                            <th>Estado</th>
                            <th>Criticidade</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>EQ001</td>
                            <td>Monitor Multiparamétrico</td>
                            <td>Philips IntelliVue MP5</td>
                            <td><span class="estado ativo">Ativo</span></td>
                            <td><span class="estado suporte">Suporte de vida</span></td>
                        </tr>

                        <tr>
                            <td>EQ002</td>
                            <td>Ventilador Pulmonar</td>
                            <td>Dräger Evita V500</td>
                            <td><span class="estado manutencao">Em manutenção</span></td>
                            <td><span class="estado suporte">Suporte de vida</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="backend-box">
            <h2>Observações</h2>
            <p>
                Localização associada a equipamentos de elevada criticidade clínica.
                Deve manter registos atualizados para facilitar a rastreabilidade dos equipamentos.
            </p>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>