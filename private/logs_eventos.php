<?php
$pagina_atual = 'logs_eventos';

require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/includes/logs_eventos.php';

redirect_if_not_logged();
redirect_if_no_permission('logs_eventos', 'ver');

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

$ligacao = ligar_bd();
$logs = [];
$erro = '';

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        garantir_tabela_logs($ligacao);

        $stmt = $ligacao->query("
            SELECT
                le.id,
                le.utilizador_id,
                le.tipo_evento,
                le.entidade,
                le.entidade_id,
                le.descricao,
                le.ip,
                le.criado_em,
                u.nome AS utilizador_nome,
                u.email AS utilizador_email
            FROM logs_eventos le
            LEFT JOIN utilizadores u ON u.id = le.utilizador_id
            ORDER BY le.criado_em DESC, le.id DESC
            LIMIT 150
        ");

        $logs = $stmt->fetchAll();
    } catch (PDOException $e) {
        $erro = 'Não foi possível consultar o registo de eventos.';
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="backend-card">
    <div class="backend-card-header">
        <div>
            <h2>Log eventos</h2>
            <p>Todas as autenticações, alterações de dados, exportações e erros ficam concentrados na tabela logs_eventos.</p>
        </div>
    </div>

    <?php if (!empty($erro)) : ?>
        <div class="alert alert-danger"><?= h($erro) ?></div>
    <?php endif; ?>

    <div class="tabela-wrapper">
        <table class="tabela-backend tabela-dados">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data/hora</th>
                    <th>Tipo</th>
                    <th>Entidade</th>
                    <th>Utilizador</th>
                    <th>Descrição</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)) : ?>
                    <tr>
                        <td colspan="7">Ainda não existem eventos registados.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?= h($log->id) ?></td>
                            <td><?= h($log->criado_em) ?></td>
                            <td><span class="badge bg-secondary"><?= h($log->tipo_evento) ?></span></td>
                            <td>
                                <?= h($log->entidade ?? '-') ?>
                                <?php if (!empty($log->entidade_id)) : ?>
                                    <br><small>ID: <?= h($log->entidade_id) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= h($log->utilizador_nome ?? 'Sistema') ?>
                                <?php if (!empty($log->utilizador_email)) : ?>
                                    <br><small><?= h($log->utilizador_email) ?></small>
                                <?php elseif (!empty($log->utilizador_id)) : ?>
                                    <br><small>ID: <?= h($log->utilizador_id) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= h($log->descricao) ?></td>
                            <td><?= h($log->ip ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
