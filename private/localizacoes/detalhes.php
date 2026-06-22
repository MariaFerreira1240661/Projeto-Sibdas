<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'localizacoes';
$erro = '';

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
        'Inativa' => 'inativo',
        'Em manutenção' => 'manutencao'
    ];

    return $classes[$texto] ?? '';
}

$id_encriptado = $_GET['id'] ?? null;
$id_localizacao = validar_id_encriptado($id_encriptado);

if (!$id_localizacao) {
    header('Location: index.php');
    exit;
}

$localizacao = null;
$equipamentos = [];
$editar_disponivel_localizacao = false;

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare("
            SELECT
                l.*,
                COALESCE(l.numero_pisos, l.piso) AS numero_pisos_total,
                el.nome AS estado
            FROM localizacoes l
            LEFT JOIN estados_localizacao el ON l.estado_localizacao_id = el.id
            WHERE l.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id_localizacao]);
        $localizacao = $stmt->fetch();

        if (!$localizacao) {
            header('Location: index.php');
            exit;
        }

        $editar_disponivel_localizacao = !in_array($localizacao->estado, ['Inativa', 'Inativo'], true);

        $stmt = $ligacao->prepare("
            SELECT
                e.id,
                e.codigo,
                e.designacao,
                e.localizacao_piso,
                e.localizacao_servico,
                e.localizacao_sala,
                ee.nome AS estado
            FROM equipamentos e
            LEFT JOIN estados_equipamento ee ON e.estado_equipamento_id = ee.id
            WHERE e.localizacao_id = :id
            ORDER BY e.codigo
        ");
        $stmt->execute([':id' => $id_localizacao]);
        $equipamentos = $stmt->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os detalhes da localização.';
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes da Localização</h1>
                <p>Consulta dos dados reais da localização geral.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/index.php"><i class="bi bi-box-arrow-up-right"></i> Sair para o site público</a></li>
                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php"><i class="bi bi-box-arrow-right"></i> Terminar sessão</a></li>
                </ul>
            </div>
        </div>

        <section class="backend-box">
            <?php if ($erro) : ?>
                <div class="alert alert-danger"><?= h($erro) ?></div>
            <?php elseif ($localizacao) : ?>

                <div class="backend-section-header">
                    <div>
                        <h2><?= h($localizacao->edificio) ?></h2>
                        <p>Código interno: <?= h($localizacao->codigo) ?></p>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <?php if ($editar_disponivel_localizacao) : ?>
                            <a href="editar.php?id=<?= h($id_encriptado) ?>" class="btn-backend">
                                <i class="bi bi-pencil-square"></i>
                                Editar localização
                            </a>
                        <?php else : ?>
                            <span class="btn-secundario disabled" aria-disabled="true">
                                <i class="bi bi-lock"></i>
                                Edição indisponível
                            </span>
                        <?php endif; ?>

                        <a href="index.php" class="btn-secundario">
                            <i class="bi bi-arrow-left-circle"></i>
                            Voltar à listagem
                        </a>
                    </div>
                </div>

                <div class="detalhes-grid">
                    <div class="detalhe-card">
                        <h3>Identificação</h3>

                        <div class="detalhe-linha"><span>Código interno</span><strong><?= h($localizacao->codigo) ?></strong></div>
                        <div class="detalhe-linha"><span>Edifício</span><strong><?= h($localizacao->edificio) ?></strong></div>
                        <div class="detalhe-linha"><span>Número de pisos</span><strong><?= h($localizacao->numero_pisos_total) ?></strong></div>
                        <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado_localizacao($localizacao->estado) ?>"><?= h($localizacao->estado) ?></span></strong></div>
                    </div>

                    <div class="detalhe-card">
                        <h3>Responsável</h3>

                        <div class="detalhe-linha"><span>Responsável</span><strong><?= h($localizacao->responsavel) ?></strong></div>
                        <div class="detalhe-linha"><span>Contacto interno</span><strong><?= h($localizacao->contacto_interno) ?></strong></div>
                        <div class="detalhe-linha"><span>Observações</span><strong><?= h($localizacao->observacoes ?: 'Sem observações') ?></strong></div>
                    </div>
                </div>

                <div class="detalhe-card mt-4">
                    <h3>Equipamentos associados</h3>

                    <?php if (empty($equipamentos)) : ?>
                        <p>Não existem equipamentos associados a esta localização.</p>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table tabela-backend">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Piso</th>
                                        <th>Serviço</th>
                                        <th>Sala</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($equipamentos as $equipamento) : ?>
                                        <tr>
                                            <td><?= h($equipamento->codigo) ?></td>
                                            <td><?= h($equipamento->designacao) ?></td>
                                            <td><?= h($equipamento->localizacao_piso) ?></td>
                                            <td><?= h($equipamento->localizacao_servico) ?></td>
                                            <td><?= h($equipamento->localizacao_sala) ?></td>
                                            <td><span class="estado <?= classe_estado_localizacao($equipamento->estado) ?>"><?= h($equipamento->estado) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </section>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
