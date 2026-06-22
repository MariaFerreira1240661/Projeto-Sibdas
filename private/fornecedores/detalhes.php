<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'fornecedores';
$erro = '';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function classe_estado_fornecedor($ativo)
{
    return (int) $ativo === 1 ? 'ativo' : 'inativo';
}

$id_encriptado = $_GET['id'] ?? null;
$id_fornecedor = validar_id_encriptado($id_encriptado);

if (!$id_fornecedor) {
    header('Location: index.php');
    exit;
}

$fornecedor = null;
$equipamentos = [];
$documentos = [];
$contratos = [];
$editar_disponivel_fornecedor = false;

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare("
            SELECT *
            FROM fornecedores
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id_fornecedor]);
        $fornecedor = $stmt->fetch();

        if (!$fornecedor) {
            header('Location: index.php');
            exit;
        }

        $editar_disponivel_fornecedor = ((int) ($fornecedor->ativo ?? 0) === 1);

        $stmt = $ligacao->prepare("
            SELECT
                e.codigo,
                e.designacao,
                trf.nome AS tipo_relacao,
                ef.pessoa_contacto,
                ef.telefone_contacto,
                ef.email_contacto,
                ef.principal
            FROM equipamento_fornecedores ef
            INNER JOIN equipamentos e ON ef.equipamento_id = e.id
            LEFT JOIN tipos_relacao_fornecedor trf ON ef.tipo_relacao_fornecedor_id = trf.id
            WHERE ef.fornecedor_id = :id
            ORDER BY e.codigo
        ");
        $stmt->execute([':id' => $id_fornecedor]);
        $equipamentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare("
            SELECT
                d.codigo,
                d.nome,
                e.codigo AS equipamento_codigo,
                e.designacao AS equipamento_designacao
            FROM documentos d
            LEFT JOIN equipamentos e ON d.equipamento_id = e.id
            WHERE d.fornecedor_id = :id
            ORDER BY d.codigo
        ");
        $stmt->execute([':id' => $id_fornecedor]);
        $documentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare("
            SELECT
                c.codigo,
                e.codigo AS equipamento_codigo,
                e.designacao AS equipamento_designacao,
                tc.nome AS tipo_contrato,
                ec.nome AS estado_contrato
            FROM contratos c
            LEFT JOIN equipamentos e ON c.equipamento_id = e.id
            LEFT JOIN tipos_contrato tc ON c.tipo_contrato_id = tc.id
            LEFT JOIN estados_contrato ec ON c.estado_contrato_id = ec.id
            WHERE c.fornecedor_id = :id
            ORDER BY c.codigo
        ");
        $stmt->execute([':id' => $id_fornecedor]);
        $contratos = $stmt->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os detalhes do fornecedor.';
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Fornecedor</h1>
                <p>Consulta dos dados reais da entidade fornecedora.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    
                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php"><i class="bi bi-box-arrow-right"></i> Terminar sessão</a></li>
                </ul>
            </div>
        </div>

        <section class="backend-box">
            <?php if ($erro) : ?>
                <div class="alert alert-danger"><?= h($erro) ?></div>
            <?php elseif ($fornecedor) : ?>

                <div class="backend-section-header">
                    <div>
                        <h2><?= h($fornecedor->nome) ?></h2>
                        <p>Código interno: <?= h($fornecedor->codigo) ?></p>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <?php if ($editar_disponivel_fornecedor) : ?>
                            <a href="editar.php?id=<?= h($id_encriptado) ?>" class="btn-backend">
                                <i class="bi bi-pencil-square"></i>
                                Editar fornecedor
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

                        <div class="detalhe-linha"><span>Código</span><strong><?= h($fornecedor->codigo) ?></strong></div>
                        <div class="detalhe-linha"><span>Fornecedor</span><strong><?= h($fornecedor->nome) ?></strong></div>
                        <div class="detalhe-linha"><span>NIF</span><strong><?= h($fornecedor->nif) ?></strong></div>
                        <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado_fornecedor($fornecedor->ativo) ?>"><?= (int) $fornecedor->ativo === 1 ? 'Ativo' : 'Inativo' ?></span></strong></div>
                    </div>

                    <div class="detalhe-card">
                        <h3>Contactos</h3>

                        <div class="detalhe-linha"><span>Telefone</span><strong><?= h($fornecedor->telefone) ?></strong></div>
                        <div class="detalhe-linha"><span>Email</span><strong><?= h($fornecedor->email) ?></strong></div>
                        <div class="detalhe-linha"><span>Website</span><strong><?= h($fornecedor->website) ?></strong></div>
                        <div class="detalhe-linha"><span>Morada</span><strong><?= h($fornecedor->morada) ?></strong></div>
                        <div class="detalhe-linha"><span>Observações</span><strong><?= h($fornecedor->observacoes ?: 'Sem observações') ?></strong></div>
                    </div>
                </div>

                <div class="detalhes-grid mt-4">
                    <div class="detalhe-card">
                        <h3>Equipamentos associados</h3>

                        <?php if (empty($equipamentos)) : ?>
                            <p>Este fornecedor ainda não está associado a equipamentos.</p>
                        <?php else : ?>
                            <?php foreach ($equipamentos as $equipamento) : ?>
                                <div class="detalhe-linha">
                                    <span><?= h($equipamento->codigo) ?> - <?= h($equipamento->designacao) ?></span>
                                    <strong><?= h($equipamento->tipo_relacao ?: 'Relação não indicada') ?></strong>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="detalhe-card">
                        <h3>Documentos associados</h3>

                        <?php if (empty($documentos)) : ?>
                            <p>Não existem documentos associados a este fornecedor.</p>
                        <?php else : ?>
                            <?php foreach ($documentos as $documento) : ?>
                                <div class="detalhe-linha">
                                    <span><?= h($documento->codigo) ?> - <?= h($documento->nome) ?></span>
                                    <strong><?= h($documento->equipamento_codigo ? $documento->equipamento_codigo . ' - ' . $documento->equipamento_designacao : 'Sem equipamento') ?></strong>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="detalhe-card">
                        <h3>Contratos associados</h3>

                        <?php if (empty($contratos)) : ?>
                            <p>Não existem contratos associados a este fornecedor.</p>
                        <?php else : ?>
                            <?php foreach ($contratos as $contrato) : ?>
                                <div class="detalhe-linha">
                                    <span><?= h($contrato->codigo) ?> - <?= h($contrato->tipo_contrato) ?></span>
                                    <strong><?= h($contrato->estado_contrato) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </section>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
