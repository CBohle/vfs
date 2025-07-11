<?php
include __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';

// Detectar si estamos en la landing o no
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : BASE_URL . 'index.php';

$mensaje = "";
$error = "";

// Función para cambiar contraseña
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $pass = $_POST["password"];
    $pass2 = $_POST["confirm_password"];

    if ($pass !== $pass2) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $pass)) {
        $error = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM usuarios_admin WHERE reset_token = ? AND token_expira > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "Enlace inválido o expirado.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios_admin SET password = ?, reset_token = NULL, token_expira = NULL WHERE id = ?");
            $stmt->execute([$hash, $user["id"]]);

            $mensaje = "Tu contraseña ha sido actualizada con éxito.<br><br><a class='btn btn-light' href='login.php'>Iniciar sesión</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guardar_Nueva - Admin</title>
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
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensaje)) : ?>
                    <div class="alert alert-success text-center">
                        <?= $mensaje ?>
                    </div>
                <?php endif; ?>

                <hr class="divider" />
            </div>
        </div>
    </div>
</body>

</html>


