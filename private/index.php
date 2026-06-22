<?php
require_once 'includes/funcoes.php';
require_once 'includes/mensagens_contacto.php';

start_session();

$pagina_atual = 'dashboard';

// --------------------------------------------------------------------
// ACESSO AO DASHBOARD COM SESSÃO E PERFIL
// --------------------------------------------------------------------

redirect_if_not_logged();
redirect_if_no_permission('dashboard', 'ver');

$username = $_SESSION['utilizador'] ?? '';
$profile = $_SESSION['profile'] ?? '';
$mensagem_sessao = $_SESSION['server_error'] ?? '';
unset($_SESSION['server_error']);

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function dashboard_json($dados)
{
    return h(json_encode($dados, JSON_UNESCAPED_UNICODE));
}

function classe_dashboard($texto)
{
    $classes = [
        'Ativo' => 'ativo',
        'Ativa' => 'ativo',
        'Válida' => 'ativo',
        'Válido' => 'ativo',

        'Em manutenção' => 'manutencao',
        'A expirar' => 'pendente',
        'Pendente' => 'pendente',
        'Média' => 'media',

        'Expirada' => 'expirado',
        'Expirado' => 'expirado',
        'Alta' => 'alta',
        'Crítica' => 'alta',

        'Suporte de vida' => 'suporte',

        'Inativo' => 'inativo',
        'Inativa' => 'inativo',
        'Sem contrato' => 'inativo',
        'Sem documentação' => 'inativo',

        'Em calibração' => 'calibracao',
        'Baixa' => 'ativo'
    ];

    return $classes[$texto] ?? '';
}

function classe_criticidade_barra($texto)
{
    $classes = [
        'Suporte de vida' => 'criticidade-suporte',
        'Crítica' => 'criticidade-alta',
        'Alta' => 'criticidade-alta',
        'Média' => 'criticidade-media',
        'Baixa' => 'criticidade-baixa'
    ];

    return $classes[$texto] ?? 'criticidade-media';
}

function estado_garantia($data_fim)
{
    if (empty($data_fim)) {
        return 'Sem contrato';
    }

    $hoje = new DateTime(date('Y-m-d'));
    $fim = new DateTime($data_fim);
    $limite = (clone $hoje)->modify('+30 days');

    if ($fim < $hoje) {
        return 'Expirada';
    }

    if ($fim <= $limite) {
        return 'A expirar';
    }

    return 'Válida';
}

function percentagem($valor, $total)
{
    if ((int) $total <= 0) {
        return 0;
    }

    return (int) round(((int) $valor * 100) / (int) $total);
}

function valor_count($ligacao, $sql)
{
    return (int) $ligacao->query($sql)->fetchColumn();
}

$erro_dashboard = '';

$total_equipamentos = 0;
$equipamentos_ativos = 0;
$equipamentos_manutencao = 0;
$equipamentos_inativos = 0;
$total_localizacoes = 0;
$garantias_expiradas = 0;
$equipamentos_sem_documentacao = 0;
$criticidade_elevada = 0;

$grafico_estados = [];
$grafico_categorias = [];
$grafico_localizacoes = [];
$criticidades_dashboard = [];
$alertas_dashboard = [];
$equipamentos_criticos = [];
$total_mensagens_contacto = 0;
$mensagens_novas = 0;
$mensagens_lidas = 0;
$mensagens_arquivadas = 0;
$mensagens_recentes = [];
$mensagem_contacto_aberta = null;
$mensagem_dashboard_sucesso = '';

$ligacao = ligar_bd();

if (!$ligacao) {
    $erro_dashboard = 'Aconteceu um erro na ligação à base de dados.';
} else {
    try {
        garantir_tabela_mensagens_contacto($ligacao);

        if (perfil_tem_acesso('mensagens', 'editar') && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao_mensagem'] ?? '') !== '') {
            $id_mensagem = (int) ($_POST['id_mensagem'] ?? 0);
            $acao_mensagem = $_POST['acao_mensagem'] ?? '';

            if ($id_mensagem > 0 && $acao_mensagem === 'marcar_lida') {
                marcar_mensagem_contacto_lida($ligacao, $id_mensagem);
                $mensagem_dashboard_sucesso = 'Mensagem marcada como lida.';
            } elseif ($id_mensagem > 0 && $acao_mensagem === 'arquivar') {
                arquivar_mensagem_contacto($ligacao, $id_mensagem);
                $mensagem_dashboard_sucesso = 'Mensagem arquivada com sucesso.';
            }
        }

        $id_mensagem_ver = (int) ($_GET['mensagem'] ?? 0);

        if (perfil_tem_acesso('mensagens', 'ver') && $id_mensagem_ver > 0) {
            $mensagem_contacto_aberta = obter_mensagem_contacto($ligacao, $id_mensagem_ver);

            if ($mensagem_contacto_aberta && $mensagem_contacto_aberta->estado === 'Nova') {
                marcar_mensagem_contacto_lida($ligacao, $id_mensagem_ver);
                $mensagem_contacto_aberta = obter_mensagem_contacto($ligacao, $id_mensagem_ver);
            }
        }

        $total_equipamentos = valor_count($ligacao, "SELECT COUNT(*) FROM equipamentos");

        $equipamentos_ativos = valor_count($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            WHERE e.ativo = 1
              AND ee.nome <> 'Inativo'
        ");

        $equipamentos_manutencao = valor_count($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            WHERE e.ativo = 1
              AND ee.nome = 'Em manutenção'
        ");

        $equipamentos_inativos = valor_count($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            WHERE e.ativo = 0
               OR ee.nome = 'Inativo'
        ");

        $total_localizacoes = valor_count($ligacao, "SELECT COUNT(*) FROM localizacoes");

        $total_mensagens_contacto = contar_mensagens_contacto($ligacao);
        $mensagens_novas = contar_mensagens_contacto($ligacao, "estado = 'Nova' AND arquivada = 0");
        $mensagens_lidas = contar_mensagens_contacto($ligacao, "estado = 'Lida' AND arquivada = 0");
        $mensagens_arquivadas = contar_mensagens_contacto($ligacao, "arquivada = 1");
        $mensagens_recentes = obter_mensagens_contacto_recentes($ligacao, 5);

        $garantias_expiradas = valor_count($ligacao, "
            SELECT COUNT(*)
            FROM contratos c
            INNER JOIN estados_contrato ec
                ON c.estado_contrato_id = ec.id
            WHERE ec.nome IN ('Expirado', 'Expirada')
               OR (c.data_fim IS NOT NULL AND c.data_fim < CURDATE())
        ");

        $equipamentos_sem_documentacao = valor_count($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            WHERE e.ativo = 1
              AND NOT EXISTS (
                    SELECT 1
                    FROM documentos d
                    INNER JOIN estados_documento ed
                        ON d.estado_documento_id = ed.id
                    WHERE d.equipamento_id = e.id
                      AND ed.nome NOT IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
              )
        ");

        $criticidade_elevada = valor_count($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN criticidades c
                ON e.criticidade_id = c.id
            WHERE e.ativo = 1
              AND c.nome IN ('Suporte de vida', 'Crítica', 'Alta')
        ");

        $grafico_estados = $ligacao->query("
            SELECT ee.nome AS label, COUNT(e.id) AS total
            FROM equipamentos e
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            GROUP BY ee.nome
            ORDER BY ee.nome
        ")->fetchAll();

        $grafico_categorias = $ligacao->query("
            SELECT ce.nome AS label, COUNT(e.id) AS total
            FROM equipamentos e
            INNER JOIN categorias_equipamento ce
                ON e.categoria_equipamento_id = ce.id
            GROUP BY ce.nome
            ORDER BY ce.nome
        ")->fetchAll();

        $grafico_localizacoes = $ligacao->query("
            SELECT l.edificio AS label, COUNT(e.id) AS total
            FROM equipamentos e
            INNER JOIN localizacoes l
                ON e.localizacao_id = l.id
            GROUP BY l.edificio
            ORDER BY l.edificio
        ")->fetchAll();

        $criticidades_dashboard = $ligacao->query("
            SELECT c.nome AS nome, COUNT(e.id) AS total
            FROM criticidades c
            LEFT JOIN equipamentos e
                ON e.criticidade_id = c.id
                AND e.ativo = 1
            GROUP BY c.id, c.nome
            HAVING total > 0
            ORDER BY FIELD(c.nome, 'Suporte de vida', 'Crítica', 'Alta', 'Média', 'Baixa'), c.nome
        ")->fetchAll();

        $contrato_expirado = $ligacao->query("
            SELECT e.designacao, e.marca, e.modelo, c.data_fim
            FROM contratos c
            INNER JOIN equipamentos e
                ON c.equipamento_id = e.id
            INNER JOIN estados_contrato ec
                ON c.estado_contrato_id = ec.id
            WHERE ec.nome IN ('Expirado', 'Expirada')
               OR (c.data_fim IS NOT NULL AND c.data_fim < CURDATE())
            ORDER BY c.data_fim DESC
            LIMIT 1
        ")->fetch();

        if ($contrato_expirado) {
            $alertas_dashboard[] = [
                'classe' => 'alerta-urgente',
                'icone' => 'bi-exclamation-triangle',
                'titulo' => 'Garantia expirada',
                'texto' => trim($contrato_expirado->designacao . ' ' . $contrato_expirado->marca . ' ' . $contrato_expirado->modelo) . ' tem garantia/contrato expirado.'
            ];
        }

        $contrato_a_terminar = $ligacao->query("
            SELECT e.designacao, e.marca, e.modelo, c.data_fim
            FROM contratos c
            INNER JOIN equipamentos e
                ON c.equipamento_id = e.id
            INNER JOIN estados_contrato ec
                ON c.estado_contrato_id = ec.id
            WHERE c.data_fim IS NOT NULL
              AND c.data_fim >= CURDATE()
              AND c.data_fim <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
              AND ec.nome NOT IN ('Expirado', 'Expirada', 'Inativo', 'Inativa')
            ORDER BY c.data_fim ASC
            LIMIT 1
        ")->fetch();

        if ($contrato_a_terminar) {
            $alertas_dashboard[] = [
                'classe' => 'alerta-aviso',
                'icone' => 'bi-calendar-event',
                'titulo' => 'Contrato a terminar',
                'texto' => trim($contrato_a_terminar->designacao . ' ' . $contrato_a_terminar->marca . ' ' . $contrato_a_terminar->modelo) . ' termina nos próximos 30 dias.'
            ];
        }

        if ($equipamentos_sem_documentacao > 0) {
            $alertas_dashboard[] = [
                'classe' => 'alerta-info',
                'icone' => 'bi-file-earmark-x',
                'titulo' => 'Documentação em falta',
                'texto' => $equipamentos_sem_documentacao . ' equipamento(s) ativo(s) não têm documentação associada.'
            ];
        }

        $equipamentos_em_manutencao = $ligacao->query("
            SELECT e.designacao, e.marca, e.modelo
            FROM equipamentos e
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            WHERE e.ativo = 1
              AND ee.nome = 'Em manutenção'
            ORDER BY e.codigo
            LIMIT 2
        ")->fetchAll();

        if (!empty($equipamentos_em_manutencao)) {
            $nomes_manutencao = [];

            foreach ($equipamentos_em_manutencao as $equipamento_manutencao) {
                $nomes_manutencao[] = trim($equipamento_manutencao->designacao . ' ' . $equipamento_manutencao->marca . ' ' . $equipamento_manutencao->modelo);
            }

            $alertas_dashboard[] = [
                'classe' => 'alerta-info',
                'icone' => 'bi-tools',
                'titulo' => 'Manutenção pendente',
                'texto' => implode(' e ', $nomes_manutencao) . ' requer(em) acompanhamento técnico.'
            ];
        }

        $equipamentos_criticos = $ligacao->query("
            SELECT
                e.codigo,
                e.designacao,
                e.marca,
                e.modelo,
                CONCAT(
                    l.edificio,
                    CASE
                        WHEN e.localizacao_servico IS NOT NULL AND e.localizacao_servico <> ''
                            THEN CONCAT(' / ', e.localizacao_servico)
                        ELSE ''
                    END,
                    CASE
                        WHEN e.localizacao_sala IS NOT NULL AND e.localizacao_sala <> ''
                            THEN CONCAT(' / ', e.localizacao_sala)
                        ELSE ''
                    END
                ) AS localizacao,
                c.nome AS criticidade,
                ee.nome AS estado,
                (
                    SELECT c2.data_fim
                    FROM contratos c2
                    WHERE c2.equipamento_id = e.id
                    ORDER BY c2.data_fim DESC
                    LIMIT 1
                ) AS data_fim_garantia
            FROM equipamentos e
            INNER JOIN criticidades c
                ON e.criticidade_id = c.id
            INNER JOIN estados_equipamento ee
                ON e.estado_equipamento_id = ee.id
            INNER JOIN localizacoes l
                ON e.localizacao_id = l.id
            WHERE e.ativo = 1
              AND c.nome IN ('Suporte de vida', 'Crítica', 'Alta', 'Média')
            ORDER BY FIELD(c.nome, 'Suporte de vida', 'Crítica', 'Alta', 'Média', 'Baixa'), e.codigo
            LIMIT 5
        ")->fetchAll();

    } catch (PDOException $erroBD) {
        $erro_dashboard = 'Aconteceu um erro ao consultar os dados da dashboard.';
    }
}

$labels_estados = array_map(fn($linha) => $linha->label, $grafico_estados);
$valores_estados = array_map(fn($linha) => (int) $linha->total, $grafico_estados);

$labels_categorias = array_map(fn($linha) => $linha->label, $grafico_categorias);
$valores_categorias = array_map(fn($linha) => (int) $linha->total, $grafico_categorias);

$labels_localizacoes = array_map(fn($linha) => $linha->label, $grafico_localizacoes);
$valores_localizacoes = array_map(fn($linha) => (int) $linha->total, $grafico_localizacoes);

include 'includes/header.php';
?>

<div class="backend-layout">

    <?php include 'includes/sidebar.php'; ?>

    <!-- Conteúdo principal -->
    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Visão geral do inventário hospitalar de equipamentos médicos.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span><?php echo htmlspecialchars(perfil_nome(), ENT_QUOTES, 'UTF-8'); ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    
                    <li>
                        <a class="dropdown-item" href="../public/logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php if (!empty($erro_dashboard)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= h($erro_dashboard) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensagem_sessao)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= h($mensagem_sessao) ?>
            </div>
        <?php endif; ?>

        <section class="dashboard-kpis">

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-hospital"></i>
                </div>
                <div>
                    <h3><?= $total_equipamentos ?></h3>
                    <p>Total de equipamentos</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h3><?= $equipamentos_ativos ?></h3>
                    <p>Equipamentos ativos</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <h3><?= $equipamentos_manutencao ?></h3>
                    <p>Em manutenção</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <h3><?= $equipamentos_inativos ?></h3>
                    <p>Equipamentos inativos</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h3><?= $total_localizacoes ?></h3>
                    <p>Localizações</p>
                </div>
            </div>

            <?php if (perfil_tem_acesso('mensagens', 'ver')) : ?>
                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <h3><?= $mensagens_novas ?></h3>
                        <p>Mensagens novas</p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div>
                    <h3><?= $garantias_expiradas ?></h3>
                    <p>Garantias expiradas</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-file-earmark-x"></i>
                </div>
                <div>
                    <h3><?= $equipamentos_sem_documentacao ?></h3>
                    <p>Sem documentação</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <div>
                    <h3><?= $criticidade_elevada ?></h3>
                    <p>Criticidade elevada</p>
                </div>
            </div>

        </section>

        <?php if (perfil_tem_acesso('conteudos', 'editar')) : ?>
        <section class="backend-box mt-4">
            <div class="backend-section-header">
                <div>
                    <h2>Gestão da área pública</h2>
                    <p>Atualize os conteúdos da área pública de forma rápida e eficaz</p>
                </div>

                <a href="conteudos/index.php" class="btn-backend">
                    <i class="bi bi-pencil-square"></i>
                    Gerir conteúdos
                </a>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-xl-4">
                    <div class="detalhe-card h-100">
                        <h3>Textos do site</h3>
                        <p>Editar títulos, descrições, menus e textos principais da página pública.</p>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="detalhe-card h-100">
                        <h3>Contactos</h3>
                        <p>Atualizar email, telefone, localização e textos do formulário de contacto.</p>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="detalhe-card h-100">
                        <h3>Serviços</h3>
                        <p>Gerir os textos informativos sobre equipamentos, fornecedores, documentação e contratos.</p>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="dashboard-graficos">

            <div class="backend-box grafico-card">
                <h2>Equipamentos por estado</h2>
                <p>Distribuição dos equipamentos segundo o seu estado atual.</p>
                <canvas
                    id="graficoEstados"
                    data-labels="<?= dashboard_json($labels_estados) ?>"
                    data-valores="<?= dashboard_json($valores_estados) ?>"></canvas>
            </div>

            <div class="backend-box grafico-card">
                <h2>Equipamentos por categoria</h2>
                <p>Classificação funcional dos equipamentos registados.</p>
                <canvas
                    id="graficoCategorias"
                    data-labels="<?= dashboard_json($labels_categorias) ?>"
                    data-valores="<?= dashboard_json($valores_categorias) ?>"></canvas>
            </div>

        </section>

        <section class="dashboard-graficos">

            <div class="backend-box grafico-card">
                <h2>Equipamentos por localização</h2>
                <p>Distribuição dos equipamentos pelos edifícios registados.</p>
                <canvas
                    id="graficoLocalizacoes"
                    data-labels="<?= dashboard_json($labels_localizacoes) ?>"
                    data-valores="<?= dashboard_json($valores_localizacoes) ?>"></canvas>
            </div>

            <div class="backend-box">
                <h2>Níveis de criticidade</h2>
                <p>Resumo dos equipamentos ativos segundo prioridade clínica.</p>

                <?php if (empty($criticidades_dashboard)) : ?>
                    <p class="sem-resultados d-block">Ainda não existem equipamentos ativos com criticidade definida.</p>
                <?php else : ?>
                    <?php foreach ($criticidades_dashboard as $criticidade) : ?>
                        <?php $percentagem_criticidade = percentagem($criticidade->total, $equipamentos_ativos); ?>

                        <div class="criticidade-item">
                            <div class="criticidade-topo">
                                <span><?= h($criticidade->nome) ?></span>
                                <strong><?= $percentagem_criticidade ?>%</strong>
                            </div>
                            <div class="progress">
                                <div
                                    class="progress-bar <?= classe_criticidade_barra($criticidade->nome) ?>"
                                    style="width: <?= $percentagem_criticidade ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </section>

        <section class="dashboard-duas-colunas">

            <div class="backend-box">
                <h2>Alertas de gestão</h2>

                <?php if (empty($alertas_dashboard)) : ?>
                    <div class="alerta-item alerta-info">
                        <i class="bi bi-check-circle"></i>
                        <div>
                            <h4>Sem alertas ativos</h4>
                            <p>Não existem alertas relevantes com base nos dados atuais.</p>
                        </div>
                    </div>
                <?php else : ?>
                    <?php foreach ($alertas_dashboard as $alerta) : ?>
                        <div class="alerta-item <?= h($alerta['classe']) ?>">
                            <i class="bi <?= h($alerta['icone']) ?>"></i>
                            <div>
                                <h4><?= h($alerta['titulo']) ?></h4>
                                <p><?= h($alerta['texto']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="backend-box">
                <h2>Ações rápidas</h2>

                <div class="acoes-rapidas">
                    <?php if (perfil_tem_acesso('equipamentos', 'criar')) : ?>
                    <a href="equipamentos/novo.php">
                        <i class="bi bi-plus-circle"></i>
                        Registar equipamento
                    </a>
                    <?php endif; ?>

                    <?php if (perfil_tem_acesso('localizacoes', 'ver')) : ?>
                    <a href="localizacoes/index.php">
                        <i class="bi bi-geo-alt"></i>
                        Gerir localizações
                    </a>
                    <?php endif; ?>

                    <?php if (perfil_tem_acesso('fornecedores', 'ver')) : ?>
                    <a href="fornecedores/index.php">
                        <i class="bi bi-truck"></i>
                        Gerir fornecedores
                    </a>
                    <?php endif; ?>

                    <?php if (perfil_tem_acesso('documentacao', 'ver')) : ?>
                    <a href="documentacao/index.php">
                        <i class="bi bi-file-earmark-text"></i>
                        Ver documentação
                    </a>
                    <?php endif; ?>

                    <?php if (perfil_tem_acesso('contratos', 'ver')) : ?>
                    <a href="contratos/index.php">
                        <i class="bi bi-shield-check"></i>
                        Ver garantias e contratos
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </section>


        <?php if (perfil_tem_acesso('mensagens', 'ver')) : ?>
        <section class="backend-box" id="mensagens-contacto">
            <div class="backend-section-header">
                <div>
                    <h2>Mensagens de contacto</h2>
                    <p>Mensagens enviadas através do formulário público do site.</p>
                </div>
            </div>

            <?php if (!empty($mensagem_dashboard_sucesso)) : ?>
                <div class="alert alert-success" role="alert">
                    <?= h($mensagem_dashboard_sucesso) ?>
                </div>
            <?php endif; ?>

            <div class="dashboard-kpis mensagens-kpis">
                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div>
                        <h3><?= $total_mensagens_contacto ?></h3>
                        <p>Total de mensagens</p>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="bi bi-envelope-exclamation"></i>
                    </div>
                    <div>
                        <h3><?= $mensagens_novas ?></h3>
                        <p>Novas</p>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="bi bi-envelope-check"></i>
                    </div>
                    <div>
                        <h3><?= $mensagens_lidas ?></h3>
                        <p>Lidas</p>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="bi bi-archive"></i>
                    </div>
                    <div>
                        <h3><?= $mensagens_arquivadas ?></h3>
                        <p>Arquivadas</p>
                    </div>
                </div>
            </div>

            <?php if ($mensagem_contacto_aberta) : ?>
                <div class="detalhe-card mt-4">
                    <div class="backend-section-header">
                        <div>
                            <h3><?= h($mensagem_contacto_aberta->nome) ?></h3>
                            <p>
                                <?= h($mensagem_contacto_aberta->email) ?>
                                · <?= h(data_mensagem_pt($mensagem_contacto_aberta->data_envio)) ?>
                            </p>
                        </div>

                        <span class="estado <?= classe_estado_mensagem($mensagem_contacto_aberta->estado) ?>">
                            <?= h($mensagem_contacto_aberta->estado) ?>
                        </span>
                    </div>

                    <p class="mensagem-contacto-texto">
                        <?= nl2br(h($mensagem_contacto_aberta->mensagem)) ?>
                    </p>

                    <div class="form-botoes">
                        <form method="post" action="index.php#mensagens-contacto" class="d-inline">
                            <input type="hidden" name="id_mensagem" value="<?= (int) $mensagem_contacto_aberta->id ?>">
                            <input type="hidden" name="acao_mensagem" value="arquivar">
                            <button type="submit" class="btn-secundario">
                                <i class="bi bi-archive"></i>
                                Arquivar
                            </button>
                        </form>

                        <a href="index.php#mensagens-contacto" class="btn-backend">
                            Fechar mensagem
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($mensagens_recentes)) : ?>
                <p class="sem-resultados d-block mt-4">Ainda não existem mensagens de contacto recebidas.</p>
            <?php else : ?>
                <div class="table-responsive mt-4">
                    <table class="tabela-backend">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($mensagens_recentes as $mensagem_contacto) : ?>
                                <tr>
                                    <td><?= h(data_mensagem_pt($mensagem_contacto->data_envio)) ?></td>
                                    <td><?= h($mensagem_contacto->nome) ?></td>
                                    <td><?= h($mensagem_contacto->email) ?></td>
                                    <td>
                                        <span class="estado <?= classe_estado_mensagem($mensagem_contacto->estado) ?>">
                                            <?= h($mensagem_contacto->estado) ?>
                                        </span>
                                    </td>
                                    <td class="acoes-tabela">
                                        <a href="index.php?mensagem=<?= (int) $mensagem_contacto->id ?>#mensagens-contacto" data-bs-toggle="tooltip" data-bs-title="Ver mensagem">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ($mensagem_contacto->estado === 'Nova') : ?>
                                            <form method="post" action="index.php#mensagens-contacto" class="d-inline">
                                                <input type="hidden" name="id_mensagem" value="<?= (int) $mensagem_contacto->id ?>">
                                                <input type="hidden" name="acao_mensagem" value="marcar_lida">
                                                <button type="submit" class="btn-acao-tabela" data-bs-toggle="tooltip" data-bs-title="Marcar como lida">
                                                    <i class="bi bi-check2-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form method="post" action="index.php#mensagens-contacto" class="d-inline">
                                            <input type="hidden" name="id_mensagem" value="<?= (int) $mensagem_contacto->id ?>">
                                            <input type="hidden" name="acao_mensagem" value="arquivar">
                                            <button type="submit" class="btn-acao-tabela" data-bs-toggle="tooltip" data-bs-title="Arquivar">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <?php endif; ?>

        <section class="backend-box">
            <div class="backend-section-header">
                <div>
                    <h2>Equipamentos críticos</h2>
                    <p>Equipamentos com maior impacto clínico ou necessidade de acompanhamento.</p>
                </div>
            </div>

            <?php if (empty($equipamentos_criticos)) : ?>
                <p class="sem-resultados d-block">Ainda não existem equipamentos críticos registados.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="tabela-backend">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Equipamento</th>
                                <th>Localização</th>
                                <th>Criticidade</th>
                                <th>Estado</th>
                                <th>Garantia</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($equipamentos_criticos as $equipamento) : ?>
                                <?php $estado_garantia = estado_garantia($equipamento->data_fim_garantia); ?>

                                <tr>
                                    <td><?= h($equipamento->codigo) ?></td>
                                    <td><?= h(trim($equipamento->designacao . ' ' . $equipamento->marca . ' ' . $equipamento->modelo)) ?></td>
                                    <td><?= h($equipamento->localizacao) ?></td>
                                    <td>
                                        <span class="estado <?= classe_dashboard($equipamento->criticidade) ?>">
                                            <?= h($equipamento->criticidade) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="estado <?= classe_dashboard($equipamento->estado) ?>">
                                            <?= h($equipamento->estado) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="estado <?= classe_dashboard($estado_garantia) ?>">
                                            <?= h($estado_garantia) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section class="backend-box">
            <h2>Resumo operacional</h2>
            <p>
                O inventário tem atualmente
                <strong><?= $total_equipamentos ?></strong> equipamento(s) registado(s),
                dos quais <strong><?= $equipamentos_ativos ?></strong> estão ativos,
                <strong><?= $equipamentos_manutencao ?></strong> estão em manutenção e
                <strong><?= $equipamentos_inativos ?></strong> estão inativos.
                Existem ainda <strong><?= $garantias_expiradas ?></strong> garantia(s)/contrato(s) expirado(s)
                e <strong><?= $equipamentos_sem_documentacao ?></strong> equipamento(s) ativo(s) sem documentação associada.
            </p>

            <div class="accordion dashboard-accordion" id="accordionDashboard">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Prioridade 1 — Regularizar garantias expiradas
                        </button>
                    </h2>

                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#accordionDashboard">
                        <div class="accordion-body">
                            Existem <?= $garantias_expiradas ?> garantia(s)/contrato(s) expirado(s).
                            Reveja os contratos associados aos equipamentos críticos e atualize a informação quando necessário.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Prioridade 2 — Completar documentação técnica
                        </button>
                    </h2>

                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionDashboard">
                        <div class="accordion-body">
                            Existem <?= $equipamentos_sem_documentacao ?> equipamento(s) ativo(s) sem documentação.
                            Associe manuais, certificados, relatórios técnicos ou outros documentos necessários.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Prioridade 3 — Monitorizar equipamentos em manutenção
                        </button>
                    </h2>

                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#accordionDashboard">
                        <div class="accordion-body">
                            Existem <?= $equipamentos_manutencao ?> equipamento(s) em manutenção.
                            Acompanhe estes registos para reduzir períodos de indisponibilidade clínica.
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

</div>

<?php
$ligacao = null;
include 'includes/footer.php';
?>
