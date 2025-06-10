<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';

//Detectar si estamos en la landing o no
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : BASE_URL . 'index.php';

// Función para enviar un correo con PHPMailer a través de WebMail
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $stmt = $pdo->prepare("SELECT id FROM usuarios_admin WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("UPDATE usuarios_admin SET reset_token = ?, token_expira = ? WHERE email = ?");
        $stmt->execute([$token, $expira, $email]);

        $enlace = "http://localhost/vfs/public/admin/restablecer.php?token=$token";

        // Enviar email con PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.vfs.cl';
            $mail->SMTPAuth = true;
            $mail->Username = 'soporte@vfs.cl';
            $mail->Password = 'SoporteVFS1234.';
            $mail->SMTPSecure = 'ssl'; // o 'ssl' si usas puerto 465
            $mail->Port = 465; // o 465

            $mail->setFrom('soporte@vfs.cl', 'Soporte VFS');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña:<br><a href='$enlace'>$enlace</a>";

            $mail->send();
            $mensaje = "Si el correo está registrado, recibirás un enlace.";
        } catch (Exception $e) {
            $mensaje = "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        $mensaje = "Si el correo está registrado, recibirás un enlace.";
    }
}
?>
<!-- VISTA DE LA PÁGINA -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>avisoEnvioMail</title>
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
                <?php if (!empty($mensaje)) : ?>
                    <h2 class="text-center"><?= htmlspecialchars($mensaje) ?></h2>
                <?php endif; ?>
                <hr class="divider" />

            </div>
        </div>
    </div>
</body>

</html>