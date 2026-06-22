<?php
$pagina_atual = 'documentacao';
include '../includes/header.php';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function data_documento_pt($data, $textoSemData = 'Sem validade')
{
    if (empty($data)) {
        return $textoSemData;
    }

    return date('d/m/Y', strtotime($data));
}

function classe_estado_documento($texto)
{
    $classes = [
        'Válido' => 'ativo',
        'Válida' => 'ativo',
        'Pendente' => 'pendente',
        'Expirado' => 'expirado',
        'Expirada' => 'expirado'
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
                d.id,
                d.codigo,
                d.nome,
                d.data_documento,
                d.data_validade,
                d.ficheiro,
                td.nome AS tipo_documento,
                ed.nome AS estado_documento,
                e.designacao AS equipamento,
                f.nome AS fornecedor
            FROM documentos d
            INNER JOIN tipos_documento td
                ON d.tipo_documento_id = td.id
            INNER JOIN estados_documento ed
                ON d.estado_documento_id = ed.id
            INNER JOIN equipamentos e
                ON d.equipamento_id = e.id
            LEFT JOIN fornecedores f
                ON d.fornecedor_id = f.id
            ORDER BY d.codigo
        ";

        $resultados = $ligacao->query($sql)->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os documentos.';
        $resultados = [];
    }
}

$ligacao = null;
$tiposDocumento = [];
$estadosDocumento = [];
$equipamentosDocumento = [];

foreach ($resultados as $documento) {
    $tiposDocumento[$documento->tipo_documento] = $documento->tipo_documento;
    $estadosDocumento[$documento->estado_documento] = $documento->estado_documento;
    $equipamentosDocumento[$documento->equipamento] = $documento->equipamento;
}

sort($tiposDocumento);
sort($estadosDocumento);
sort($equipamentosDocumento);
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Documentação</h1>
                <p>Gestão da documentação técnica e administrativa associada aos equipamentos médicos.</p>
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
                    <h2>Listagem de Documentos</h2>
                    <p>Consulta e gestão da documentação técnica e administrativa associada aos equipamentos.</p>
                </div>
            </div>
            <?php if (!empty($_SESSION['mensagem_sucesso'])) : ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
                </div>
                <?php unset($_SESSION['mensagem_sucesso']); ?>
            <?php endif; ?>

            <div class="filtros-backend">
                <div>
                    <label for="filtroTipoDocumento">Tipo de documento</label>
                    <select id="filtroTipoDocumento">
                        <option value="">Todos</option>
                        <?php foreach ($tiposDocumento as $tipo) : ?>
                            <option value="<?= h($tipo) ?>"><?= h($tipo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="filtroEstadoDocumento">Estado</label>
                    <select id="filtroEstadoDocumento">
                        <option value="">Todos</option>
                        <?php foreach ($estadosDocumento as $estado) : ?>
                            <option value="<?= h($estado) ?>"><?= h($estado) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="filtroEquipamentoDocumento">Equipamento</label>
                    <select id="filtroEquipamentoDocumento">
                        <option value="">Todos</option>
                        <?php foreach ($equipamentosDocumento as $equipamento) : ?>
                            <option value="<?= h($equipamento) ?>"><?= h($equipamento) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="filtroDataTablesDocumentos" class="filtro-datatables-backend"></div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="sem-resultados" style="display: block;">
                    <?= h($erro) ?>
                </p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="sem-resultados" style="display: block;">
                    Não existem documentos registados.
                </p>
            <?php else : ?>

                <div class="table-responsive">
                    <table class="tabela-backend" id="tabelaDocumentos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tipo de documento</th>
                                <th>Nome do documento</th>
                                <th>Equipamento</th>
                                <th>Data do documento</th>
                                <th>Validade</th>
                                <th>Fornecedor associado</th>
                                <th>Estado</th>
                                <th>Ficheiro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($resultados as $documento) : ?>
                                <tr>
                                    <td><?= h($documento->codigo) ?></td>
                                    <td><?= h($documento->tipo_documento) ?></td>
                                    <td><?= h($documento->nome) ?></td>
                                    <td><?= h($documento->equipamento) ?></td>
                                    <td><?= data_documento_pt($documento->data_documento, 'Não registada') ?></td>
                                    <td><?= data_documento_pt($documento->data_validade, 'Sem validade') ?></td>
                                    <td><?= !empty($documento->fornecedor) ? h($documento->fornecedor) : 'Não aplicável' ?></td>
                                    <td>
                                        <span class="estado <?= classe_estado_documento($documento->estado_documento) ?>">
                                            <?= h($documento->estado_documento) ?>
                                        </span>
                                    </td>
                                    <td><?= h($documento->ficheiro) ?></td>
                                    <td class="acoes-tabela">
                                        <a href="detalhes.php?id=<?= aes_encrypt($documento->id) ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <p class="mt-3">
                Total de documentos:
                <strong><?= count($resultados) ?></strong>
            </p>
        </section>

    </main>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabela = document.querySelector("#tabelaDocumentos");

        if (!tabela || typeof DataTable === "undefined") {
            return;
        }

        const tabelaDocumentos = new DataTable(tabela, {
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

        const filtroTipo = document.querySelector("#filtroTipoDocumento");
        const filtroEstado = document.querySelector("#filtroEstadoDocumento");
        const filtroEquipamento = document.querySelector("#filtroEquipamentoDocumento");

        const filtroDataTables = document.querySelector("#filtroDataTablesDocumentos");
        const pesquisaDataTables = document.querySelector("#tabelaDocumentos_wrapper .dt-search, #tabelaDocumentos_wrapper .dataTables_filter");

        if (filtroDataTables && pesquisaDataTables) {
            filtroDataTables.appendChild(pesquisaDataTables);
        }

        function aplicarFiltrosDocumentos() {
            tabelaDocumentos.column(1).search(filtroTipo.value);
            tabelaDocumentos.column(3).search(filtroEquipamento.value);
            tabelaDocumentos.column(7).search(filtroEstado.value);
            tabelaDocumentos.draw();
        }

        filtroTipo.addEventListener("change", aplicarFiltrosDocumentos);
        filtroEquipamento.addEventListener("change", aplicarFiltrosDocumentos);
        filtroEstado.addEventListener("change", aplicarFiltrosDocumentos);
    });
</script>
<?php include '../includes/footer.php'; ?>