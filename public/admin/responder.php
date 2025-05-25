<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Si usas PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje_id = intval($_POST['mensaje_id'] ?? 0);
    $respuesta = trim($_POST['respuesta'] ?? '');
    $usuario_admin_id = 1; // Puedes usar el ID del admin de la sesión

    if ($mensaje_id <= 0 || empty($respuesta)) {
        die('Datos inválidos');
    }

    // Obtener datos del mensaje
    $stmt = $conexion->prepare("SELECT email, nombre, apellido FROM mensajes WHERE id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    if (!$cliente) {
        die('Mensaje no encontrado');
    }

    // Insertar la respuesta
    $stmt = $conexion->prepare("INSERT INTO respuestas (mensaje_id, respuesta, usuario_admin_id, fecha_respuesta) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $mensaje_id, $respuesta, $usuario_admin_id);
    $stmt->execute();

    // Cambiar estado a respondido
    $stmt = $conexion->prepare("UPDATE mensajes SET estado = 'respondido' WHERE id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();

    // Enviar correo al cliente
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambiar por tu SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'parmait.detalles@gmail.com'; // Tu correo
        $mail->Password = 'lcrl uqjg wfbn apla'; // Tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('parmait.detalles@gmail.com', 'VFS-Admin');
        $mail->addAddress($cliente['email'], $cliente['nombre'] . ' ' . $cliente['apellido']);
        $mail->Subject = 'Respuesta a tu consulta en VFS';
        $mail->Body = "Hola {$cliente['nombre']},\n\nEsta es nuestra respuesta a tu consulta:\n\n$respuesta\n\nGracias por contactarnos.";

        $mail->send();
        header('Location: mensajes.php?respuesta=enviada');
        exit;
    } catch (Exception $e) {
        die("Error al enviar correo: {$mail->ErrorInfo}");
    }
} else {
    header('Location: mensajes.php?error=metodo');
    exit;
}
?>
