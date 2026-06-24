<!-- Sidebar -->
<aside class="backend-sidebar">
    <div class="sidebar-logo">
        <img src="<?php echo BASE_URL; ?>/assets/img/medcontrol-logo2.png" alt="Logótipo da MedControl">
        <h2><?php echo APP_NAME; ?></h2>
    </div>

    <div class="sidebar-utilizador">
        <span><?php echo htmlspecialchars(nome_utilizador(), ENT_QUOTES, 'UTF-8'); ?></span>
        <small><?php echo htmlspecialchars(perfil_nome(), ENT_QUOTES, 'UTF-8'); ?></small>
    </div>

    <nav class="sidebar-menu">
        <?php if (perfil_tem_acesso('dashboard')) : ?>
            <a href="<?php echo BASE_URL; ?>/private/index.php" class="<?php echo (// Identificação da página atual para destacar o item correspondente no menu lateral.
$pagina_atual == 'dashboard') ? 'ativo' : ''; ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if (perfil_tem_acesso('equipamentos')) : ?>
            <a href="<?php echo BASE_URL; ?>/private/equipamentos/index.php" class="<?php echo ($pagina_atual == 'equipamentos') ? 'ativo' : ''; ?>">
                <i class="bi bi-hospital"></i>
                <span>Equipamentos</span>
            </a>
        <?php endif; ?>

        <?php if (perfil_tem_acesso('localizacoes')) : ?>
            <a href="<?php echo BASE_URL; ?>/private/localizacoes/index.php" class="<?php echo ($pagina_atual == 'localizacoes') ? 'ativo' : ''; ?>">
                <i class="bi bi-geo-alt"></i>
                <span>Localizações</span>
            </a>
        <?php endif; ?>

        <?php if (perfil_tem_acesso('fornecedores')) : ?>
            <a href="<?php echo BASE_URL; ?>/private/fornecedores/index.php" class="<?php echo ($pagina_atual == 'fornecedores') ? 'ativo' : ''; ?>">
                <i class="bi bi-truck"></i>
                <span>Fornecedores</span>
            </a>
        <?php endif; ?>

        <?php if (perfil_tem_acesso('documentacao')) : ?>
            <a href="<?php echo BASE_URL; ?>/private/documentacao/index.php" class="<?php echo ($pagina_atual == 'documentacao') ? 'ativo' : ''; ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Documentação</span>
            </a>
        <?php endif; ?>

        <?php if (perfil_tem_acesso('contratos')) : ?>
            <a href="<?php echo BASE_URL; ?>/private/contratos/index.php" class="<?php echo ($pagina_atual == 'contratos') ? 'ativo' : ''; ?>">
                <i class="bi bi-shield-check"></i>
                <span>Contratos</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
