<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que sea AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        echo json_encode(['success' => false, 'error' => 'Solo se aceptan solicitudes AJAX']);
        exit;
    }

    // Validación de reCAPTCHA
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    if (!$captcha) {
        echo json_encode(['success' => false, 'error' => 'Por favor, verifica el reCAPTCHA.']);
        exit;
    }

    $secretKey = '6LfJGWMrAAAAAF2Wz68UIcy4pu4gTWKb3qVzV-1j';
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $data = [
        'secret' => $secretKey,
        'response' => $captcha,
        'remoteip' => $remoteIp
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $captchaData = json_decode($response, true);


    if (!$captchaData['success']) {
        echo json_encode(['success' => false, 'error' => 'Error en la validación del captcha.']);
        exit;
    }

    // Recoger datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $rut = trim($_POST['rut'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $comuna = trim($_POST['comuna'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $estudios = trim($_POST['estudios'] ?? '');
    $institucion = trim($_POST['institucion'] ?? '');
    $ano_titulacion = (int)($_POST['ano_titulacion'] ?? 0);
    $formacion_tasacion = ($_POST['formacion_tasacion'] ?? '') === 'Sí' ? 1 : 0;
    $detalle_formacion = trim($_POST['campo_especificar'] ?? '');
    $mapa_experiencia = [
        "Sin experiencia" => 0,
        "Menos de 1 año" => 0,
        "1 a 3 años" => 2,
        "3 a 5 años" => 4,
        "Más de 5 años" => 5
    ];
    $anos_experiencia_tasacion = $mapa_experiencia[$_POST['ano_experiencia']] ?? 0;
    $otra_empresa = trim($_POST['otra_empresa'] ?? '');
    $disponibilidad_comuna = ($_POST['disponibilidad_comunal'] ?? '') === 'Sí' ? 1 : 0;
    $disponibilidad_region = ($_POST['disponibilidad_regional'] ?? '') === 'Sí' ? 1 : 0;
    $movilizacion_propia = ($_POST['movilizacion'] ?? '') === 'Sí' ? 1 : 0;

    // Validar archivo CV
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'No se subió el archivo correctamente.']);
        exit;
    }

    $cv_nombre = $_FILES['cv']['name'];
    $cv_temp = $_FILES['cv']['tmp_name'];
    $cv_tamano = $_FILES['cv']['size'];
    $cv_ext = strtolower(pathinfo($cv_nombre, PATHINFO_EXTENSION));
    $ext_permitidas = ['pdf', 'doc', 'docx'];

    if (!in_array($cv_ext, $ext_permitidas)) {
        echo json_encode(['success' => false, 'error' => 'Formato de archivo no permitido.']);
        exit;
    }
    if ($cv_tamano > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'error' => 'El archivo supera el límite de 2MB.']);
        exit;
    }

    $nombre_cv_final = uniqid() . '.' . $cv_ext;
    $ruta_absoluta = __DIR__ . '/../../uploads/cv/' . $nombre_cv_final;
    $ruta_relativa = 'uploads/cv/' . $nombre_cv_final;

    if (!move_uploaded_file($cv_temp, $ruta_absoluta)) {
        echo json_encode(['success' => false, 'error' => 'Error al guardar el archivo.']);
        exit;
    }

    // Insertar en la base de datos
    $stmt = $conexion->prepare("
        INSERT INTO curriculum (
            nombre, apellido, fecha_nacimiento, rut, email, telefono,
            direccion, comuna, region, estudios, institucion_educacional,
            ano_titulacion, formacion_tasacion, detalle_formacion,
            anos_experiencia_tasacion, otra_empresa,
            disponibilidad_comuna, disponibilidad_region, movilizacion_propia,
            archivo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error en la base de datos (prepare).']);
        exit;
    }

    $stmt->bind_param(
        "sssssssssssiisisiiis",
        $nombre,
        $apellido,
        $fecha_nacimiento,
        $rut,
        $email,
        $telefono,
        $direccion,
        $comuna,
        $region,
        $estudios,
        $institucion,
        $ano_titulacion,
        $formacion_tasacion,
        $detalle_formacion,
        $anos_experiencia_tasacion,
        $otra_empresa,
        $disponibilidad_comuna,
        $disponibilidad_region,
        $movilizacion_propia,
        $ruta_relativa
    );

    if ($stmt->execute()) {
        $stmt->close();

        // Enviar correos a los administradores
        $stmt_admins = $conexion->prepare("
            SELECT DISTINCT ua.email 
            FROM usuarios_admin ua
            JOIN permisos p ON ua.rol_id = p.rol_id
            WHERE ua.activo = 1
            AND p.modulo = 'postulaciones'
            AND p.accion = 'aviso'
        ");
        $stmt_admins->execute();
        $result = $stmt_admins->get_result();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_CONTACTO_USER'];
            $mail->Password = $_ENV['MAIL_CONTACTO_PASS'];
            $mail->SMTPSecure = $_ENV['MAIL_SECURE'];
            $mail->Port       = $_ENV['MAIL_PORT'];
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($_ENV['MAIL_CONTACTO_USER'], 'VFS-Admin');

            while ($admin = $result->fetch_assoc()) {
                $mail->addAddress($admin['email']);
            }

            $mail->Subject = 'Nueva postulación recibida';
            $mail->Body = "
            Has recibido una nueva postulación con los siguientes datos:

            Nombre: $nombre $apellido
            Fecha de Nacimiento: $fecha_nacimiento
            RUT: $rut
            Email: $email
            Teléfono: $telefono
            Dirección: $direccion
            Comuna: $comuna
            Región: $region
            Estudios: $estudios
            Institución: $institucion
            Año de Titulación: $ano_titulacion
            Formación en Tasación: " . ($formacion_tasacion ? 'Sí' : 'No') . "
            Detalles de la Formación: $detalle_formacion
            Años de Experiencia: $anos_experiencia_tasacion
            Otra Empresa: $otra_empresa
            Disponibilidad Comuna: " . ($disponibilidad_comuna ? 'Sí' : 'No') . "
            Disponibilidad Región: " . ($disponibilidad_region ? 'Sí' : 'No') . "
            Movilización Propia: " . ($movilizacion_propia ? 'Sí' : 'No') . "

            El CV adjunto ha sido recibido.
            ";

            $mail->addAttachment($ruta_absoluta, $cv_nombre);
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo a administrador: {$mail->ErrorInfo}");
            echo json_encode(['success' => false, 'error' => 'Error al enviar correo a los administradores']);
            exit;
        }

        echo json_encode(['success' => true, 'mensaje' => 'Postulación enviada correctamente.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos.']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Solicitud no válida.']);
exit;
