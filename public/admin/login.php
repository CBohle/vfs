<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php'; 

//Detectar si estamos en la landing o no
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : BASE_URL . 'index.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    // Validar que el usuario existe y está activo
    $stmt = $conexion->prepare("SELECT id, password, activo, rol_id FROM usuarios_admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $password_hash, $activo, $rol_id);
        $stmt->fetch();

        if (!$activo) {
            $error = "Cuenta inactiva. Contacta al administrador.";
        } elseif ($clave === $password_hash || password_verify($clave, $password_hash)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['usuario_id'] = $id;
            $_SESSION['rol_id'] = $rol_id;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>admin/adminlte/css/stylesLogin.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="body-login">
    <!-- LOGO -->
    <div style=" text-align: center; margin-bottom: 30px;">           
        <a href="<?= $is_landing ? '#page-top' : BASE_URL . 'index.php' ?>">
            <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" alt="Logo de la empresa" style="height: 70px;">
        </a>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Bloque izquierdo -->
            <div class="ed-login col-md-6 col-lg-6 order-lg-1">
                <div>
                    <h1 class="text-center" style="margin-top: 100px;">¡Bienvenido!</h1>
                    <h3 class="text-center">Inicia sesión para acceder al Panel de Administración VFS</h3>
                </div>
            </div>
            <!-- Bloque derecho -->
            <div class="login-box col-md-6 col-lg-4 text-white order-lg-1">
                <h2 class="text-center">Login</h2>
                <hr class="divider" />
                <!-- <p class="text-center">Panel de administración</p> -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label text-white">Correo</label>
                        <input type="email" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="clave" class="form-label text-white">Contraseña</label>
                        <input type="password" name="clave" class="form-control" required>
                    </div>
                    <br>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-secondary">Entrar</button>
                    </div>
                    <br>
                    <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>