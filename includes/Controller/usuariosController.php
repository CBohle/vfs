<?php
require_once __DIR__ . '/../db.php';

function obtenerRoles() {
    global $conexion;
    $result = $conexion->query("SELECT id, nombre FROM roles WHERE activo = TRUE");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function guardarRol($datos, $permisos) {
    global $conexion;
    if (!empty($datos['id'])) {
        $rolActual = obtenerRolPorId($datos['id']);
        if (strtolower($rolActual['nombre']) === 'admin') {
            echo json_encode(['success' => false, 'error' => 'No puedes modificar los permisos del rol admin']);
            return;
        }
    }
    if (!empty($datos['id'])) {
        $stmt = $conexion->prepare("UPDATE roles SET descripcion = ? WHERE id = ?");
        $stmt->bind_param("si", $datos['descripcion'], $datos['id']);
        $stmt->execute();
        $rol_id = $datos['id'];
        $conexion->query("DELETE FROM permisos WHERE rol_id = $rol_id");
    } else {
        $stmt = $conexion->prepare("INSERT INTO roles (nombre, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $datos['nombre'], $datos['descripcion']);
        $stmt->execute();
        $rol_id = $conexion->insert_id;
    }

    foreach ($permisos as $modulo => $acciones) {
        foreach ($acciones as $accion => $valor) {
            $stmt = $conexion->prepare("INSERT INTO permisos (rol_id, modulo, accion) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $rol_id, $modulo, $accion);
            $stmt->execute();
        }
    }
    return true;
}

function obtenerRolPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT id, nombre, descripcion, activo FROM roles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rol = $result->fetch_assoc();

    $stmt = $conexion->prepare("SELECT modulo, accion FROM permisos WHERE rol_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rol['permisos'][$row['modulo']][$row['accion']] = true;
    }
    return $rol;
}

function toggleEstadoRol($id) {
    global $conexion;
    $stmt = $conexion->prepare("UPDATE roles SET activo = NOT activo WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function listarRoles($post) {
    global $conexion;
    $columns = ['id', 'nombre', 'descripcion', 'activo'];
    $start = intval($post['start'] ?? 0);
    $length = intval($post['length'] ?? 10);
    $orderIndex = $post['order'][0]['column'] ?? 0;
    $orderDir = in_array($post['order'][0]['dir'] ?? '', ['asc', 'desc']) ? $post['order'][0]['dir'] : 'asc';
    $orderColumn = $columns[$orderIndex] ?? 'nombre';

    $sqlData = "SELECT id, nombre, descripcion, activo FROM roles ORDER BY $orderColumn $orderDir LIMIT ?, ?";
    $stmt = $conexion->prepare($sqlData);
    $stmt->bind_param("ii", $start, $length);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['activo'] = $row['activo'] == 1 ? 'Activo' : 'Inactivo';
        $data[] = $row;
    }

    $total = $conexion->query("SELECT COUNT(*) as total FROM roles")->fetch_assoc();

    return [
        "draw" => intval($post['draw'] ?? 0),
        "recordsTotal" => $total['total'],
        "recordsFiltered" => $total['total'],
        "data" => $data
    ];
}

function guardarUsuario($datos) {
    global $conexion;

    if (!empty($datos['id'])) {
        if (!empty($datos['password'])) {
            $pass = password_hash($datos['password'], PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios_admin SET nombre = ?, apellido = ?, email = ?, password = ?, rol_id = ?, activo = ? WHERE id = ?");
            $stmt->bind_param("ssssiii", $datos['nombre'], $datos['apellido'], $datos['email'], $pass, $datos['rol_id'], $datos['activo'], $datos['id']);
        } else {
            $stmt = $conexion->prepare("UPDATE usuarios_admin SET nombre = ?, apellido = ?, email = ?, rol_id = ?, activo = ? WHERE id = ?");
            $stmt->bind_param("sssiii", $datos['nombre'], $datos['apellido'], $datos['email'], $datos['rol_id'], $datos['activo'], $datos['id']);
        }
    } else {
        $pass = password_hash($datos['password'], PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios_admin (nombre, apellido, email, password, rol_id, activo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $datos['nombre'], $datos['apellido'], $datos['email'], $pass, $datos['rol_id'], $datos['activo']);
    }

    return $stmt->execute();
}

function obtenerUsuarioPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT id, nombre, apellido, email, rol_id, activo FROM usuarios_admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
function obtenerRolPorNombre($nombre) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT * FROM roles WHERE LOWER(nombre) = LOWER(?)");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
function toggleEstadoUsuario($id) {
    global $conexion;

    // Obtener datos del usuario
    $stmt = $conexion->prepare("SELECT rol_id, activo FROM usuarios_admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    // Obtener nombre del rol
    $stmt = $conexion->prepare("SELECT nombre FROM roles WHERE id = ?");
    $stmt->bind_param("i", $usuario['rol_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $rol = $result->fetch_assoc();

    if ($usuario['activo'] && strtolower($rol['nombre']) === 'admin') {
        // Verificar si es el único admin activo
        $query = "SELECT COUNT(*) as total FROM usuarios_admin u JOIN roles r ON u.rol_id = r.id WHERE r.nombre = 'admin' AND u.activo = 1 AND u.id != ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res['total'] == 0) {
            return ['success' => false, 'error' => 'Debe existir al menos un usuario activo con rol admin.'];
        }
    }

    $stmt = $conexion->prepare("UPDATE usuarios_admin SET activo = NOT activo WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return ['success' => true];
}


function listarUsuarios($post) {
    global $conexion;
    $columns = ['id', 'nombre', 'apellido', 'email', 'rol_id', 'activo'];
    $start = intval($post['start'] ?? 0);
    $length = intval($post['length'] ?? 10);
    $orderIndex = $post['order'][0]['column'] ?? 0;
    $orderDir = in_array($post['order'][0]['dir'] ?? '', ['asc', 'desc']) ? $post['order'][0]['dir'] : 'asc';
    $orderColumn = $columns[$orderIndex] ?? 'nombre';

    $sqlData = "SELECT u.id, u.nombre, u.apellido, u.email, u.rol_id, u.activo, r.nombre as rol FROM usuarios_admin u JOIN roles r ON u.rol_id = r.id ORDER BY $orderColumn $orderDir LIMIT ?, ?";
    $stmt = $conexion->prepare($sqlData);
    $stmt->bind_param("ii", $start, $length);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $total = $conexion->query("SELECT COUNT(*) as total FROM usuarios_admin")->fetch_assoc();

    return [
        "draw" => intval($post['draw'] ?? 0),
        "recordsTotal" => $total['total'],
        "recordsFiltered" => $total['total'],
        "data" => $data
    ];
}
function cambiarEstadoUsuario($id, $estado) {
    global $conexion;

    // Obtener datos del usuario
    $stmt = $conexion->prepare("SELECT rol_id FROM usuarios_admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    // Obtener nombre del rol
    $stmt = $conexion->prepare("SELECT nombre FROM roles WHERE id = ?");
    $stmt->bind_param("i", $usuario['rol_id']);
    $stmt->execute();
    $rol = $stmt->get_result()->fetch_assoc();

    $nuevoValor = ($estado === 'activo') ? 1 : 0;

    if ($nuevoValor === 0 && strtolower($rol['nombre']) === 'admin') {
        // Verificar si es el único admin activo
        $query = "SELECT COUNT(*) as total FROM usuarios_admin u 
                  JOIN roles r ON u.rol_id = r.id 
                  WHERE r.nombre = 'admin' AND u.activo = 1 AND u.id != ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res['total'] == 0) {
            return ['success' => false, 'error' => 'Debe existir al menos un usuario activo con rol admin.'];
        }
    }

    $stmt = $conexion->prepare("UPDATE usuarios_admin SET activo = ? WHERE id = ?");
    $stmt->bind_param("ii", $nuevoValor, $id);
    $success = $stmt->execute();

    return ['success' => $success];
}
function cambiarEstadoRol($id, $estado) {
    global $conexion;

    // 1. Obtener el nombre del rol
    $stmt = $conexion->prepare("SELECT nombre FROM roles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $rol = $stmt->get_result()->fetch_assoc();

    if (!$rol) {
        return ['success' => false, 'error' => 'Rol no encontrado'];
    }

    // 2. Validar si es el rol admin y se intenta desactivar
    $nuevoValor = ($estado === 'activo') ? 1 : 0;
    if ($nuevoValor === 0 && strtolower($rol['nombre']) === 'admin') {
        return ['success' => false, 'error' => 'No puedes desactivar el rol admin.'];
    }

    // 3. Ejecutar actualización
    $stmt = $conexion->prepare("UPDATE roles SET activo = ? WHERE id = ?");
    $stmt->bind_param("ii", $nuevoValor, $id);
    $success = $stmt->execute();

    return ['success' => $success];
}
function eliminarRol($id) {
    global $conexion;
    $conexion->query("DELETE FROM permisos WHERE rol_id = $id");
    $stmt = $conexion->prepare("DELETE FROM roles WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function eliminarUsuario($id) {
    global $conexion;

    // Obtener datos del usuario
    $stmt = $conexion->prepare("SELECT rol_id FROM usuarios_admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    // Obtener nombre del rol
    $stmt = $conexion->prepare("SELECT nombre FROM roles WHERE id = ?");
    $stmt->bind_param("i", $usuario['rol_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $rol = $result->fetch_assoc();

    if (strtolower($rol['nombre']) === 'admin') {
        // Verificar si es el único admin activo
        $query = "SELECT COUNT(*) as total FROM usuarios_admin u JOIN roles r ON u.rol_id = r.id WHERE r.nombre = 'admin' AND u.id != ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res['total'] == 0) {
            echo json_encode(['success' => false, 'error' => 'Debe existir al menos un admin activo.']);
            exit;
        }
    }

    $stmt = $conexion->prepare("DELETE FROM usuarios_admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>


