<?php
require_once __DIR__ . '/../private/includes/funcoes.php';

// Termina a sessão ativa e redireciona o utilizador para a página de login.
logout_and_redirect(BASE_URL . '/public/login.php');