<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configura la respuesta como JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Validación reCAPTCHA ---
    $secretKey = '6LfJGWMrAAAAAF2Wz68UIcy4pu4gTWKb3qVzV-1j';
    $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (!$captchaResponse) {
        echo json_encode(['success' => false, 'error' => 'Debe completar el reCAPTCHA.']);
        exit;
    }

    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $captchaResponse
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verifyUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response);


    if (!$responseData->success) {
        echo json_encode(['success' => false, 'error' => 'Falló la validación del reCAPTCHA.']);
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
        $mail->CharSet = 'UTF-8';
        // Ahora enviar el correo a los administradores
        
        $stmt = $conexion->prepare("
            SELECT DISTINCT ua.email 
            FROM usuarios_admin ua
            JOIN permisos p ON ua.rol_id = p.rol_id
            WHERE ua.activo = 1
            AND p.modulo = 'mensajes'
            AND p.accion = 'aviso'
        ");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de roles para dar avisos']);
            exit;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo json_encode(['success' => false, 'error' => 'Error al ejecutar la consulta de roles para dar avisos']);
            exit;
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP(); // 💡 Asegúrate de hacerlo una sola vez
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_CONTACTO_USER'];
        $mail->Password   = $_ENV['MAIL_CONTACTO_PASS'];
        $mail->SMTPSecure = $_ENV['MAIL_SECURE'];
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->setFrom($_ENV['MAIL_CONTACTO_USER'], 'VFS-Admin');

        while ($admin = $result->fetch_assoc()) {
            try {
                $mail->clearAllRecipients();
                $mail->addAddress($admin['email']);
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
        try {
            $mail->clearAllRecipients(); // Limpia los destinatarios anteriores
            $mail->addAddress($email); // Correo del remitente
            $mail->Subject = 'Confirmación de recepción de mensaje de atención personalizada';
            $mail->isHTML(true);
            $mail->Body = "
            <p>Estimado(a) <strong>$nombre $apellido</strong>,</p>
            <p>Gracias por contactarse con nuestro equipo. Hemos recibido su mensaje correctamente y será revisado a la brevedad por uno de nuestros especialistas.</p>
            <p><strong>Resumen de su mensaje de contacto:</strong></p>
            <hr>
            <p><strong>Nombre:</strong> $nombre $apellido<br>
            <strong>Email:</strong> $email<br>
            <strong>Teléfono:</strong> $telefono<br>
            <strong>Servicio:</strong> $servicio</p>
            <p><strong>Mensaje:</strong><br>$mensaje</p>
            <hr>
            <p>Saludos cordiales,<br>
            Equipo de VFS</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo al contacto: {$mail->ErrorInfo}");
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
