<?php
require_once __DIR__ . '/funcoes.php';

redirect_if_not_logged();
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>

    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/img/medcontrol-logo2.png" type="image/png">
    <link href="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap-icons.min.css">


    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/datatables/datatables.min.css">

    <!-- jQuery -->
    <script src="<?php echo BASE_URL; ?>/assets/jquery/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="<?php echo BASE_URL; ?>/assets/datatables/datatables.min.js"></script>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/1240661.css?v=2">
    
</head>

<body class="backend-body">