<?php
include __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';


$token = $_GET["token"] ?? null;

$stmt = $pdo->prepare("SELECT * FROM usuarios_admin WHERE reset_token = ? AND token_expira > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $error = "Enlace inválido o expirado.";
}
?>

<!-- VISTA RESTABLECER -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Restablecer - Admin</title>
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
                <h2 class="text-center">Ingresa tu nueva contraseña</h2>
                <hr class="divider" />
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- FORMULARIO DE NUEVA CONTRASEÑA -->
                <form method="POST" action="guardar_nueva.php">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Confirmar contraseña</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <br>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-secondary">Cambiar contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>