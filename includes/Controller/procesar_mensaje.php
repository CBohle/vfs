<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configura la respuesta como JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.vfs.cl';  // Usando el servidor SMTP en duro
            $mail->SMTPAuth = true;
            $mail->Username = 'contacto@vfs.cl';  // Tu correo
            $mail->Password = 'ContactoVFS1234.';  // Tu contraseña
            $mail->SMTPSecure = 'ssl';  // Usando SSL
            $mail->Port = 465;  // Puerto 465 para SSL

            $mail->setFrom('contacto@vfs.cl', 'VFS-Admin');
            $mail->addAddress($admin['email']);  // Correo fijo
            $mail->Subject = 'Respuesta a tu consulta en VFS';
            $mail->Body    = "test";

            // Enviar el correo
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");  // Log en caso de error
            echo json_encode([
                'success' => false,
                'error' => 'Error al enviar correo: ' . $mail->ErrorInfo
            ]);
            exit;
        }

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

        // Enviar correos a los administradores
        while ($admin = $result->fetch_assoc()) {
            try {
                $mail->isSMTP();
                $mail->clearAllRecipients();  // Limpiar destinatarios previos
                $mail->Host = 'mail.vfs.cl';  // Usando el servidor SMTP en duro
                $mail->SMTPAuth = true;
                $mail->Username = 'contacto@vfs.cl';  // Tu correo
                $mail->Password = 'ContactoVFS1234.';  // Tu contraseña
                $mail->SMTPSecure = 'ssl';  // Usando SSL
                $mail->Port = 465;  // Puerto 465 para SSL
        
                $mail->addAddress($admin['email']);  // Enviar al email del administrador
                $mail->Subject = 'Nuevo mensaje de contacto';
                $mail->Body = "Has recibido un nuevo mensaje de contacto de:\n\nNombre: $nombre $apellido\nEmail: $email\nTeléfono: $telefono\nServicio: $servicio\n\nMensaje:\n$mensaje";

                // Enviar el correo
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

        // Respuesta final exitosa
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
