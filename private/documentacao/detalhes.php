<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'documentacao';
$erro = '';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function data_pt($data)
{
    return empty($data) ? 'Não aplicável' : date('d/m/Y', strtotime($data));
}

function classe_estado_documento($texto)
{
    $classes = [
        'Válido' => 'ativo',
        'Validado' => 'ativo',
        'Pendente' => 'pendente',
        'Expirado' => 'expirado'
    ];

    return $classes[$texto] ?? '';
}

$id_encriptado = $_GET['id'] ?? null;
$id_documento = validar_id_encriptado($id_encriptado);

if (!$id_documento) {
    header('Location: index.php');
    exit;
}

$documento = null;

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare("
            SELECT
                d.*,
                e.codigo AS equipamento_codigo,
                e.designacao AS equipamento_designacao,
                td.nome AS tipo_documento,
                ed.nome AS estado_documento,
                f.codigo AS fornecedor_codigo,
                f.nome AS fornecedor_nome
            FROM documentos d
            LEFT JOIN equipamentos e ON d.equipamento_id = e.id
            LEFT JOIN tipos_documento td ON d.tipo_documento_id = td.id
            LEFT JOIN estados_documento ed ON d.estado_documento_id = ed.id
            LEFT JOIN fornecedores f ON d.fornecedor_id = f.id
            WHERE d.id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id_documento]);
        $documento = $stmt->fetch();

        if (!$documento) {
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os detalhes do documento.';
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Documento</h1>
                <p>Consulta dos dados reais do documento selecionado.</p>
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
            <?php elseif ($documento) : ?>

                <div class="backend-section-header">
                    <div>
                        <h2><?= h($documento->nome) ?></h2>
                        <p>Código interno: <?= h($documento->codigo) ?></p>
                    </div>

                    <a href="index.php" class="btn-secundario">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>

                <div class="detalhes-grid">
                    <div class="detalhe-card">
                        <h3>Identificação</h3>

                        <div class="detalhe-linha"><span>Código</span><strong><?= h($documento->codigo) ?></strong></div>
                        <div class="detalhe-linha"><span>Nome</span><strong><?= h($documento->nome) ?></strong></div>
                        <div class="detalhe-linha"><span>Tipo</span><strong><?= h($documento->tipo_documento) ?></strong></div>
                        <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado_documento($documento->estado_documento) ?>"><?= h($documento->estado_documento) ?></span></strong></div>
                    </div>

                    <div class="detalhe-card">
                        <h3>Associações</h3>

                        <div class="detalhe-linha"><span>Equipamento</span><strong><?= h($documento->equipamento_codigo ? $documento->equipamento_codigo . ' - ' . $documento->equipamento_designacao : 'Não aplicável') ?></strong></div>
                        <div class="detalhe-linha"><span>Fornecedor</span><strong><?= h($documento->fornecedor_codigo ? $documento->fornecedor_codigo . ' - ' . $documento->fornecedor_nome : 'Sem fornecedor associado') ?></strong></div>
                        <div class="detalhe-linha"><span>Responsável pelo registo</span><strong><?= h($documento->responsavel_registo ?: 'Não indicado') ?></strong></div>
                    </div>

                    <div class="detalhe-card">
                        <h3>Datas e ficheiro</h3>

                        <div class="detalhe-linha"><span>Data do documento</span><strong><?= h(data_pt($documento->data_documento)) ?></strong></div>
                        <div class="detalhe-linha"><span>Data de validade</span><strong><?= h(data_pt($documento->data_validade)) ?></strong></div>
                        <div class="detalhe-linha"><span>Observações</span><strong><?= h($documento->observacoes ?: 'Sem observações') ?></strong></div>
                        <div class="detalhe-linha"><span>Ficheiro</span><strong>
                            <?php if (!empty($documento->ficheiro)) : ?>
                                <a href="<?= h(BASE_URL . '/' . $documento->ficheiro) ?>" target="_blank">Abrir PDF</a>
                            <?php else : ?>
                                Não disponível
                            <?php endif; ?>
                        </strong></div>
                    </div>
                </div>

            <?php endif; ?>
        </section>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
