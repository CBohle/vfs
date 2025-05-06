<?php
// Login con validación por base de datos (comentado)
// require_once __DIR__ . '/../../includes/db.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: "Segoe UI", sans-serif;
        }
        .login-box {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 100px;
        }
        h2 {
            font-weight: 500;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="login-box col-md-6 col-lg-4">
            <h2 class="text-center">Panel de administración</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" name="clave" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
