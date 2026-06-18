<?php
$pagina_atual = 'localizacoes';
include '../includes/header.php';

function classe_estado_localizacao($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Ativa' => 'ativo',
        'Inativo' => 'inativo',
        'Inativa' => 'inativo'
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
                l.id,
                l.codigo,
                l.edificio,
                l.piso,
                l.servico,
                l.sala,
                el.nome AS estado,
                COUNT(e.id) AS total_equipamentos
            FROM localizacoes l
            INNER JOIN estados_localizacao el
                ON l.estado_localizacao_id = el.id
            LEFT JOIN equipamentos e
                ON e.localizacao_id = l.id
                AND e.ativo = 1
            GROUP BY
                l.id,
                l.codigo,
                l.edificio,
                l.piso,
                l.servico,
                l.sala,
                el.nome
            ORDER BY l.codigo
        ";

        $resultados = $ligacao->query($sql)->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar as localizações.';
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
                <h1>Localizações</h1>
                <p>Gestão dos serviços, pisos e salas onde se encontram os equipamentos médicos.</p>
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
                    <h2>Listagem de Localizações</h2>
                    <p>Consulta e gestão das áreas hospitalares associadas aos equipamentos.</p>
                </div>

                <a href="novo.php" class="btn-backend">
                    <i class="bi bi-plus-circle"></i>
                    Nova localização
                </a>
            </div>
            <div class="filtros-backend">
                <div>
                    <label for="filtroServicoLocalizacao">Serviço</label>
                    <select id="filtroServicoLocalizacao">
                        <option value="">Todos</option>
                        <option value="UCI">UCI</option>
                        <option value="Bloco Operatório">Bloco Operatório</option>
                        <option value="Laboratório">Laboratório</option>
                    </select>
                </div>
                <div>
                    <label for="filtroEdificioLocalizacao">Edifício</label>
                    <select id="filtroEdificioLocalizacao">
                        <option value="">Todos</option>
                        <option value="Edifício A">Edifício A</option>
                        <option value="Edifício B">Edifício B</option>
                        <option value="Edifício C">Edifício C</option>
                    </select>
                </div>


                <div>
                    <label for="filtroEstadoLocalizacao">Estado</label>
                    <select id="filtroEstadoLocalizacao">
                        <option value="">Todos</option>
                        <option value="Ativa">Ativa</option>
                        <option value="Inativa">Inativa</option>
                    </select>
                </div>

                <div id="filtroDataTablesLocalizacoes" class="filtro-datatables-backend"></div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="sem-resultados" style="display: block;">
                    <?= htmlspecialchars($erro) ?>
                </p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="sem-resultados" style="display: block;">
                    Não existem localizações registadas.
                </p>
            <?php else : ?>

                <div class="table-responsive">
                    <table class="tabela-backend" id="tabelaLocalizacoes">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Edifício</th>
                                <th>Piso</th>
                                <th>Serviço</th>
                                <th>Sala / Unidade</th>
                                <th>Equipamentos</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($resultados as $localizacao) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($localizacao->codigo) ?></td>
                                    <td><?= htmlspecialchars($localizacao->edificio) ?></td>
                                    <td><?= htmlspecialchars($localizacao->piso) ?></td>
                                    <td><?= htmlspecialchars($localizacao->servico) ?></td>
                                    <td><?= htmlspecialchars($localizacao->sala) ?></td>
                                    <td><?= htmlspecialchars($localizacao->total_equipamentos) ?></td>
                                    <td>
                                        <span class="estado <?= classe_estado_localizacao($localizacao->estado) ?>">
                                            <?= htmlspecialchars($localizacao->estado) ?>
                                        </span>
                                    </td>
                                    <td class="acoes-tabela">
                                        <a href="detalhes.php?id=<?= $localizacao->id ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="editar.php?id=<?= $localizacao->id ?>" data-bs-toggle="tooltip" data-bs-title="Editar localização">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <a href="remover.php?id=<?= $localizacao->id ?>" data-bs-toggle="tooltip" data-bs-title="Remover localização">
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
                Total de localizações:
                <strong><?= count($resultados) ?></strong>
            </p>
        </section>

    </main>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabela = document.querySelector("#tabelaLocalizacoes");

        if (!tabela || typeof DataTable === "undefined") {
            return;
        }

        const tabelaLocalizacoes = new DataTable(tabela, {
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

        const filtroServico = document.querySelector("#filtroServicoLocalizacao");
        const filtroEdificio = document.querySelector("#filtroEdificioLocalizacao");
        const filtroEstado = document.querySelector("#filtroEstadoLocalizacao");


        const filtroDataTables = document.querySelector("#filtroDataTablesLocalizacoes");
        const pesquisaDataTables = document.querySelector("#tabelaLocalizacoes_wrapper .dt-search, #tabelaLocalizacoes_wrapper .dataTables_filter");

        if (filtroDataTables && pesquisaDataTables) {
            filtroDataTables.appendChild(pesquisaDataTables);
        }

        function aplicarFiltrosLocalizacoes() {
            tabelaLocalizacoes.column(3).search(filtroServico.value);
            tabelaLocalizacoes.column(1).search(filtroEdificio.value);
            tabelaLocalizacoes.column(6).search(filtroEstado.value);
            tabelaLocalizacoes.draw();
        }

        filtroServico.addEventListener("change", aplicarFiltrosLocalizacoes);
        filtroEdificio.addEventListener("change", aplicarFiltrosLocalizacoes);
        filtroEstado.addEventListener("change", aplicarFiltrosLocalizacoes);
    });
</script>

<?php include '../includes/footer.php'; ?>