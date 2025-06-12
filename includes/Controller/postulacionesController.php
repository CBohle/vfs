<?php
require_once __DIR__ . '/../includes/db.php';

// Función general para actualizar cualquier campo de postulacion
function actualizar_campo_postulacion($campo, $valor, $id)
{
    global $conexion;
    $sql = "UPDATE curriculum SET $campo = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('si', $valor, $id);
    return $stmt->execute();
}

function actualizar_estado($id, $nuevoEstado)
{
    return actualizar_campo_postulacion('estado', $nuevoEstado, $id);
}

function actualizar_importante($id, $importante)
{
    return actualizar_campo_postulacion('importante', $importante, $id);
}

function ver_postulaciones()
{
    global $conexion;
    $sql = 'SELECT curriculum.* FROM curriculum WHERE curriculum.estado != "eliminado" ORDER BY curriculum.fecha_creacion DESC, curriculum.id ASC';
    $resultado = mysqli_query($conexion, $sql);
    $filas = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $filas[] = $fila;
    }
    return $filas;
}

function obtenerClaseEstado($estado)
{
    $estado = strtolower($estado);
    return match ($estado) {
        'respondido' => 'bg-success text-light',
        'pendiente' => 'bg-warning text-dark',
        'leido' => 'bg-primary text-light',
        'eliminado' => 'bg-secondary text-light',
        default => 'bg-secondary',
    };
}

function obtener_total_postulaciones()
{
    global $conexion;
    $sql = "SELECT COUNT(*) AS total FROM curriculum WHERE nombre != '' AND estado != 'eliminado'";
    $result = mysqli_query($conexion, $sql);
    return ($fila = mysqli_fetch_assoc($result)) ? $fila['total'] : 0;
}

function obtener_postulaciones_pendientes()
{
    global $conexion;
    $sql = "SELECT COUNT(*) AS pendientes FROM curriculum WHERE estado = 'pendiente'";
    $result = mysqli_query($conexion, $sql);
    return ($fila = mysqli_fetch_assoc($result)) ? $fila['pendientes'] : 0;
}

// Actualizar el estado de la postulacion
function actualizar_estado_postulacion($id)
{
    global $conexion;
    $nuevo_estado = 'pendiente'; 

    $sql_update = "UPDATE curriculum SET estado = ? WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param('si', $nuevo_estado, $id);
    if ($stmt_update->execute()) {
        return json_encode(['success' => true, 'nuevo_estado' => $nuevo_estado]);
    } else {
        return json_encode(['success' => false, 'error' => $conexion->error]);
    }
}

// Formatear número telefónico
function formatear_telefono($numero)
{
    $numero = preg_replace('/\D/', '', $numero); // quita espacios, guiones, paréntesis, etc.
    if (strlen($numero) === 11) {
        return substr($numero, 0, 3) . ' ' . substr($numero, 3, 4) . ' ' . substr($numero, 7);
    } elseif (strlen($numero) === 9) {
        return substr($numero, 0, 1) . ' ' . substr($numero, 1, 4) . ' ' . substr($numero, 5);
    } elseif (strlen($numero) === 8) {
        return substr($numero, 0, 4) . ' ' . substr($numero, 4);
    }
    return $numero;
}
?>
