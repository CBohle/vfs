<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configura la respuesta como JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Validación reCAPTCHA ---
    $secretKey = '6LdyYy0rAAAAAHR192gnUWvBwEXWJkw57eCfuC0N';
    $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (!$captchaResponse) {
        echo json_encode(['success' => false, 'error' => 'Debe completar el reCAPTCHA.']);
        exit;
    }

    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $captchaResponse);
    $responseData = json_decode($response);

    if (!$responseData->success) {
        echo json_encode(['success' => false, 'error' => 'Fallo la validación del reCAPTCHA.']);
        exit;
    }
    // --- Fin validación reCAPTCHA ---

    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $servicio = trim($_POST['servicio'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre && $apellido && $email && $telefono && $servicio && $mensaje) {
        $estado = 'pendiente';

        // Guardar el mensaje en la base de datos
        $stmt = $conexion->prepare("INSERT INTO mensajes (nombre, apellido, email, telefono, servicio, mensaje, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conexion->error]);
            exit;
        }

        $stmt->bind_param("sssssss", $nombre, $apellido, $email, $telefono, $servicio, $mensaje, $estado);

        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Error al ejecutar la consulta: ' . $stmt->error]);
            $stmt->close();
            exit;
        }

        $stmt->close();

        // Enviar correo a un destinatario fijo
        $mail = new PHPMailer(true);
        // Ahora enviar el correo a los administradores
        $stmt = $conexion->prepare("SELECT email FROM usuarios_admin WHERE rol_id IN (1, 5) AND activo = 1");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de administradores']);
            exit;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo json_encode(['success' => false, 'error' => 'Error al ejecutar la consulta de administradores']);
            exit;
        }

        while ($admin = $result->fetch_assoc()) {
            try {

                $mail->isSMTP();
                $mail->clearAllRecipients();  
                $mail->Host = 'mail.vfs.cl';  
                $mail->SMTPAuth = true;
                $mail->Username = 'contacto@vfs.cl';  
                $mail->Password = 'ContactoVFS1234.';  
                $mail->SMTPSecure = 'ssl';  
                $mail->Port = 465;  
                
                $mail->setFrom('contacto@vfs.cl', 'VFS-Admin');
                // Aquí asumo que tienes una variable $admin con email. Si no, asigna un email fijo.
                $mail->addAddress($admin['email']);  
                $mail->Subject = 'Nuevo mensaje de contacto';
                $mail->Body = "Has recibido un nuevo mensaje de contacto de:\n\nNombre: $nombre $apellido\nEmail: $email\nTeléfono: $telefono\nServicio: $servicio\n\nMensaje:\n$mensaje";

                $mail->send();
            } catch (Exception $e) {
                error_log("Error al enviar correo a administrador: {$mail->ErrorInfo}");
                echo json_encode([
                    'success' => false,
                    'error' => 'Error al enviar correo a los administradores: ' . $mail->ErrorInfo
                ]);
                exit;
            }
        }

        echo json_encode([
            'success' => true,
            'mensaje' => 'Mensaje enviado correctamente',
            'fecha' => date('d-m-Y H:i')
        ]);
        exit;

    } else {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}
?>
