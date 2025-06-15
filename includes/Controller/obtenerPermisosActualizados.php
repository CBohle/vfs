    <?php
    session_start();
    header('Content-Type: application/json');

    require_once __DIR__ . '/../db.php';

    if (!function_exists('obtenerPermisos')) {
        function obtenerPermisos($rol_id) {
            global $conexion;

            $sql = "SELECT modulo, accion FROM permisos WHERE rol_id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $rol_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $permisos = [];
            while ($row = $result->fetch_assoc()) {
                $permisos[$row['modulo']][] = $row['accion'];
            }

            return $permisos;
        }
    }

    if (!isset($_SESSION['rol_id'])) {
        echo json_encode([
            'success' => false,
            'error' => 'No hay sesión activa o rol no definido.'
        ]);
        exit;
    }

    $rol_id = $_SESSION['rol_id'];
    $permisos = obtenerPermisos($rol_id);

    // Guardar los permisos actualizados en sesión
    $_SESSION['permisos'] = $permisos;

    echo json_encode([
        'success' => true,
        'permisos' => $permisos
    ]);