<?php

// Login con validación por base de datos (comentado)
// require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave   = $_POST['clave'] ?? '';

    // === VALIDACIÓN SIMULADA (ACTIVA POR AHORA) ===
    if ($usuario === 'admin' && $clave === '1234') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }

    // === VALIDACIÓN CON BASE DE DATOS (COMENTADO) ===
    /*
    $stmt = $conn->prepare("SELECT id, clave FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $clave_hash);
        $stmt->fetch();

        if (password_verify($clave, $clave_hash)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['usuario_id'] = $id;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
    */
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Admin</title>
    <!-- Bootstrap y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>admin/adminlte/css/stylesAdmin.css" rel="stylesheet" />
    <!-- Fuentes de texto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center ">
            <!-- Recuadro bienvenida -->
            <div class="ed-login  col-md-6 col-lg-6 order-lg-1">
                <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" alt="Logo VFS" class="img-fluid d-block mx-auto margin-bottom:40px" style="max-width: 50px;">
                <div>
                    <h1 class="text-center">¡Bienvenido!</h1>
                    <h3 class="text-center">Inicia sesión para acceder al Panel de Administración VFS</h3>
                </div>
            </div>
            <!-- Recuadro login -->
            <div class="login-box col-md-6 col-lg-4 text-white order-lg-1">
                <h2 class="text-center">Login</h2>
                <hr class="divider" />
                <p class="text-center">Panel de administración</p>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label text-white">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="clave" class="form-label text-white">Contraseña</label>
                        <input type="password" name="clave" class="form-control" required>
                    </div>
                    <br>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-secondary">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>