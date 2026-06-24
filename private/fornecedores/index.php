<?php

// Identificação da página atual para destacar o item correspondente no menu lateral.
$pagina_atual = 'fornecedores';
// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
include '../includes/header.php';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function classe_estado_fornecedor($ativo)
{
    return (int) $ativo === 1 ? 'ativo' : 'inativo';
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
                <h1>Fornecedores</h1>
                <p>Gestão das entidades fornecedoras gerais associáveis aos equipamentos.</p>
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
                    <h2>Listagem de Fornecedores Gerais</h2>
                    <p>Consulta das entidades fornecedoras registadas no sistema.</p>
                </div>

                <?php if (perfil_tem_acesso('fornecedores', 'criar')) : ?>


                
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
                    Novo fornecedor
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
                    <!-- Tabela de listagem/consulta dos registos deste módulo -->
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
                                        <a href="detalhes.php?id=<?= aes_encrypt($fornecedor->id) ?>" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ((int) $fornecedor->ativo === 1 && (perfil_tem_acesso('fornecedores', 'editar') || perfil_tem_acesso('fornecedores', 'remover'))) : ?>
                                            <?php if (perfil_tem_acesso('fornecedores', 'editar')) : ?>
                                                <a href="editar.php?id=<?= aes_encrypt($fornecedor->id) ?>" data-bs-toggle="tooltip" data-bs-title="Editar fornecedor">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (perfil_tem_acesso('fornecedores', 'remover')) : ?>
                                                <a href="remover.php?id=<?= aes_encrypt($fornecedor->id) ?>" data-bs-toggle="tooltip" data-bs-title="Remover fornecedor">
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
