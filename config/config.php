<?php
// --------------------------------------------------------------------
// CONFIGURAÇÕES GLOBAIS DA APLICAÇÃO
// --------------------------------------------------------------------

define('APP_NAME', 'MedControl');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', '© 2026 MedControl - Sistema de Inventário Hospitalar');

// --------------------------------------------------------------------
// CAMINHO BASE DO PROJETO
// --------------------------------------------------------------------
// Este valor deve corresponder ao nome da pasta do projeto dentro do Laragon.
// Exemplo: C:\laragon\www\medcontrol  →  /medcontrol

define('BASE_URL', '/sibdas/1240661/medcontrol');
// --------------------------------------------------------------------
// CONFIGURAÇÕES DA BASE DE DADOS
// --------------------------------------------------------------------

define('MYSQL_HOST', 'vsgate-s1.dei.isep.ipp.pt');
define('MYSQL_PORT', '10464');
define('MYSQL_DATABASE','db1240661' );
define('MYSQL_USERNAME', '1240661');
define('MYSQL_PASSWORD', 'ferreira_661');


// --------------------------------------------------------------------
// SEGURANÇA - Encriptação de IDs nas páginas de edição
// --------------------------------------------------------------------
define('OPENSSL_METHOD', 'AES-256-CBC');
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa');
define('OPENSSL_IV', 'BzKAbjuREsHgnw56');
