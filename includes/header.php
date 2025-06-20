<?php
// Incluye config.php que determina la BASE_URL
require_once __DIR__ . '/config.php';

//Detectar si estamos en la landing o no
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : BASE_URL . 'index.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VFS</title>
    <!-- Bootstrap y estilos -->
    <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet" />
    <!-- Íconos Bootstrap Icons (opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fuentes de texto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body id="page-top">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <!-- LOGO -->
            <a class="navbar-brand me-4" href="<?= $is_landing ? '#page-top' : BASE_URL . 'index.php' ?>">
                <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" alt="Logo de la empresa" style="height: 50px;">
            </a>

            <!-- BOTÓN MENÚ COLAPSABLE -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENÚ COLAPSABLE -->
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <!-- ENLACES -->
                <ul class="navbar-nav me-auto my-2 my-lg-0 d-flex flex-column flex-lg-row gap-2 gap-lg-3">
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>#nosotros">Quiénes somos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>#contacto">Contacto</a></li>
                </ul>


                <!-- BOTONES (TRABAJA EN VFS/INICIA SESIÓN) -->
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <a href="<?= BASE_URL ?>postular.php" class="btn btn-primary text-center" style="min-width: 160px;">Trabaja en VFS</a>
                    <a href="<?= BASE_URL ?>admin/login.php" class="btn btn-primary text-center" style="min-width: 160px;">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </nav>