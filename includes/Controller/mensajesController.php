<?php
require_once __DIR__ . '/../../includes/db.php';

// Función general para actualizar cualquier campo de mensaje
function actualizar_campo_mensaje($campo, $valor, $id)
{
    global $conexion;
    $sql = "UPDATE mensajes SET $campo = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('si', $valor, $id);
    return $stmt->execute();
}

function actualizar_estado($id, $nuevoEstado)
{
    return actualizar_campo_mensaje('estado', $nuevoEstado, $id);
}

function actualizar_importante($id, $importante)
{
    return actualizar_campo_mensaje('importante', $importante, $id);
}

function ver_mensajes()
{
    global $conexion;
    $sql = 'SELECT
                mensajes.*, 
                respuestas.*, 
                mensajes.id AS mensaje_id,
                usuarios_admin.nombre AS admin_nombre,
                usuarios_admin.apellido AS admin_apellido,
                usuarios_admin.email AS admin_email,
                usuarios_admin.rol AS admin_rol
            FROM mensajes
            LEFT JOIN respuestas ON respuestas.mensaje_id = mensajes.id
            LEFT JOIN usuarios_admin ON usuarios_admin.id = respuestas.usuario_admin_id
            WHERE mensajes.estado != "eliminado"
            ORDER BY mensajes.fecha_creacion DESC, mensajes.id ASC';
    
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
        'pendiente' => 'bg-warning text-light',
        'leido' => 'bg-primary text-light',
        'eliminado' => 'bg-secondary text-light',
        default => 'bg-secondary',
    };
}
function tiempo_transcurrido($fecha)
{
    $ahora = new DateTime();
    $fecha_mensaje = new DateTime($fecha);
    $diferencia = $ahora->diff($fecha_mensaje);

    if ($diferencia->y > 0) return $diferencia->y . ' año(s) atrás';
    if ($diferencia->m > 0) return $diferencia->m . ' mes(es) atrás';
    if ($diferencia->d > 0) return $diferencia->d . ' día(s) atrás';
    if ($diferencia->h > 0) return $diferencia->h . ' hora(s) atrás';
    if ($diferencia->i > 0) return $diferencia->i . ' minuto(s) atrás';

    return 'Hace un momento';
}
function obtener_total_mensajes()
{
    global $conexion;
    $sql = "SELECT COUNT(*) AS total FROM mensajes WHERE nombre != '' AND estado != 'eliminado'";
    $result = mysqli_query($conexion, $sql);
    return ($fila = mysqli_fetch_assoc($result)) ? $fila['total'] : 0;
}

function obtener_mensajes_pendientes()
{
    global $conexion;
    $sql = "SELECT COUNT(*) AS pendientes FROM mensajes WHERE estado IN ('pendiente', 'leido')";
    $result = mysqli_query($conexion, $sql);
    return ($fila = mysqli_fetch_assoc($result)) ? $fila['pendientes'] : 0;
}

// Función para actualizar el estado del mensaje según si tiene respuesta
function actualizar_estado_mensaje($id)
{
    global $conexion;
    
    // Consulta para verificar si el mensaje tiene respuesta
    $sql = "SELECT respuestas.respuesta FROM mensajes 
            LEFT JOIN respuestas ON respuestas.mensaje_id = mensajes.id
            WHERE mensajes.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $msg = $result->fetch_assoc();

    // Determina el nuevo estado basado en si hay respuesta
    $nuevo_estado = ($msg['respuesta'] !== null) ? 'respondido' : 'pendiente';

    // Actualiza el estado del mensaje
    $sql_update = "UPDATE mensajes SET estado = ? WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param('si', $nuevo_estado, $id);
    if ($stmt_update->execute()) {
        return json_encode(['success' => true, 'nuevo_estado' => $nuevo_estado]);
    } else {
        return json_encode(['success' => false]);
    }
}
?>