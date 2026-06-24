<?php

// Identificação da página atual para destacar o item correspondente no menu lateral.
$pagina_atual = 'contratos';
// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
include '../includes/header.php';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function data_contrato_pt($data)
{
    if (empty($data)) {
        return 'Não aplicável';
    }

    return date('d/m/Y', strtotime($data));
}

function classe_estado_contrato($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Ativa' => 'ativo',
        'A terminar' => 'pendente',
        'Pendente' => 'pendente',
        'Expirado' => 'expirado',
        'Expirada' => 'expirado'
    ];

    return $classes[$texto] ?? '';
}

// Variável utilizada para guardar mensagens de erro a apresentar ao utilizador.
$erro = '';
// Array onde são guardados os resultados obtidos a partir da base de dados.
$resultados = [];

// Estabelece a ligação à base de dados através da função centralizada.
$ligacao = ligar_bd();

// Verifica se a ligação à base de dados foi estabelecida corretamente.
if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    // Execução protegida por try/catch para tratar erros de base de dados ou processamento.
try {
        // Consulta SQL utilizada para obter ou manipular dados deste módulo.
$sql = "
            SELECT
                c.id,
                c.codigo,
                c.data_inicio,
                c.data_fim,
                c.periodicidade,
                e.designacao AS equipamento,
                f.nome AS fornecedor,
                tc.nome AS tipo_contrato,
                ec.nome AS estado_contrato
            FROM contratos c
            INNER JOIN equipamentos e
                ON c.equipamento_id = e.id
            INNER JOIN fornecedores f
                ON c.fornecedor_id = f.id
            INNER JOIN tipos_contrato tc
                ON c.tipo_contrato_id = tc.id
            INNER JOIN estados_contrato ec
                ON c.estado_contrato_id = ec.id
            ORDER BY c.codigo
        ";

        $resultados = $ligacao->query($sql)->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os contratos.';
        $resultados = [];
    }
}

// Fecha/liberta a ligação à base de dados no final do processamento.
$ligacao = null;

$tiposContrato = [];
$estadosContrato = [];
$equipamentosContrato = [];

foreach ($resultados as $contrato) {
    $tiposContrato[$contrato->tipo_contrato] = $contrato->tipo_contrato;
    $estadosContrato[$contrato->estado_contrato] = $contrato->estado_contrato;
    $equipamentosContrato[$contrato->equipamento] = $contrato->equipamento;
}

sort($tiposContrato);
sort($estadosContrato);
sort($equipamentosContrato);
// Início da estrutura HTML da área privada.
?>

<div class="backend-layout">

    <!-- Inclusão do menu lateral comum da área privada -->
<?php include '../includes/sidebar.php'; ?>

    <!-- Conteúdo principal da página privada -->
<main class="backend-content">

        <!-- Topbar com título da página e área do utilizador autenticado -->
<div class="backend-topbar">
            <div>
                <h1>Contratos</h1>
                <p>Gestão de garantias e contratos associados aos equipamentos médicos.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span><?php echo htmlspecialchars(nome_utilizador(), ENT_QUOTES, 'UTF-8'); ?></span>
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

        <!-- Caixa principal do módulo, onde são apresentados formulários, tabelas ou detalhes -->
<section class="backend-box">
            <div class="backend-section-header">
                <div>
                    <h2>Listagem de Contratos e Garantias</h2>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="exportar.php?formato=csv" class="btn-secundario">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        Exportar CSV
                    </a>
                    <a href="exportar.php?formato=json" class="btn-secundario">
                        <i class="bi bi-filetype-json"></i>
                        Exportar JSON
                    </a>
                    <a href="exportar.php?formato=pdf" class="btn-secundario">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Exportar PDF
                    </a>
                </div>

                    <p>Consulta e gestão de garantias e contratos associados aos equipamentos médicos.</p>
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
                    <label for="filtroTipoContrato">Tipo de contrato</label>
                    <select id="filtroTipoContrato">
                        <option value="">Todos</option>
                        <?php foreach ($tiposContrato as $tipo) : ?>
                            <option value="<?= h($tipo) ?>"><?= h($tipo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="filtroEstadoContrato">Estado</label>
                    <select id="filtroEstadoContrato">
                        <option value="">Todos</option>
                        <?php foreach ($estadosContrato as $estado) : ?>
                            <option value="<?= h($estado) ?>"><?= h($estado) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="filtroEquipamentoContrato">Equipamento</label>
                    <select id="filtroEquipamentoContrato">
                        <option value="">Todos</option>
                        <?php foreach ($equipamentosContrato as $equipamento) : ?>
                            <option value="<?= h($equipamento) ?>"><?= h($equipamento) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="filtroDataTablesContratos" class="filtro-datatables-backend"></div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="sem-resultados" style="display: block;">
                    <?= h($erro) ?>
                </p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="sem-resultados" style="display: block;">
                    Não existem contratos registados.
                </p>
            <?php else : ?>

                <div class="table-responsive">
                    <!-- Tabela de listagem/consulta dos registos deste módulo -->
<table class="tabela-backend" id="tabelaContratos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Equipamento</th>
                                <th>Fornecedor</th>
                                <th>Tipo</th>
                                <th>Data de início</th>
                                <th>Data de fim</th>
                                <th>Periodicidade</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($resultados as $contrato) : ?>
                                <tr>
                                    <td><?= h($contrato->codigo) ?></td>
                                    <td><?= h($contrato->equipamento) ?></td>
                                    <td><?= h($contrato->fornecedor) ?></td>
                                    <td><?= h($contrato->tipo_contrato) ?></td>
                                    <td><?= data_contrato_pt($contrato->data_inicio) ?></td>
                                    <td><?= data_contrato_pt($contrato->data_fim) ?></td>
                                    <td><?= !empty($contrato->periodicidade) ? h($contrato->periodicidade) : 'Não aplicável' ?></td>
                                    <td>
                                        <span class="estado <?= classe_estado_contrato($contrato->estado_contrato) ?>">
                                            <?= h($contrato->estado_contrato) ?>
                                        </span>
                                    </td>
                                    <td class="acoes-tabela">
                                        <a href="detalhes.php?id=<?= aes_encrypt($contrato->id) ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
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
                Total de contratos:
                <strong><?= count($resultados) ?></strong>
            </p>
        </section>

    </main>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabela = document.querySelector("#tabelaContratos");

        if (!tabela || typeof DataTable === "undefined") {
            return;
        }

        const tabelaContratos = new DataTable(tabela, {
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

        const filtroTipo = document.querySelector("#filtroTipoContrato");
        const filtroEstado = document.querySelector("#filtroEstadoContrato");
        const filtroEquipamento = document.querySelector("#filtroEquipamentoContrato");

        const filtroDataTables = document.querySelector("#filtroDataTablesContratos");
        const pesquisaDataTables = document.querySelector("#tabelaContratos_wrapper .dt-search, #tabelaContratos_wrapper .dataTables_filter");

        if (filtroDataTables && pesquisaDataTables) {
            filtroDataTables.appendChild(pesquisaDataTables);
        }

        function aplicarFiltrosContratos() {
            tabelaContratos.column(3).search(filtroTipo.value);
            tabelaContratos.column(1).search(filtroEquipamento.value);
            tabelaContratos.column(7).search(filtroEstado.value);
            tabelaContratos.draw();
        }

        filtroTipo.addEventListener("change", aplicarFiltrosContratos);
        filtroEquipamento.addEventListener("change", aplicarFiltrosContratos);
        filtroEstado.addEventListener("change", aplicarFiltrosContratos);
    });
</script>
<?php include '../includes/footer.php'; ?>