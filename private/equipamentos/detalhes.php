<?php
$pagina_atual = 'equipamentos';

require_once __DIR__ . '/../includes/funcoes.php';
redirect_if_not_logged();

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

function dinheiro_pt($valor)
{
    if ($valor === null || $valor === '') {
        return 'Não aplicável';
    }

    return number_format((float) $valor, 2, ',', ' ') . ' €';
}

function sim_nao($valor)
{
    return (int) $valor === 1 ? 'Sim' : 'Não';
}

function classe_estado_detalhes($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Ativa' => 'ativo',
        'Válido' => 'ativo',
        'Em manutenção' => 'manutencao',
        'Inativo' => 'inativo',
        'Inativa' => 'inativo',
        'Em calibração' => 'calibracao',
        'Alta' => 'alta',
        'Média' => 'media',
        'Baixa' => 'baixa',
        'Suporte de vida' => 'suporte',
        'A terminar' => 'pendente',
        'Pendente' => 'pendente',
        'Expirado' => 'expirado',
        'Expirada' => 'expirado',
        'Sem garantia registada' => 'inativo'
    ];

    return $classes[$texto] ?? '';
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$erro = '';
$equipamento = null;
$fornecedores = [];
$documentos = [];
$contratos = [];

if (!$id) {
    $erro = 'Equipamento não identificado.';
} else {
    $ligacao = ligar_bd();

    if (!$ligacao) {
        $erro = 'Aconteceu um erro na ligação à base de dados.';
    } else {
        try {
            $consultaEquipamento = $ligacao->prepare("
                SELECT
                    e.*,
                    ce.nome AS categoria,
                    ee.nome AS estado,
                    c.nome AS criticidade,
                    pm.nome AS prioridade_manutencao,
                    te.nome AS tipo_entrada,
                    l.codigo AS localizacao_codigo,
                    l.edificio,
                    l.piso,
                    l.servico,
                    l.sala,
                    l.responsavel,
                    l.contacto_interno,
                    el.nome AS estado_localizacao,
                    ep.codigo AS equipamento_principal_codigo,
                    ep.designacao AS equipamento_principal_designacao
                FROM equipamentos e
                INNER JOIN categorias_equipamento ce
                    ON e.categoria_equipamento_id = ce.id
                INNER JOIN estados_equipamento ee
                    ON e.estado_equipamento_id = ee.id
                INNER JOIN criticidades c
                    ON e.criticidade_id = c.id
                INNER JOIN prioridades_manutencao pm
                    ON e.prioridade_manutencao_id = pm.id
                INNER JOIN tipos_entrada te
                    ON e.tipo_entrada_id = te.id
                INNER JOIN localizacoes l
                    ON e.localizacao_id = l.id
                INNER JOIN estados_localizacao el
                    ON l.estado_localizacao_id = el.id
                LEFT JOIN equipamentos ep
                    ON e.equipamento_principal_id = ep.id
                WHERE e.id = :id
                  AND e.ativo = 1
                LIMIT 1
            ");

            $consultaEquipamento->execute(['id' => $id]);
            $equipamento = $consultaEquipamento->fetch();

            if (!$equipamento) {
                $erro = 'Equipamento não encontrado.';
            } else {
                $consultaFornecedores = $ligacao->prepare("
                    SELECT
                        f.codigo,
                        f.nome,
                        f.nif,
                        f.telefone,
                        f.email,
                        f.website,
                        f.pessoa_contacto,
                        f.telefone_contacto,
                        f.morada,
                        tf.nome AS tipo_fornecedor,
                        trf.nome AS tipo_relacao,
                        ef.principal,
                        ef.observacoes AS observacoes_relacao
                    FROM equipamento_fornecedores ef
                    INNER JOIN fornecedores f
                        ON ef.fornecedor_id = f.id
                    INNER JOIN tipos_fornecedor tf
                        ON f.tipo_fornecedor_id = tf.id
                    INNER JOIN tipos_relacao_fornecedor trf
                        ON ef.tipo_relacao_fornecedor_id = trf.id
                    WHERE ef.equipamento_id = :id
                    ORDER BY ef.principal DESC, f.nome
                ");

                $consultaFornecedores->execute(['id' => $id]);
                $fornecedores = $consultaFornecedores->fetchAll();

                $consultaDocumentos = $ligacao->prepare("
                    SELECT
                        d.codigo,
                        d.nome,
                        d.data_documento,
                        d.data_validade,
                        d.ficheiro,
                        d.responsavel_registo,
                        d.observacoes,
                        td.nome AS tipo_documento,
                        ed.nome AS estado_documento,
                        f.nome AS fornecedor
                    FROM documentos d
                    INNER JOIN tipos_documento td
                        ON d.tipo_documento_id = td.id
                    INNER JOIN estados_documento ed
                        ON d.estado_documento_id = ed.id
                    LEFT JOIN fornecedores f
                        ON d.fornecedor_id = f.id
                    WHERE d.equipamento_id = :id
                    ORDER BY d.data_documento DESC, d.codigo
                ");

                $consultaDocumentos->execute(['id' => $id]);
                $documentos = $consultaDocumentos->fetchAll();

                $consultaContratos = $ligacao->prepare("
                    SELECT
                        c.codigo,
                        c.data_inicio,
                        c.data_fim,
                        c.periodicidade,
                        c.valor,
                        c.observacoes,
                        tc.nome AS tipo_contrato,
                        ec.nome AS estado_contrato,
                        f.nome AS fornecedor,
                        d.ficheiro AS documento_ficheiro
                    FROM contratos c
                    INNER JOIN tipos_contrato tc
                        ON c.tipo_contrato_id = tc.id
                    INNER JOIN estados_contrato ec
                        ON c.estado_contrato_id = ec.id
                    INNER JOIN fornecedores f
                        ON c.fornecedor_id = f.id
                    LEFT JOIN documentos d
                        ON c.documento_id = d.id
                    WHERE c.equipamento_id = :id
                    ORDER BY c.data_fim DESC, c.codigo
                ");

                $consultaContratos->execute(['id' => $id]);
                $contratos = $consultaContratos->fetchAll();
            }
        } catch (PDOException $erroBD) {
            $erro = 'Aconteceu um erro ao consultar os detalhes do equipamento.';
            $equipamento = null;
            $fornecedores = [];
            $documentos = [];
            $contratos = [];
        }

        $ligacao = null;
    }
}

include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Detalhes do Equipamento</h1>
                <p>Consulta completa da ficha técnica e administrativa do equipamento médico.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/index.php">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Sair para o site público
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php if (!empty($erro)) : ?>

            <section class="backend-box">
                <div class="backend-section-header">
                    <div>
                        <h2>Não foi possível carregar o equipamento</h2>
                        <p><?= h($erro) ?></p>
                    </div>

                    <a href="index.php" class="btn-secundario">
                        <i class="bi bi-arrow-left-circle"></i>
                        Voltar à listagem
                    </a>
                </div>
            </section>

        <?php else : ?>

            <section class="backend-box">
                <div class="backend-section-header">
                    <div>
                        <h2><?= h($equipamento->designacao) ?></h2>
                        <p>Código interno: <?= h($equipamento->codigo) ?></p>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <a href="editar.php?id=<?= h($equipamento->id) ?>" class="btn-backend">
                            <i class="bi bi-pencil-square"></i>
                            Editar equipamento
                        </a>

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
                        <button class="nav-link" id="detalhes-fornecedor-tab" data-bs-toggle="tab" data-bs-target="#detalhes-fornecedor" type="button" role="tab">
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

                <div class="tab-content" id="tabsDetalhesEquipamentoConteudo">

                    <div class="tab-pane fade show active" id="detalhes-info" role="tabpanel" tabindex="0">
                        <h3>Informação geral</h3>

                        <div class="detalhes-grid">
                            <div class="detalhe-card">
                                <h3>Identificação</h3>

                                <div class="detalhe-linha">
                                    <span>Código interno</span>
                                    <strong><?= h($equipamento->codigo) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Designação</span>
                                    <strong><?= h($equipamento->designacao) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Categoria</span>
                                    <strong><?= h($equipamento->categoria) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Estado atual</span>
                                    <strong>
                                        <span class="estado <?= classe_estado_detalhes($equipamento->estado) ?>">
                                            <?= h($equipamento->estado) ?>
                                        </span>
                                    </strong>
                                </div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Dados técnicos</h3>

                                <div class="detalhe-linha">
                                    <span>Marca</span>
                                    <strong><?= h($equipamento->marca) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Modelo</span>
                                    <strong><?= h($equipamento->modelo) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Número de série</span>
                                    <strong><?= h($equipamento->numero_serie) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Fabricante</span>
                                    <strong><?= h($equipamento->fabricante) ?></strong>
                                </div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Aquisição</h3>

                                <div class="detalhe-linha">
                                    <span>Data de aquisição</span>
                                    <strong><?= data_pt($equipamento->data_aquisicao) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Ano de fabrico</span>
                                    <strong><?= h($equipamento->ano_fabrico) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Custo de aquisição</span>
                                    <strong><?= dinheiro_pt($equipamento->custo) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Tipo de entrada</span>
                                    <strong><?= h($equipamento->tipo_entrada) ?></strong>
                                </div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Criticidade</h3>

                                <div class="detalhe-linha">
                                    <span>Nível de criticidade</span>
                                    <strong>
                                        <span class="estado <?= classe_estado_detalhes($equipamento->criticidade) ?>">
                                            <?= h($equipamento->criticidade) ?>
                                        </span>
                                    </strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Prioridade de manutenção</span>
                                    <strong><?= h($equipamento->prioridade_manutencao) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Utilização clínica</span>
                                    <strong><?= h($equipamento->utilizacao_clinica) ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="detalhes-grid mt-4">
                            <div class="detalhe-card">
                                <h3>Relações e consumíveis</h3>

                                <div class="detalhe-linha">
                                    <span>É componente de outro equipamento</span>
                                    <strong><?= sim_nao($equipamento->componente_outro_equipamento) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Equipamento principal</span>
                                    <strong>
                                        <?php if (!empty($equipamento->equipamento_principal_designacao)) : ?>
                                            <?= h($equipamento->equipamento_principal_codigo) ?> —
                                            <?= h($equipamento->equipamento_principal_designacao) ?>
                                        <?php else : ?>
                                            Não aplicável
                                        <?php endif; ?>
                                    </strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Tem consumíveis</span>
                                    <strong><?= sim_nao($equipamento->tem_consumiveis) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Consumíveis utilizados</span>
                                    <strong><?= !empty($equipamento->consumiveis) ? h($equipamento->consumiveis) : 'Não aplicável' ?></strong>
                                </div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Observações</h3>

                                <p>
                                    <?= !empty($equipamento->observacoes) ? h($equipamento->observacoes) : 'Sem observações registadas.' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="detalhes-localizacao" role="tabpanel" tabindex="0">
                        <h3>Localização</h3>

                        <div class="detalhes-grid">
                            <div class="detalhe-card">
                                <h3>Local atual</h3>

                                <div class="detalhe-linha">
                                    <span>Código da localização</span>
                                    <strong><?= h($equipamento->localizacao_codigo) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Serviço / Localização</span>
                                    <strong><?= h($equipamento->servico) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Edifício</span>
                                    <strong><?= h($equipamento->edificio) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Piso</span>
                                    <strong><?= h($equipamento->piso) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Sala / Unidade</span>
                                    <strong><?= h($equipamento->sala) ?></strong>
                                </div>
                            </div>

                            <div class="detalhe-card">
                                <h3>Responsável</h3>

                                <div class="detalhe-linha">
                                    <span>Responsável da localização</span>
                                    <strong><?= h($equipamento->responsavel) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Contacto interno</span>
                                    <strong><?= h($equipamento->contacto_interno) ?></strong>
                                </div>

                                <div class="detalhe-linha">
                                    <span>Estado da localização</span>
                                    <strong>
                                        <span class="estado <?= classe_estado_detalhes($equipamento->estado_localizacao) ?>">
                                            <?= h($equipamento->estado_localizacao) ?>
                                        </span>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="detalhes-fornecedor" role="tabpanel" tabindex="0">
                        <h3>Fornecedores associados</h3>
                        <p>Fornecedores relacionados com este equipamento, de acordo com o tipo de relação.</p>

                        <?php if (count($fornecedores) == 0) : ?>
                            <p class="sem-resultados" style="display: block;">Não existem fornecedores associados a este equipamento.</p>
                        <?php else : ?>
                            <div class="detalhes-grid">
                                <?php foreach ($fornecedores as $indice => $fornecedor) : ?>
                                    <div class="detalhe-card">
                                        <h3>Fornecedor associado <?= $indice + 1 ?></h3>

                                        <div class="detalhe-linha">
                                            <span>Fornecedor</span>
                                            <strong><?= h($fornecedor->nome) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Tipo de fornecedor</span>
                                            <strong><?= h($fornecedor->tipo_fornecedor) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Tipo de relação</span>
                                            <strong><?= h($fornecedor->tipo_relacao) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Pessoa de contacto</span>
                                            <strong><?= h($fornecedor->pessoa_contacto) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Telefone</span>
                                            <strong><?= h($fornecedor->telefone) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Telefone da pessoa de contacto</span>
                                            <strong><?= !empty($fornecedor->telefone_contacto) ? h($fornecedor->telefone_contacto) : 'Não aplicável' ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Email</span>
                                            <strong><?= h($fornecedor->email) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>NIF</span>
                                            <strong><?= h($fornecedor->nif) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Website</span>
                                            <strong><?= !empty($fornecedor->website) ? h($fornecedor->website) : 'Não aplicável' ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Morada</span>
                                            <strong><?= !empty($fornecedor->morada) ? h($fornecedor->morada) : 'Não aplicável' ?></strong>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="detalhes-documentacao" role="tabpanel" tabindex="0">
                        <h3>Documentação</h3>
                        <p>Documentos registados para este equipamento, com indicação de tipo, nome, datas, validade, fornecedor associado e ficheiro.</p>

                        <?php if (count($documentos) == 0) : ?>
                            <p class="sem-resultados" style="display: block;">Não existem documentos associados a este equipamento.</p>
                        <?php else : ?>
                            <div class="table-responsive">
                                <table class="tabela-backend">
                                    <thead>
                                        <tr>
                                            <th>Tipo de documento</th>
                                            <th>Nome do documento</th>
                                            <th>Data do documento</th>
                                            <th>Validade</th>
                                            <th>Fornecedor associado</th>
                                            <th>Ficheiro</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($documentos as $documento) : ?>
                                            <tr>
                                                <td><?= h($documento->tipo_documento) ?></td>
                                                <td><?= h($documento->nome) ?></td>
                                                <td><?= data_pt($documento->data_documento) ?></td>
                                                <td><?= data_pt($documento->data_validade) ?></td>
                                                <td><?= !empty($documento->fornecedor) ? h($documento->fornecedor) : 'Não aplicável' ?></td>
                                                <td><?= h($documento->ficheiro) ?></td>
                                                <td>
                                                    <span class="estado <?= classe_estado_detalhes($documento->estado_documento) ?>">
                                                        <?= h($documento->estado_documento) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="detalhes-contratos" role="tabpanel" tabindex="0">
                        <h3>Contratos</h3>

                        <?php if (count($contratos) == 0) : ?>
                            <p class="sem-resultados" style="display: block;">Não existem contratos associados a este equipamento.</p>
                        <?php else : ?>
                            <div class="detalhes-grid">
                                <?php foreach ($contratos as $contrato) : ?>
                                    <div class="detalhe-card">
                                        <h3><?= h($contrato->tipo_contrato) ?></h3>

                                        <div class="detalhe-linha">
                                            <span>Código do contrato</span>
                                            <strong><?= h($contrato->codigo) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Fornecedor</span>
                                            <strong><?= h($contrato->fornecedor) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Estado do contrato / garantia</span>
                                            <strong>
                                                <span class="estado <?= classe_estado_detalhes($contrato->estado_contrato) ?>">
                                                    <?= h($contrato->estado_contrato) ?>
                                                </span>
                                            </strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Data de início</span>
                                            <strong><?= data_pt($contrato->data_inicio) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Data de fim</span>
                                            <strong><?= data_pt($contrato->data_fim) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Periodicidade de manutenção</span>
                                            <strong><?= !empty($contrato->periodicidade) ? h($contrato->periodicidade) : 'Não aplicável' ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Valor associado</span>
                                            <strong><?= dinheiro_pt($contrato->valor) ?></strong>
                                        </div>

                                        <div class="detalhe-linha">
                                            <span>Documento associado</span>
                                            <strong><?= !empty($contrato->documento_ficheiro) ? h($contrato->documento_ficheiro) : 'Não aplicável' ?></strong>
                                        </div>

                                        <?php if (!empty($contrato->observacoes)) : ?>
                                            <div class="detalhe-linha">
                                                <span>Observações</span>
                                                <strong><?= h($contrato->observacoes) ?></strong>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </section>

        <?php endif; ?>

    </main>

</div>

<?php include '../includes/footer.php'; ?>
