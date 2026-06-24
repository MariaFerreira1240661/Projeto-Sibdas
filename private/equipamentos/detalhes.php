<?php

// Importação de ficheiros necessários para reutilizar configurações, funções e componentes comuns.
require_once __DIR__ . '/../includes/funcoes.php';

// Proteção da página: impede acesso sem autenticação.
redirect_if_not_logged();

// Identificação da página atual para destacar o item correspondente no menu lateral.
$pagina_atual = 'equipamentos';
// Variável utilizada para guardar mensagens de erro a apresentar ao utilizador.
$erro = '';

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function data_pt($data)
{
    if (empty($data)) {
        return 'Não aplicável';
    }

    return date('d/m/Y', strtotime($data));
}

function valor_moeda($valor)
{
    if ($valor === null || $valor === '') {
        return 'Não aplicável';
    }

    return number_format((float) $valor, 2, ',', '.') . ' €';
}

function sim_nao($valor)
{
    return !empty($valor) ? 'Sim' : 'Não';
}

function classe_estado($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Ativa' => 'ativo',
        'Em manutenção' => 'manutencao',
        'Inativo' => 'inativo',
        'Em calibração' => 'calibracao',
        'Válido' => 'ativo',
        'Expirado' => 'expirado'
    ];

    return $classes[$texto] ?? '';
}

$id_encriptado = $_GET['id'] ?? null;
$id_equipamento = validar_id_encriptado($id_encriptado);

if (!$id_equipamento) {
    // Redirecionamento do utilizador após a operação ou validação.
header('Location: index.php');
    exit;
}

$equipamento = null;
$fornecedores = [];
$documentos = [];
$contratos = [];
$editar_disponivel_equipamento = false;

// Estabelece a ligação à base de dados através da função centralizada.
$ligacao = ligar_bd();

// Verifica se a ligação à base de dados foi estabelecida corretamente.
if (!$ligacao) {
    $erro = 'Aconteceu um erro na ligação à base de dados.';
} else {
    // Execução protegida por try/catch para tratar erros de base de dados ou processamento.
try {
        // Preparação da consulta SQL com parâmetros, melhorando segurança e organização.
$stmt = $ligacao->prepare("
            SELECT
                e.*,
                ce.nome AS categoria,
                ee.nome AS estado,
                cr.nome AS criticidade,
                pm.nome AS prioridade,
                te.nome AS tipo_entrada,
                l.codigo AS localizacao_codigo,
                l.edificio AS localizacao_edificio,
                COALESCE(l.numero_pisos, l.piso) AS localizacao_numero_pisos,
                l.responsavel AS localizacao_responsavel,
                l.contacto_interno AS localizacao_contacto,
                ep.codigo AS equipamento_principal_codigo,
                ep.designacao AS equipamento_principal_designacao
            FROM equipamentos e
            LEFT JOIN categorias_equipamento ce ON e.categoria_equipamento_id = ce.id
            LEFT JOIN estados_equipamento ee ON e.estado_equipamento_id = ee.id
            LEFT JOIN criticidades cr ON e.criticidade_id = cr.id
            LEFT JOIN prioridades_manutencao pm ON e.prioridade_manutencao_id = pm.id
            LEFT JOIN tipos_entrada te ON e.tipo_entrada_id = te.id
            LEFT JOIN localizacoes l ON e.localizacao_id = l.id
            LEFT JOIN equipamentos ep ON e.equipamento_principal_id = ep.id
            WHERE e.id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id_equipamento]);
        $equipamento = $stmt->fetch();

        if (!$equipamento) {
            header('Location: index.php');
            exit;
        }

        $editar_disponivel_equipamento = ((int) ($equipamento->ativo ?? 0) === 1);

        $stmt = $ligacao->prepare("
            SELECT
                ef.*,
                f.codigo AS fornecedor_codigo,
                f.nome AS fornecedor_nome,
                f.telefone AS fornecedor_telefone,
                f.email AS fornecedor_email,
                trf.nome AS tipo_relacao
            FROM equipamento_fornecedores ef
            INNER JOIN fornecedores f ON ef.fornecedor_id = f.id
            LEFT JOIN tipos_relacao_fornecedor trf ON ef.tipo_relacao_fornecedor_id = trf.id
            WHERE ef.equipamento_id = :id
            ORDER BY ef.principal DESC, f.codigo
        ");
        $stmt->execute([':id' => $id_equipamento]);
        $fornecedores = $stmt->fetchAll();

        $stmt = $ligacao->prepare("
            SELECT
                d.*,
                td.nome AS tipo_documento,
                ed.nome AS estado_documento,
                f.codigo AS fornecedor_codigo,
                f.nome AS fornecedor_nome
            FROM documentos d
            LEFT JOIN tipos_documento td ON d.tipo_documento_id = td.id
            LEFT JOIN estados_documento ed ON d.estado_documento_id = ed.id
            LEFT JOIN fornecedores f ON d.fornecedor_id = f.id
            WHERE d.equipamento_id = :id
              AND d.id NOT IN (
                    SELECT c.documento_id
                    FROM contratos c
                    WHERE c.equipamento_id = :id
                      AND c.documento_id IS NOT NULL
              )
            ORDER BY d.codigo
        ");
        $stmt->execute([':id' => $id_equipamento]);
        $documentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare("
            SELECT
                c.*,
                tc.nome AS tipo_contrato,
                ec.nome AS estado_contrato,
                f.codigo AS fornecedor_codigo,
                f.nome AS fornecedor_nome,
                d.codigo AS documento_codigo,
                d.nome AS documento_nome,
                d.ficheiro AS documento_ficheiro
            FROM contratos c
            LEFT JOIN tipos_contrato tc ON c.tipo_contrato_id = tc.id
            LEFT JOIN estados_contrato ec ON c.estado_contrato_id = ec.id
            LEFT JOIN fornecedores f ON c.fornecedor_id = f.id
            LEFT JOIN documentos d ON c.documento_id = d.id
            WHERE c.equipamento_id = :id
            ORDER BY c.id DESC
            LIMIT 1
        ");
        $stmt->execute([':id' => $id_equipamento]);
        $contratos = $stmt->fetchAll();
    } catch (PDOException $erroBD) {
        $erro = 'Aconteceu um erro ao consultar os detalhes do equipamento.';
    }
}

include '../includes/header.php';
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
                <h1>Detalhes do Equipamento</h1>
                <p>Consulta dos dados reais do equipamento selecionado.</p>
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

        <!-- Caixa principal do módulo, onde são apresentados formulários, tabelas ou detalhes -->
<section class="backend-box">

            <?php if ($erro) : ?>
                <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
            <?php elseif ($equipamento) : ?>

                <div class="backend-section-header">
                    <div>
                        <h2><?= h($equipamento->designacao) ?></h2>
                        <p>Código interno: <?= h($equipamento->codigo) ?></p>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <?php if ($editar_disponivel_equipamento) : ?>
                            <a href="editar.php?id=<?= h($id_encriptado) ?>" class="btn-backend">
                                <i class="bi bi-pencil-square"></i>
                                Editar equipamento
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

                <ul class="nav nav-tabs mb-4 tabs-equipamento" id="tabsDetalhesEquipamento" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detalhes-info-tab" data-bs-toggle="tab" data-bs-target="#detalhes-info" type="button" role="tab">
                            <i class="bi bi-info-circle"></i>
                            Informação geral
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="detalhes-localizacao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-localizacao" type="button" role="tab">
                            <i class="bi bi-geo-alt"></i>
                            Localização
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="detalhes-fornecedores-tab" data-bs-toggle="tab" data-bs-target="#detalhes-fornecedores" type="button" role="tab">
                            <i class="bi bi-truck"></i>
                            Fornecedores
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="detalhes-documentacao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-documentacao" type="button" role="tab">
                            <i class="bi bi-folder"></i>
                            Documentação
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="detalhes-contratos-tab" data-bs-toggle="tab" data-bs-target="#detalhes-contratos" type="button" role="tab">
                            <i class="bi bi-shield-check"></i>
                            Contratos
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="detalhes-info" role="tabpanel">
                        <div class="detalhes-grid">
                            <div class="detalhe-card">
                                <h3>Identificação</h3>

                                <div class="detalhe-linha"><span>Código</span><strong><?= h($equipamento->codigo) ?></strong></div>
                                <div class="detalhe-linha"><span>Designação</span><strong><?= h($equipamento->designacao) ?></strong></div>
                                <div class="detalhe-linha"><span>Categoria</span><strong><?= h($equipamento->categoria) ?></strong></div>
                                <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado($equipamento->estado) ?>"><?= h($equipamento->estado) ?></span></strong></div>
                                <div class="detalhe-linha"><span>Criticidade</span><strong><?= h($equipamento->criticidade) ?></strong></div>
                                <div class="detalhe-linha"><span>Prioridade</span><strong><?= h($equipamento->prioridade) ?></strong></div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Dados técnicos</h3>

                                <div class="detalhe-linha"><span>Marca</span><strong><?= h($equipamento->marca) ?></strong></div>
                                <div class="detalhe-linha"><span>Modelo</span><strong><?= h($equipamento->modelo) ?></strong></div>
                                <div class="detalhe-linha"><span>N.º série</span><strong><?= h($equipamento->numero_serie) ?></strong></div>
                                <div class="detalhe-linha"><span>Fabricante</span><strong><?= h($equipamento->fabricante) ?></strong></div>
                                <div class="detalhe-linha"><span>Ano de fabrico</span><strong><?= h($equipamento->ano_fabrico) ?></strong></div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Aquisição</h3>

                                <div class="detalhe-linha"><span>Data de aquisição</span><strong><?= h(data_pt($equipamento->data_aquisicao)) ?></strong></div>
                                <div class="detalhe-linha"><span>Custo</span><strong><?= h(valor_moeda($equipamento->custo)) ?></strong></div>
                                <div class="detalhe-linha"><span>Tipo de entrada</span><strong><?= h($equipamento->tipo_entrada) ?></strong></div>
                                <div class="detalhe-linha"><span>Componente de outro equipamento</span><strong><?= h(sim_nao($equipamento->componente_outro_equipamento)) ?></strong></div>
                                <div class="detalhe-linha"><span>Equipamento principal</span><strong><?= h($equipamento->equipamento_principal_codigo ? $equipamento->equipamento_principal_codigo . ' - ' . $equipamento->equipamento_principal_designacao : 'Não aplicável') ?></strong></div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Utilização</h3>

                                <div class="detalhe-linha"><span>Tem consumíveis</span><strong><?= h(sim_nao($equipamento->tem_consumiveis)) ?></strong></div>
                                <div class="detalhe-linha"><span>Consumíveis</span><strong><?= h($equipamento->consumiveis ?: 'Não aplicável') ?></strong></div>
                                <div class="detalhe-linha"><span>Utilização clínica</span><strong><?= h($equipamento->utilizacao_clinica) ?></strong></div>
                                <div class="detalhe-linha"><span>Observações</span><strong><?= h($equipamento->observacoes ?: 'Sem observações') ?></strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="detalhes-localizacao" role="tabpanel">
                        <div class="detalhes-grid">
                            <div class="detalhe-card">
                                <h3>Localização geral</h3>

                                <div class="detalhe-linha"><span>Código</span><strong><?= h($equipamento->localizacao_codigo) ?></strong></div>
                                <div class="detalhe-linha"><span>Edifício</span><strong><?= h($equipamento->localizacao_edificio) ?></strong></div>
                                <div class="detalhe-linha"><span>N.º pisos</span><strong><?= h($equipamento->localizacao_numero_pisos) ?></strong></div>
                                <div class="detalhe-linha"><span>Responsável</span><strong><?= h($equipamento->localizacao_responsavel) ?></strong></div>
                                <div class="detalhe-linha"><span>Contacto interno</span><strong><?= h($equipamento->localizacao_contacto) ?></strong></div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Localização específica do equipamento</h3>

                                <div class="detalhe-linha"><span>Piso</span><strong><?= h($equipamento->localizacao_piso) ?></strong></div>
                                <div class="detalhe-linha"><span>Serviço</span><strong><?= h($equipamento->localizacao_servico) ?></strong></div>
                                <div class="detalhe-linha"><span>Sala / Unidade</span><strong><?= h($equipamento->localizacao_sala) ?></strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="detalhes-fornecedores" role="tabpanel">
                        <?php if (empty($fornecedores)) : ?>
                            <div class="alert alert-info">Este equipamento não tem fornecedores associados.</div>
                        <?php endif; ?>

                        <div class="detalhes-grid">
                            <?php foreach ($fornecedores as $fornecedor) : ?>
                                <div class="detalhe-card">
                                    <h3><?= h($fornecedor->fornecedor_codigo) ?> - <?= h($fornecedor->fornecedor_nome) ?></h3>

                                    <div class="detalhe-linha"><span>Tipo de relação</span><strong><?= h($fornecedor->tipo_relacao) ?></strong></div>
                                    <div class="detalhe-linha"><span>Principal</span><strong><?= h(sim_nao($fornecedor->principal)) ?></strong></div>
                                    <div class="detalhe-linha"><span>Pessoa de contacto</span><strong><?= h($fornecedor->pessoa_contacto) ?></strong></div>
                                    <div class="detalhe-linha"><span>Telefone contacto</span><strong><?= h($fornecedor->telefone_contacto) ?></strong></div>
                                    <div class="detalhe-linha"><span>Email contacto</span><strong><?= h($fornecedor->email_contacto) ?></strong></div>
                                    <div class="detalhe-linha"><span>Telefone fornecedor</span><strong><?= h($fornecedor->fornecedor_telefone) ?></strong></div>
                                    <div class="detalhe-linha"><span>Email fornecedor</span><strong><?= h($fornecedor->fornecedor_email) ?></strong></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="detalhes-documentacao" role="tabpanel">
                        <?php if (empty($documentos)) : ?>
                            <div class="alert alert-info">Este equipamento não tem documentação técnica associada.</div>
                        <?php endif; ?>

                        <div class="detalhes-grid">
                            <?php foreach ($documentos as $documento) : ?>
                                <div class="detalhe-card">
                                    <h3><?= h($documento->codigo) ?> - <?= h($documento->nome) ?></h3>

                                    <div class="detalhe-linha"><span>Tipo</span><strong><?= h($documento->tipo_documento) ?></strong></div>
                                    <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado($documento->estado_documento) ?>"><?= h($documento->estado_documento) ?></span></strong></div>
                                    <div class="detalhe-linha"><span>Fornecedor</span><strong><?= h($documento->fornecedor_nome ?: 'Sem fornecedor associado') ?></strong></div>
                                    <div class="detalhe-linha"><span>Data</span><strong><?= h(data_pt($documento->data_documento)) ?></strong></div>
                                    <div class="detalhe-linha"><span>Validade</span><strong><?= h(data_pt($documento->data_validade)) ?></strong></div>
                                    <div class="detalhe-linha"><span>Ficheiro</span><strong>
                                        <?php if (!empty($documento->ficheiro)) : ?>
                                            <a href="<?= h(BASE_URL . '/' . $documento->ficheiro) ?>" target="_blank">Abrir PDF</a>
                                        <?php else : ?>
                                            Não disponível
                                        <?php endif; ?>
                                    </strong></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="detalhes-contratos" role="tabpanel">
                        <?php if (empty($contratos)) : ?>
                            <div class="alert alert-info">Este equipamento não tem contrato/garantia associado.</div>
                        <?php endif; ?>

                        <div class="detalhes-grid detalhes-grid-contrato">
                            <?php foreach ($contratos as $contrato) : ?>
                                <div class="detalhe-card">
                                    <h3><?= h($contrato->codigo) ?> - <?= h($contrato->tipo_contrato) ?></h3>

                                    <div class="detalhe-linha"><span>Estado</span><strong><span class="estado <?= classe_estado($contrato->estado_contrato) ?>"><?= h($contrato->estado_contrato) ?></span></strong></div>
                                    <div class="detalhe-linha"><span>Fornecedor</span><strong><?= h($contrato->fornecedor_nome ?: 'Não aplicável') ?></strong></div>
                                    <div class="detalhe-linha"><span>Data de início</span><strong><?= h(data_pt($contrato->data_inicio)) ?></strong></div>
                                    <div class="detalhe-linha"><span>Data de fim</span><strong><?= h(data_pt($contrato->data_fim)) ?></strong></div>
                                    <div class="detalhe-linha"><span>Periodicidade</span><strong><?= h($contrato->periodicidade) ?></strong></div>
                                    <div class="detalhe-linha"><span>Valor</span><strong><?= h(valor_moeda($contrato->valor)) ?></strong></div>
                                    <div class="detalhe-linha"><span>Documento</span><strong>
                                        <?php if (!empty($contrato->documento_ficheiro)) : ?>
                                            <a href="<?= h(BASE_URL . '/' . $contrato->documento_ficheiro) ?>" target="_blank"><?= h($contrato->documento_codigo . ' - ' . $contrato->documento_nome) ?></a>
                                        <?php else : ?>
                                            Sem documento associado
                                        <?php endif; ?>
                                    </strong></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </section>
    </main>
</div>


<style>
    .detalhes-grid-contrato {
        grid-template-columns: 1fr;
    }
</style>

<?php include '../includes/footer.php'; ?>
