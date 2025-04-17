<?php
// Procesa la información del formulario de contacto, guardar en DB.

// Conexión a la base de datos
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas
    $nombre = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST['message']));

    // Validar campos
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico inválido.";
    } else {
        // Guardar en la base de datos (opcional)
        $stmt = $conn->prepare("INSERT INTO mensajes (nombre, email, mensaje, fecha) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $nombre, $email, $mensaje);

        if ($stmt->execute()) {
            $success = "¡Gracias por tu mensaje! Te responderemos pronto.";
        } else {
            $error = "Ocurrió un error al enviar el mensaje.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Contacto - Empresa</title>
  <link href="/assets/css/styles.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="padding: 50px; max-width: 600px; margin: auto;">
    <h2>Resultado del mensaje</h2>
    
    <?php if (isset($error)): ?>
      <div style="color: red;"><?= $error ?></div>
    <?php elseif (isset($success)): ?>
      <div style="color: green;"><?= $success ?></div>
    <?php endif; ?>

    <br>
    <a href="index.php" style="text-decoration: none; color: #007bff;">← Volver al inicio</a>
  </div>
</body>
</html>
