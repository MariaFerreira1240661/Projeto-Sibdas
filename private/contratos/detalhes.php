<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'contratos';
$erro = '';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function data_pt($data)
{
    return empty($data) ? 'Não aplicável' : date('d/m/Y', strtotime($data));
}

function valor_moeda($valor)
{
    if ($valor === null || $valor === '') {
        return 'Não aplicável';
    }

    return number_format((float) $valor, 2, ',', '.') . ' €';
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

$id_encriptado = $_GET['id'] ?? null;
$id_contrato = validar_id_encriptado($id_encriptado);

if (!$id_contrato) {
    header('Location: index.php');
    exit;
}

$contrato = null;

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare("
            SELECT
                c.*,
                e.codigo AS equipamento_codigo,
                e.designacao AS equipamento_designacao,
                f.codigo AS fornecedor_codigo,
                f.nome AS fornecedor_nome,
                tc.nome AS tipo_contrato,
                ec.nome AS estado_contrato,
                d.codigo AS documento_codigo,
                d.nome AS documento_nome,
                d.ficheiro AS documento_ficheiro
            FROM contratos c
            LEFT JOIN equipamentos e ON c.equipamento_id = e.id
            LEFT JOIN fornecedores f ON c.fornecedor_id = f.id
            LEFT JOIN tipos_contrato tc ON c.tipo_contrato_id = tc.id
            LEFT JOIN estados_contrato ec ON c.estado_contrato_id = ec.id
            LEFT JOIN documentos d ON c.documento_id = d.id
            WHERE c.id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id_contrato]);
        $contrato = $stmt->fetch();

        if (!$contrato) {
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os detalhes do contrato.';
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Contrato</h1>
                <p>Consulta dos dados reais do contrato ou garantia selecionada.</p>
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
            <?php elseif ($contrato) : ?>

                <div class="backend-section-header">
                    <div>
                        <h2><?= h($contrato->codigo) ?> - <?= h($contrato->tipo_contrato) ?></h2>
                        <p>Equipamento: <?= h($contrato->equipamento_codigo . ' - ' . $contrato->equipamento_designacao) ?></p>
                    </div>

                    <a href="index.php" class="btn-secundario">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>

                <div class="detalhes-grid">
                    <div class="detalhe-card">
                        <h3>Contrato / Garantia</h3>

                        <div class="detalhe-linha"><span>Código</span><strong><?= h($contrato->codigo) ?></strong></div>
                        <div class="detalhe-linha"><span>Tipo</span><strong><?= h($contrato->tipo_contrato) ?></strong></div>
                        <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado_contrato($contrato->estado_contrato) ?>"><?= h($contrato->estado_contrato) ?></span></strong></div>
                        <div class="detalhe-linha"><span>Periodicidade</span><strong><?= h($contrato->periodicidade) ?></strong></div>
                        <div class="detalhe-linha"><span>Valor</span><strong><?= h(valor_moeda($contrato->valor)) ?></strong></div>
                    </div>

                    <div class="detalhe-card">
                        <h3>Datas</h3>

                        <div class="detalhe-linha"><span>Data de início</span><strong><?= h(data_pt($contrato->data_inicio)) ?></strong></div>
                        <div class="detalhe-linha"><span>Data de fim</span><strong><?= h(data_pt($contrato->data_fim)) ?></strong></div>
                        <div class="detalhe-linha"><span>Observações</span><strong><?= h($contrato->observacoes ?: 'Sem observações') ?></strong></div>
                    </div>

                    <div class="detalhe-card">
                        <h3>Associações</h3>

                        <div class="detalhe-linha"><span>Equipamento</span><strong><?= h($contrato->equipamento_codigo . ' - ' . $contrato->equipamento_designacao) ?></strong></div>
                        <div class="detalhe-linha"><span>Fornecedor</span><strong><?= h($contrato->fornecedor_codigo ? $contrato->fornecedor_codigo . ' - ' . $contrato->fornecedor_nome : 'Não aplicável') ?></strong></div>
                        <div class="detalhe-linha"><span>Documento associado</span><strong>
                            <?php if (!empty($contrato->documento_ficheiro)) : ?>
                                <a href="<?= h(BASE_URL . '/' . $contrato->documento_ficheiro) ?>" target="_blank"><?= h($contrato->documento_codigo . ' - ' . $contrato->documento_nome) ?></a>
                            <?php else : ?>
                                Sem documento associado
                            <?php endif; ?>
                        </strong></div>
                    </div>
                </div>

            <?php endif; ?>
        </section>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
