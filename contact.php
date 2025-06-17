
<?php
// require_once '/includes/db.php'; // ← Activar cuando se defina la conexión $conn

require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación reCAPTCHA
    $recaptchaSecret = '6LfJGWMrAAAAAF2Wz68UIcy4pu4gTWKb3qVzV-1j';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    $recaptchaValid = false;
    if (!empty($recaptchaResponse)) {
        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}"
        );
        $captchaSuccess = json_decode($verify);
        $recaptchaValid = $captchaSuccess->success ?? false;
    }

    if (!$recaptchaValid) {
        $error = "Error de verificación reCAPTCHA. Por favor, inténtalo nuevamente.";
    } else {
        // Entradas
        $nombre   = htmlspecialchars(trim($_POST['name']));
        $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $telefono = htmlspecialchars(trim($_POST['telefono']));
        $empresa  = htmlspecialchars(trim($_POST['empresa']));
        $region   = htmlspecialchars(trim($_POST['region']));
        $mensaje  = htmlspecialchars(trim($_POST['mensaje']));

        // Validaciones
        if (empty($nombre) || empty($email) || empty($telefono) || empty($empresa) || empty($region) || empty($mensaje)) {
            $error = "Todos los campos son obligatorios.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Correo electrónico inválido.";
        } else {
            // Simulación de éxito mientras no se implemente la conexión
            $success = "¡Gracias por tu mensaje!";

            /*
            // Guardar en la base de datos cuando esté lista
            $stmt = $conn->prepare("INSERT INTO mensajes (nombre, email, telefono, empresa, region, mensaje, fecha) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $nombre, $email, $telefono, $empresa, $region, $mensaje);

            if ($stmt->execute()) {
                $success = "¡Gracias por tu mensaje! Te responderemos pronto.";
            } else {
                $error = "Ocurrió un error al enviar el mensaje.";
            }

            $stmt->close();
            */
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Contacto - Empresa</title>
  <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet">
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
    <a href="<?= BASE_URL ?>index.php" style="text-decoration: none; color: #007bff;">← Volver al inicio</a>
  </div>
</body>
</html>
