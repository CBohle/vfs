<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Envía un correo desde una cuenta específica.
 *
 * @param string $destinatario Email del destinatario
 * @param string $asunto Asunto del correo
 * @param string $cuerpoHTML Contenido HTML del correo
 * @param string $remitente Tipo de remitente: 'soporte' o 'contacto'
 * @param string|null $cuerpoAlt Texto alternativo (opcional)
 * @return bool
 */
function enviarCorreo($destinatario, $asunto, $cuerpoHTML, $remitente = 'soporte', $cuerpoAlt = null)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->SMTPSecure = $_ENV['MAIL_SECURE'];
        $mail->CharSet = 'UTF-8';

        // Autenticación y remitente según tipo
        switch (strtolower($remitente)) {
            case 'contacto':
                $mail->Username = $_ENV['MAIL_CONTACTO_USER'];
                $mail->Password = $_ENV['MAIL_CONTACTO_PASS'];
                $mail->setFrom($_ENV['MAIL_CONTACTO_USER'], 'Contacto VFS');
                break;

            case 'soporte':
            default:
                $mail->Username = $_ENV['MAIL_SOPORTE_USER'];
                $mail->Password = $_ENV['MAIL_SOPORTE_PASS'];
                $mail->setFrom($_ENV['MAIL_SOPORTE_USER'], 'Soporte VFS');
                break;
        }

        // Destinatario y contenido
        $mail->addAddress($destinatario);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpoHTML;
        $mail->AltBody = $cuerpoAlt ?: strip_tags($cuerpoHTML);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo desde $remitente: {$mail->ErrorInfo}");
        return false;
    }
}
