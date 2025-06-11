<?php
// Desactivar errores visibles (opcional)
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que sea AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        echo json_encode(['success' => false, 'error' => 'Solo se aceptan solicitudes AJAX']);
        exit;
    }

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
        "Sin experiencia" => 0, "Menos de 1 año" => 0,
        "1 a 3 años" => 2, "3 a 5 años" => 4, "Más de 5 años" => 5
    ];
    $anos_experiencia_tasacion = $mapa_experiencia[$_POST['ano_experiencia']] ?? 0;
    $otra_empresa = trim($_POST['otra_empresa'] ?? '');
    $disponibilidad_comuna = ($_POST['disponibilidad_comunal'] ?? '') === 'Sí' ? 1 : 0;
    $disponibilidad_region = ($_POST['disponibilidad_regional'] ?? '') === 'Sí' ? 1 : 0;
    $movilizacion_propia = ($_POST['movilizacion'] ?? '') === 'Sí' ? 1 : 0;

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

    $stmt = $conexion->prepare("
        INSERT INTO curriculum (
            nombre, apellido, fecha_nacimiento, rut, email, telefono,
            direccion, comuna, region, estudios, institucion_educacional,
            ano_titulacion, formacion_tasacion, formacion_tasacion_descripcion,
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
        "sssssssssssisisiisss",
        $nombre, $apellido, $fecha_nacimiento, $rut, $email, $telefono,
        $direccion, $comuna, $region, $estudios, $institucion, $ano_titulacion,
        $formacion_tasacion, $formacion_tasacion_descripcion,
        $anos_experiencia_tasacion, $otra_empresa,
        $disponibilidad_comuna, $disponibilidad_region, $movilizacion_propia,
        $ruta_relativa
    );

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos.']);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Solicitud no válida.']);
exit;
