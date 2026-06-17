<?php
$pagina_atual = 'contratos';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Contrato</h1>
                <p>Ficha detalhada de garantia ou contrato associado ao inventário.</p>
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
                    <h2>Manutenção Preventiva</h2>
                    <p>Código interno: C001</p>
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
                        <span>Código</span>
                        <strong>C001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Tipo</span>
                        <strong>Manutenção preventiva</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado</span>
                        <strong><span class="estado ativo">Ativo</span></strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Periodicidade</span>
                        <strong>Anual</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Equipamento e Entidade</h3>

                    <div class="detalhe-linha">
                        <span>Equipamento associado</span>
                        <strong>Monitor Multiparamétrico</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Código do equipamento</span>
                        <strong>EQ001</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Entidade responsável</span>
                        <strong>MedTech Portugal</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Contacto</span>
                        <strong>contacto@medtech.pt</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Datas</h3>

                    <div class="detalhe-linha">
                        <span>Data de início</span>
                        <strong>01/01/2025</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Data de fim</span>
                        <strong>31/12/2025</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Situação</span>
                        <strong>Dentro do prazo</strong>
                    </div>
                </div>

                <div class="detalhe-card">
                    <h3>Informação Administrativa</h3>

                    <div class="detalhe-linha">
                        <span>Valor associado</span>
                        <strong>1 200 €</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Documento associado</span>
                        <strong>contrato_monitor.pdf</strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Responsável interno</span>
                        <strong>Administrador</strong>
                    </div>
                </div>

            </div>
        </section>

        <section class="backend-box">
            <h2>Observações</h2>
            <p>
                Contrato de manutenção preventiva associado ao monitor multiparamétrico.
                Deve ser revisto antes da data de fim para garantir a continuidade do acompanhamento técnico.
            </p>
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
                            <th>Criticidade</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>EQ001</td>
                            <td>Monitor Multiparamétrico</td>
                            <td>Philips IntelliVue MP5</td>
                            <td>UCI</td>
                            <td><span class="estado ativo">Ativo</span></td>
                            <td><span class="estado suporte">Suporte de vida</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>