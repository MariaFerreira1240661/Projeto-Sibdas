<?php

// Identificação da página atual para destacar o item correspondente no menu lateral.
$pagina_atual = 'localizacoes';
// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
include '../includes/header.php';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

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
                l.id,
                l.codigo,
                l.edificio,
                COALESCE(l.numero_pisos, l.piso) AS numero_pisos,
                l.responsavel,
                l.contacto_interno,
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
                l.numero_pisos,
                l.piso,
                l.responsavel,
                l.contacto_interno,
                el.nome
            ORDER BY l.codigo
        ";

        $resultados = $ligacao->query($sql)->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar as localizações. Confirma se já executaste o SQL que adiciona a coluna numero_pisos.';
        $resultados = [];
    }
}

// Fecha/liberta a ligação à base de dados no final do processamento.
$ligacao = null;
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
                <h1>Localizações</h1>
                <p>Gestão das localizações gerais onde os equipamentos podem ser associados.</p>
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
                    <h2>Listagem de Localizações Gerais</h2>
                    <p>Consulta das localizações-base usadas depois no registo de equipamentos.</p>
                </div>

                <?php if (perfil_tem_acesso('localizacoes', 'criar')) : ?>


                
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
<a href="novo.php" class="btn-backend">
                    <i class="bi bi-plus-circle"></i>
                    Nova localização
                </a>


                <?php endif; ?>
            </div>
<?php if (!empty($_SESSION['mensagem_sucesso'])) : ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
                </div>
                <?php unset($_SESSION['mensagem_sucesso']); ?>
            <?php endif; ?>

            <div class="filtros-backend">
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
                    <?= h($erro) ?>
                </p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="sem-resultados" style="display: block;">
                    Não existem localizações registadas.
                </p>
            <?php else : ?>

                <div class="table-responsive">
                    <!-- Tabela de listagem/consulta dos registos deste módulo -->
<table class="tabela-backend" id="tabelaLocalizacoes">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Edifício</th>
                                <th>N.º pisos</th>
                                <th>Responsável</th>
                                <th>Contacto</th>
                                <th>Equipamentos</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($resultados as $localizacao) : ?>
                                <tr>
                                    <td><?= h($localizacao->codigo) ?></td>
                                    <td><?= h($localizacao->edificio) ?></td>
                                    <td><?= h($localizacao->numero_pisos) ?></td>
                                    <td><?= h($localizacao->responsavel) ?></td>
                                    <td><?= h($localizacao->contacto_interno) ?></td>
                                    <td><?= h($localizacao->total_equipamentos) ?></td>
                                    <td>
                                        <span class="estado <?= classe_estado_localizacao($localizacao->estado) ?>">
                                            <?= h($localizacao->estado) ?>
                                        </span>
                                    </td>
                                    <td class="acoes-tabela">
                                        <a href="detalhes.php?id=<?= aes_encrypt($localizacao->id) ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if (!in_array($localizacao->estado, ['Inativa', 'Inativo'], true) && (perfil_tem_acesso('localizacoes', 'editar') || perfil_tem_acesso('localizacoes', 'remover'))) : ?>
                                            <?php if (perfil_tem_acesso('localizacoes', 'editar')) : ?>
                                                <a href="editar.php?id=<?= aes_encrypt($localizacao->id) ?>" data-bs-toggle="tooltip" data-bs-title="Editar localização">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (perfil_tem_acesso('localizacoes', 'remover')) : ?>
                                                <a href="remover.php?id=<?= aes_encrypt($localizacao->id) ?>" data-bs-toggle="tooltip" data-bs-title="Remover localização">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
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

        const filtroEdificio = document.querySelector("#filtroEdificioLocalizacao");
        const filtroEstado = document.querySelector("#filtroEstadoLocalizacao");

        const filtroDataTables = document.querySelector("#filtroDataTablesLocalizacoes");
        const pesquisaDataTables = document.querySelector("#tabelaLocalizacoes_wrapper .dt-search, #tabelaLocalizacoes_wrapper .dataTables_filter");

        if (filtroDataTables && pesquisaDataTables) {
            filtroDataTables.appendChild(pesquisaDataTables);
        }

        function aplicarFiltrosLocalizacoes() {
            tabelaLocalizacoes.column(1).search(filtroEdificio.value);
            tabelaLocalizacoes.column(6).search(filtroEstado.value);
            tabelaLocalizacoes.draw();
        }

        filtroEdificio.addEventListener("change", aplicarFiltrosLocalizacoes);
        filtroEstado.addEventListener("change", aplicarFiltrosLocalizacoes);
    });
</script>
<?php include '../includes/footer.php'; ?>
