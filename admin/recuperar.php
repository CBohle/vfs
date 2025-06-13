<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

//Detectar si estamos en la landing o no
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : BASE_URL . 'index.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperación - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>admin/adminlte/css/stylesLogin.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="body-recuperar">
    <!-- LOGO -->
    <div style=" text-align: center; margin-bottom: 30px;">
        <a href="<?= $is_landing ? '#page-top' : BASE_URL . 'index.php' ?>">
            <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" alt="Logo de la empresa" style="height: 70px;">
        </a>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Bloque derecho -->
            <div class="login-box col-md-6 col-lg-4 text-white order-lg-1">
                <h2 class="text-center">Recuperar contraseña</h2>
                <hr class="divider" />
                <p class="text-center">¿No sabe su contraseña? Restablézcala después de confirmar su dirección de correo electrónico.</p>
                <br>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert"><?= $error ?></div>
                <?php endif; ?>

                <!-- FORMULARIO DE RECUPERACIÓN -->
                <form method="POST" action="procesar_recuperar.php">
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Ingresa tu correo electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <br>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-secondary">Enviar enlace</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>