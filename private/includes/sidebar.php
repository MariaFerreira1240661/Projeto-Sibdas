<!-- Sidebar -->
<aside class="backend-sidebar">
    <div class="sidebar-logo">
        <img src="<?php echo BASE_URL; ?>/assets/img/medcontrol-logo2.png" alt="Logótipo da MedControl">
        <h2><?php echo APP_NAME; ?></h2>
    </div>

    <nav class="sidebar-menu">
        <a href="<?php echo BASE_URL; ?>/private/index.php" class="<?php echo ($pagina_atual == 'dashboard') ? 'ativo' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/private/equipamentos/index.php" class="<?php echo ($pagina_atual == 'equipamentos') ? 'ativo' : ''; ?>">
            <i class="bi bi-hospital"></i>
            <span>Equipamentos</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/private/localizacoes/index.php" class="<?php echo ($pagina_atual == 'localizacoes') ? 'ativo' : ''; ?>">
            <i class="bi bi-geo-alt"></i>
            <span>Localizações</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/private/fornecedores/index.php" class="<?php echo ($pagina_atual == 'fornecedores') ? 'ativo' : ''; ?>">
            <i class="bi bi-truck"></i>
            <span>Fornecedores</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/private/documentacao/index.php" class="<?php echo ($pagina_atual == 'documentacao') ? 'ativo' : ''; ?>">
            <i class="bi bi-file-earmark-text"></i>
            <span>Documentação</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/private/contratos/index.php" class="<?php echo ($pagina_atual == 'contratos') ? 'ativo' : ''; ?>">
            <i class="bi bi-shield-check"></i>
            <span>Contratos</span>
        </a>
    </nav>
</aside>