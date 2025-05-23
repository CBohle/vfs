<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $rut = trim($_POST['rut'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $comuna = trim($_POST['comuna'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $estudio = trim($_POST['estudios'] ?? '');
    $institucion = trim($_POST['institucion'] ?? '');
    $ano_titulacion = (int)($_POST['ano_titulacion'] ?? 0);
    $formacion_tasacion = ($_POST['formacion_tasacion'] ?? '') === 'Sí' ? 1 : 0;
    $formacion_tasacion_descripcion = trim($_POST['campo_especificar'] ?? '');
    
    // Mapear experiencia textual a número
    $mapa_experiencia = [
        "Sin experiencia" => 0,
        "Menos de 1 año" => 0,
        "1 a 3 años" => 2,
        "3 a 5 años" => 4,
        "Más de 5 años" => 5
    ];
    $anos_experiencia_tasacion = $mapa_experiencia[$_POST['ano_experiencia']] ?? 0;

    $empresa_tasacion = trim($_POST['otra_empresa'] ?? '');
    $disponibilidad_comuna = ($_POST['disponibilidad_comunal'] ?? '') === 'Sí' ? 1 : 0;
    $disponibilidad_region = ($_POST['disponibilidad_regional'] ?? '') === 'Sí' ? 1 : 0;
    $movilizacion_propia = ($_POST['movilizacion'] ?? '') === 'Sí' ? 1 : 0;

    // Validación del archivo CV
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        die("Error: No se subió el archivo correctamente.");
    }

    $cv_nombre = $_FILES['cv']['name'];
    $cv_temp = $_FILES['cv']['tmp_name'];
    $cv_tamano = $_FILES['cv']['size'];
    $cv_ext = strtolower(pathinfo($cv_nombre, PATHINFO_EXTENSION));

    $ext_permitidas = ['pdf', 'doc', 'docx'];
    if (!in_array($cv_ext, $ext_permitidas)) {
        die("Error: Formato de archivo no permitido. Solo PDF, DOC o DOCX.");
    }

    if ($cv_tamano > 2 * 1024 * 1024) {
        die("Error: El archivo supera el límite de 2MB.");
    }

    // Guardar archivo
    $nombre_cv_final = uniqid() . '.' . $cv_ext;
    $ruta_absoluta = __DIR__ . '/../../uploads/cv/' . $nombre_cv_final;
    $ruta_relativa = 'uploads/cv/' . $nombre_cv_final;

    if (!move_uploaded_file($cv_temp, $ruta_absoluta)) {
        die("Error: No se pudo guardar el archivo.");
    }

    // Insertar en la base de datos
    $stmt = $conexion->prepare("
        INSERT INTO curriculum (
            nombre, apellido, fecha_nacimiento, rut, email, telefono,
            direccion, comuna, region, estudio, institucion_educacional,
            ano_titulacion, formacion_tasacion, formacion_tasacion_descripcion,
            anos_experiencia_tasacion, empresa_tasacion,
            disponibilidad_comuna, disponibilidad_region, movilizacion_propia,
            archivo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param(
        "sssssssssssisisiisss",
        $nombre,
        $apellido,
        $fecha_nacimiento,
        $rut,
        $email,
        $telefono,
        $direccion,
        $comuna,
        $region,
        $estudio,
        $institucion,
        $ano_titulacion,
        $formacion_tasacion,
        $formacion_tasacion_descripcion,
        $anos_experiencia_tasacion,
        $empresa_tasacion,
        $disponibilidad_comuna,
        $disponibilidad_region,
        $movilizacion_propia,
        $ruta_relativa
    );

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: ../../public/postula.php?mensaje=postulado');
        exit;
    } else {
        die("Error al guardar en la base de datos: " . $stmt->error);
    }
} else {
    header('Location: ../../public/postula.php?error=campos');
    exit;
}
