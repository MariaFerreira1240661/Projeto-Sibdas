<?php
$pagina_atual = 'fornecedores';
include '../includes/header.php';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function classe_estado_fornecedor($ativo)
{
    return (int) $ativo === 1 ? 'ativo' : 'inativo';
}

$erro = '';
$resultados = [];

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        $sql = "
            SELECT
                f.id,
                f.codigo,
                f.nome,
                f.nif,
                f.telefone,
                f.email,
                f.website,
                f.ativo,
                COUNT(ef.equipamento_id) AS total_equipamentos
            FROM fornecedores f
            LEFT JOIN equipamento_fornecedores ef
                ON ef.fornecedor_id = f.id
            GROUP BY
                f.id,
                f.codigo,
                f.nome,
                f.nif,
                f.telefone,
                f.email,
                f.website,
                f.ativo
            ORDER BY f.codigo
        ";

        $resultados = $ligacao->query($sql)->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os fornecedores.';
    }
}

$ligacao = null;
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Fornecedores</h1>
                <p>Gestão das entidades fornecedoras gerais associáveis aos equipamentos.</p>
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
                    <h2>Listagem de Fornecedores Gerais</h2>
                    <p>Consulta das entidades fornecedoras registadas no sistema.</p>
                </div>

                <a href="novo.php" class="btn-backend">
                    <i class="bi bi-plus-circle"></i>
                    Novo fornecedor
                </a>
            </div>
<div class="filtros-backend">
                <div>
                    <label for="filtroEstadoFornecedor">Estado</label>
                    <select id="filtroEstadoFornecedor">
                        <option value="">Todos</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>
                </div>

                <div id="filtroDataTablesFornecedores" class="filtro-datatables-backend"></div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="sem-resultados" style="display: block;">
                    <?= h($erro) ?>
                </p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="sem-resultados" style="display: block;">
                    Não existem fornecedores registados.
                </p>
            <?php else : ?>

                <div class="table-responsive">
                    <table class="tabela-backend" id="tabelaFornecedores">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fornecedor</th>
                                <th>NIF</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th>Equipamentos</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($resultados as $fornecedor) : ?>
                                <tr>
                                    <td><?= h($fornecedor->codigo) ?></td>
                                    <td><?= h($fornecedor->nome) ?></td>
                                    <td><?= h($fornecedor->nif) ?></td>
                                    <td><?= h($fornecedor->telefone) ?></td>
                                    <td><?= h($fornecedor->email) ?></td>
                                    <td><?= h($fornecedor->total_equipamentos) ?></td>
                                    <td>
                                        <span class="estado <?= classe_estado_fornecedor($fornecedor->ativo) ?>">
                                            <?= (int) $fornecedor->ativo === 1 ? 'Ativo' : 'Inativo' ?>
                                        </span>
                                    </td>
                                    <td class="acoes-tabela">
                                        <a href="detalhes.php?id=<?= $fornecedor->id ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="editar.php?id=<?= aes_encrypt($fornecedor->id) ?>" data-bs-toggle="tooltip" data-bs-title="Editar fornecedor">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <a href="remover.php?id=<?= $fornecedor->id ?>" data-bs-toggle="tooltip" data-bs-title="Remover fornecedor">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <p class="mt-3">
                Total de fornecedores:
                <strong><?= count($resultados) ?></strong>
            </p>
        </section>

    </main>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabela = document.querySelector("#tabelaFornecedores");

        if (!tabela || typeof DataTable === "undefined") {
            return;
        }

        const tabelaFornecedores = new DataTable(tabela, {
            pageLength: 5,
            pagingType: "full_numbers",
            language: {
                decimal: "",
                emptyTable: "Sem dados disponíveis na tabela.",
                info: "Mostrando _START_ até _END_ de _TOTAL_ registos",
                infoEmpty: "Mostrando 0 até 0 de 0 registos",
                infoFiltered: "(filtrado de _MAX_ registos no total)",
                lengthMenu: "Mostrar _MENU_ registos por página",
                loadingRecords: "A carregar...",
                processing: "A processar...",
                search: "Filtrar:",
                zeroRecords: "Nenhum registo encontrado.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Seguinte",
                    previous: "Anterior"
                },
                aria: {
                    sortAscending: ": ativar para ordenar a coluna de forma crescente",
                    sortDescending: ": ativar para ordenar a coluna de forma decrescente"
                }
            }
        });

        const filtroEstado = document.querySelector("#filtroEstadoFornecedor");
        const filtroDataTables = document.querySelector("#filtroDataTablesFornecedores");
        const pesquisaDataTables = document.querySelector("#tabelaFornecedores_wrapper .dt-search, #tabelaFornecedores_wrapper .dataTables_filter");

        if (filtroDataTables && pesquisaDataTables) {
            filtroDataTables.appendChild(pesquisaDataTables);
        }

        filtroEstado.addEventListener("change", function() {
            tabelaFornecedores.column(6).search(filtroEstado.value);
            tabelaFornecedores.draw();
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
