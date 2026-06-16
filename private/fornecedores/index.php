<?php
$pagina_atual = 'fornecedores';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

<main class="backend-content">

            <div class="backend-topbar">
                <div>
                    <h1>Fornecedores</h1>
                    <p>Gestão das entidades associadas aos equipamentos médicos.</p>
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
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/login.php">
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
                        <h2>Listagem de Fornecedores</h2>
                        <p>Consulta e gestão das entidades associadas aos equipamentos médicos.</p>
                    </div>

                    <a href="novo.php" class="btn-backend">
                        <i class="bi bi-plus-circle"></i>
                        Novo fornecedor
                    </a>
                </div>

                <div class="filtros-backend">
                    <div>
                        <label for="pesquisaFornecedor">Pesquisar</label>
                        <input type="text" id="pesquisaFornecedor" placeholder="Nome, NIF, email ou telefone">
                    </div>

                    <div>
                        <label for="filtroTipoFornecedor">Tipo de fornecedor</label>
                        <select id="filtroTipoFornecedor">
                            <option value="">Todos</option>
                            <option value="fabricante">Fabricante</option>
                            <option value="distribuidor">Distribuidor</option>
                            <option value="assistencia">Assistência técnica</option>
                            <option value="consumiveis">Consumíveis</option>
                        </select>
                    </div>

                    <div>
                        <label for="filtroEstadoFornecedor">Estado</label>
                        <select id="filtroEstadoFornecedor">
                            <option value="">Todos</option>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="tabela-backend" id="tabelaFornecedores">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fornecedor</th>
                                <th>NIF</th>
                                <th>Tipo</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr data-tipo="fabricante" data-estado="ativo">
                                <td>F001</td>
                                <td>Philips Healthcare</td>
                                <td>501234567</td>
                                <td>Fabricante</td>
                                <td>geral@philips-health.pt</td>
                                <td>+351 222 100 100</td>
                                <td><span class="estado ativo">Ativo</span></td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                
                                    <a href="editar.php" data-bs-toggle="tooltip" data-bs-title="Editar equipamento">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <tr data-tipo="distribuidor" data-estado="ativo">
                                <td>F002</td>
                                <td>MedTech Portugal</td>
                                <td>502345678</td>
                                <td>Distribuidor</td>
                                <td>contacto@medtech.pt</td>
                                <td>+351 222 200 200</td>
                                <td><span class="estado ativo">Ativo</span></td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                
                                    <a href="editar.php" data-bs-toggle="tooltip" data-bs-title="Editar equipamento">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <tr data-tipo="assistencia" data-estado="ativo">
                                <td>F003</td>
                                <td>BioSupport Systems</td>
                                <td>503456789</td>
                                <td>Assistência técnica</td>
                                <td>assistencia@biosupport.pt</td>
                                <td>+351 222 300 300</td>
                                <td><span class="estado ativo">Ativo</span></td>
                                <td class="acoes-tabela">
                                     <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                         <i class="bi bi-eye"></i>
                                    </a>
                            
                                    <a href="editar.php" data-bs-toggle="tooltip" data-bs-title="Editar equipamento">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                            
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                         <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <tr data-tipo="consumiveis" data-estado="inativo">
                                <td>F004</td>
                                <td>InfuCare Medical</td>
                                <td>504567890</td>
                                <td>Consumíveis</td>
                                <td>info@infucare.pt</td>
                                <td>+351 222 400 400</td>
                                <td><span class="estado inativo">Inativo</span></td>
                                <td class="acoes-tabela">
                                    <a href="detalhes.php" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                
                                    <a href="editar.php" data-bs-toggle="tooltip" data-bs-title="Editar equipamento">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                
                                    <a href="remover.php" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p id="semResultadosFornecedores" class="sem-resultados">
                    Não foram encontrados fornecedores com os filtros selecionados.
                </p>
            </section>

        </main>

</div>

<?php include '../includes/footer.php'; ?>
