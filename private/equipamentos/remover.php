<?php
require_once __DIR__ . '/../includes/funcoes.php';

redirect_if_not_logged();

$pagina_atual = 'equipamentos';
$erro = '';
$removido_com_sucesso = (($_GET['removido'] ?? '') === '1');

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

function classe_estado_remover($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Ativa' => 'ativo',
        'Válido' => 'ativo',
        'Válida' => 'ativo',
        'Inativo' => 'inativo',
        'Inativa' => 'inativo',
        'Expirado' => 'expirado',
        'Expirada' => 'expirado',
        'Em manutenção' => 'manutencao',
        'Pendente' => 'pendente'
    ];

    return $classes[$texto] ?? '';
}

$id_encriptado = $_GET['id'] ?? null;
$id_registo = validar_id_encriptado($id_encriptado);

if (!$id_registo) {
    header('Location: index.php');
    exit;
}

$registo = null;

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare("SELECT
                e.id,
                e.codigo,
                e.designacao,
                e.marca,
                e.modelo,
                e.numero_serie,
                ee.nome AS estado,
                l.codigo AS localizacao_codigo,
                l.edificio AS localizacao_edificio,
                e.localizacao_servico,
                e.localizacao_sala
            FROM equipamentos e
            LEFT JOIN estados_equipamento ee ON e.estado_equipamento_id = ee.id
            LEFT JOIN localizacoes l ON e.localizacao_id = l.id
            WHERE e.id = :id
            LIMIT 1");
        $stmt->execute([':id' => $id_registo]);
        $registo = $stmt->fetch();

        if (!$registo) {
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar o registo.';
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Remover Equipamento</h1>
                <p>Confirmação da desativação do equipamento médico.</p>
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

        <section class="backend-box text-center">

            <?php if ($erro) : ?>
                <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
            <?php elseif ($registo) : ?>

                <?php if ($removido_com_sucesso) : ?>
                    <div class="alert alert-success text-start" role="alert">
                        Registo removido/desativado com sucesso.
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 60px;"></i>
                </div>

                <?php if ($removido_com_sucesso) : ?>
                    <h2>Registo removido/desativado</h2>

                    <p>
                        O registo foi atualizado com sucesso e ficou arquivado/desativado na base de dados.
                    </p>
                <?php else : ?>
                    <h2>Tem a certeza que pretende remover este registo?</h2>

                    <p>
                        Esta ação não apaga definitivamente da base de dados. O registo será desativado/arquivado, mantendo o histórico no sistema.
                    </p>
                <?php endif; ?>

                <div class="detalhe-card mt-4 text-start">
                    <div class="detalhe-linha">
                        <span>Código interno</span>
                        <strong><?= h($registo->codigo) ?></strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Equipamento</span>
                        <strong><?= h($registo->designacao) ?></strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Marca / Modelo</span>
                        <strong><?= h(trim($registo->marca . ' ' . $registo->modelo)) ?></strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>N.º série</span>
                        <strong><?= h($registo->numero_serie) ?></strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Localização</span>
                        <strong><?= h($registo->localizacao_codigo . ' - ' . $registo->localizacao_edificio . ' / ' . $registo->localizacao_servico . ' / ' . $registo->localizacao_sala) ?></strong>
                    </div>

                    <div class="detalhe-linha">
                        <span>Estado atual</span>
                        <strong><span class="estado <?= classe_estado_remover($registo->estado) ?>"><?= h($registo->estado) ?></span></strong>
                    </div>
                </div>

                <div class="form-botoes justify-content-center mt-4">
                    <?php if ($removido_com_sucesso) : ?>
                        <a href="index.php" class="btn-backend">
                            <i class="bi bi-arrow-left-circle"></i>
                            Voltar à listagem
                        </a>
                    <?php else : ?>
                        <a href="confirmar_remover.php?id=<?= h($id_encriptado) ?>" class="btn btn-danger fw-bold px-4 py-2 rounded-4">
                            <i class="bi bi-check-circle"></i>
                            Confirmar remoção
                        </a>

                        <a href="index.php" class="btn-secundario">
                            Cancelar
                        </a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>
