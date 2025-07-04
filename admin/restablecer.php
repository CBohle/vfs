<?php
include __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';

$token = trim($_GET['token'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM usuarios_admin WHERE reset_token = ? AND token_expira > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $error = "El enlace es inválido o ha expirado.";
    header('Location: error_token.php');
    exit;
}

?>

<!-- VISTA RESTABLECER -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restablecer - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>admin/adminlte/css/stylesLogin.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
        <!-- Fuentes de Google-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body class="body-login">
    <!-- <div class="body-login"> -->
    <div class="background-image"></div>
    <!-- LOGO -->
    <div class="logoLogin text-center my-3">
        <a href="<?= $is_landing ? '#page-top' : BASE_URL . 'index.php' ?>">
            <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" class="logoLogin" alt="Logo de la empresa" style="height: 70px;">
        </a>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center mx-sm-4">
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
                        <input type="password" id="password" name="password" class="form-control" required>
                        <small id="passwordHelp" class="form-text text-warning"></small>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Confirmar contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <small id="confirmHelp" class="form-text text-warning"></small>
                    </div>
                    <br>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-secondary">Cambiar contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            const helpPass = document.getElementById('passwordHelp');
            const helpConfirm = document.getElementById('confirmHelp');

            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            helpPass.textContent = '';
            helpConfirm.textContent = '';

            if (!regex.test(password)) {
                helpPass.textContent = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.';
                e.preventDefault();
            } else if (password !== confirm) {
                helpConfirm.textContent = 'Las contraseñas no coinciden.';
                e.preventDefault();
            }
        });
    </script>

</body>

</html>


