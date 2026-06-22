<?php
$pagina_atual = 'equipamentos';
include '../includes/header.php';

function classe_filtro($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Em manutenção' => 'manutencao',
        'Inativo' => 'inativo',
        'Em calibração' => 'calibracao',

        'Monitorização' => 'monitorizacao',
        'Suporte de vida' => 'suporte',
        'Terapia' => 'terapia',
        'Diagnóstico' => 'diagnostico',

        'Alta' => 'alta',
        'Média' => 'media',
        'Baixa' => 'baixa'
    ];

    return $classes[$texto] ?? '';
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
                e.id,
                e.codigo,
                e.designacao,
                e.marca,
                e.modelo,
                e.numero_serie,
                e.ativo,
                ce.nome AS categoria,
                CONCAT(
                    l.codigo,
                    ' - ',
                    l.edificio,
                    CASE
                        WHEN e.localizacao_servico IS NOT NULL AND e.localizacao_servico <> ''
                            THEN CONCAT(' / ', e.localizacao_servico)
                        ELSE ''
                    END
                ) AS localizacao,
                ee.nome AS estado,
                c.nome AS criticidade
            FROM equipamentos e
            INNER JOIN categorias_equipamento ce
                ON e.categoria_equipamento_id = ce.id
            INNER JOIN localizacoes l
                ON e.localizacao_id = l.id
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            INNER JOIN criticidades c
                ON e.criticidade_id = c.id
            ORDER BY e.codigo
        ";

        $resultados = $ligacao->query($sql)->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os equipamentos.';
        $resultados = [];
    }
}

$ligacao = null;
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Equipamentos</h1>
                <p>Gestão dos equipamentos médicos registados no inventário hospitalar.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    

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
                    <h2>Listagem de Equipamentos</h2>
                    <p>Consulta, pesquisa e gestão dos equipamentos médicos registados.</p>
                </div>

                <a href="novo.php" class="btn-backend">
                    <i class="bi bi-plus-circle"></i>
                    Novo equipamento
                </a>
            </div>

            <?php if (!empty($_SESSION['mensagem_sucesso'])) : ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
                </div>
                <?php unset($_SESSION['mensagem_sucesso']); ?>
            <?php endif; ?>

            <div class="filtros-backend">
                <div>
                    <label for="filtroEstado">Estado</label>
                    <select id="filtroEstado">
                        <option value="">Todos</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Em manutenção">Em manutenção</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Em calibração">Em calibração</option>
                    </select>
                </div>

                <div>
                    <label for="filtroCategoria">Categoria</label>
                    <select id="filtroCategoria">
                        <option value="">Todas</option>
                        <option value="Monitorização">Monitorização</option>
                        <option value="Suporte de vida">Suporte de vida</option>
                        <option value="Terapia">Terapia</option>
                        <option value="Diagnóstico">Diagnóstico</option>
                    </select>
                </div>

                <div>
                    <label for="filtroCriticidade">Criticidade</label>
                    <select id="filtroCriticidade">
                        <option value="">Todas</option>
                        <option value="Baixa">Baixa</option>
                        <option value="Média">Média</option>
                        <option value="Alta">Alta</option>
                        <option value="Suporte de vida">Suporte de vida</option>
                    </select>
                </div>
                <div id="filtroDataTablesEquipamentos" class="filtro-datatables-backend"></div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="sem-resultados" style="display: block;">
                    <?= htmlspecialchars($erro) ?>
                </p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="sem-resultados" style="display: block;">
                    Não existem equipamentos registados.
                </p>
            <?php else : ?>

                <div class="table-responsive">
                    <table class="tabela-backend" id="tabelaEquipamentos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Equipamento</th>
                                <th>Marca / Modelo</th>
                                <th>N.º Série</th>
                                <th>Categoria</th>
                                <th>Localização</th>
                                <th>Estado</th>
                                <th>Criticidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($resultados as $equipamento) : ?>
                                <tr
                                    data-estado="<?= classe_filtro($equipamento->estado) ?>"
                                    data-categoria="<?= classe_filtro($equipamento->categoria) ?>"
                                    data-criticidade="<?= classe_filtro($equipamento->criticidade) ?>">
                                    <td><?= htmlspecialchars($equipamento->codigo) ?></td>
                                    <td><?= htmlspecialchars($equipamento->designacao) ?></td>
                                    <td>
                                        <?= htmlspecialchars($equipamento->marca) ?>
                                        <?= htmlspecialchars($equipamento->modelo) ?>
                                    </td>
                                    <td><?= htmlspecialchars($equipamento->numero_serie) ?></td>
                                    <td><?= htmlspecialchars($equipamento->categoria) ?></td>
                                    <td><?= htmlspecialchars($equipamento->localizacao) ?></td>
                                    <td>
                                        <span class="estado <?= classe_filtro($equipamento->estado) ?>">
                                            <?= htmlspecialchars($equipamento->estado) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="estado <?= classe_filtro($equipamento->criticidade) ?>">
                                            <?= htmlspecialchars($equipamento->criticidade) ?>
                                        </span>
                                    </td>
                                    <td class="acoes-tabela">
                                        <a href="detalhes.php?id=<?= aes_encrypt($equipamento->id) ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ((int) $equipamento->ativo === 1) : ?>
                                            <a href="editar.php?id=<?= aes_encrypt($equipamento->id) ?>" data-bs-toggle="tooltip" data-bs-title="Editar equipamento">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <a href="remover.php?id=<?= aes_encrypt($equipamento->id) ?>" data-bs-toggle="tooltip" data-bs-title="Remover equipamento">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <p class="mt-3">
                Total de equipamentos:
                <strong><?= count($resultados) ?></strong>
            </p>

            <p id="semResultados" class="sem-resultados">
                Não foram encontrados equipamentos com os filtros selecionados.
            </p>
        </section>

    </main>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabela = document.querySelector("#tabelaEquipamentos");

        if (!tabela || typeof DataTable === "undefined") {
            return;
        }

        const tabelaEquipamentos = new DataTable(tabela, {
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

        const filtroEstado = document.querySelector("#filtroEstado");
        const filtroCategoria = document.querySelector("#filtroCategoria");
        const filtroCriticidade = document.querySelector("#filtroCriticidade");
        const filtroDataTables = document.querySelector("#filtroDataTablesEquipamentos");
        const pesquisaDataTables = document.querySelector("#tabelaEquipamentos_wrapper .dt-search, #tabelaEquipamentos_wrapper .dataTables_filter");

        if (filtroDataTables && pesquisaDataTables) {
            filtroDataTables.appendChild(pesquisaDataTables);
        }

        function aplicarFiltrosEquipamentos() {
            tabelaEquipamentos.column(6).search(filtroEstado.value);
            tabelaEquipamentos.column(4).search(filtroCategoria.value);
            tabelaEquipamentos.column(7).search(filtroCriticidade.value);
            tabelaEquipamentos.draw();
        }

        function aplicarFiltrosEquipamentos() {
            tabelaEquipamentos.column(6).search(filtroEstado.value);
            tabelaEquipamentos.column(4).search(filtroCategoria.value);
            tabelaEquipamentos.column(7).search(filtroCriticidade.value);
            tabelaEquipamentos.draw();
        }

        filtroEstado.addEventListener("change", aplicarFiltrosEquipamentos);
        filtroCategoria.addEventListener("change", aplicarFiltrosEquipamentos);
        filtroCriticidade.addEventListener("change", aplicarFiltrosEquipamentos);
    });
</script>
<?php include '../includes/footer.php'; ?>