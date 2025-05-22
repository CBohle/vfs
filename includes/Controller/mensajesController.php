<?php
require_once __DIR__ . '/../../includes/db.php'; // ← Activa cuando uses base de datos real

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Marcar como importante
    if (isset($_POST['mensaje_id'], $_POST['importante'])) {
        $mensaje_id = $_POST['mensaje_id'];
        $importante = $_POST['importante'];

        if ($importante === "0" || $importante === "1") {
            actualizar_importante($mensaje_id, $importante);
            echo json_encode(["success" => true]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos."]);
            exit;
        }
    }

    // Actualizar estado del mensaje
    if (isset($_POST['id'], $_POST['estado'])) {
        $id = $_POST['id'];
        $nuevoEstado = $_POST['estado'];

        actualizar_estado($id, $nuevoEstado);
        header("Location: mensajes.php");
        exit;
    }
}

$mensajes = ver_mensajes();

// Contar total de mensajes
$sql_total = "SELECT COUNT(*) AS total FROM mensajes WHERE nombre != ''";
$result_total = mysqli_query($conexion, $sql_total);
$total_mensajes = ($fila = mysqli_fetch_assoc($result_total)) ? $fila['total'] : 0;

// Contar mensajes pendientes
$sql_pendientes = "SELECT COUNT(*) AS pendientes FROM mensajes WHERE estado = 'pendiente'";
$result_pendientes = mysqli_query($conexion, $sql_pendientes);
$pendientes_mensajes = ($fila = mysqli_fetch_assoc($result_pendientes)) ? $fila['pendientes'] : 0;

function actualizar_estado($id, $nuevoEstado)
{
    global $conexion;
    $id = mysqli_real_escape_string($conexion, $id);
    $nuevoEstado = mysqli_real_escape_string($conexion, $nuevoEstado);
    $sql = "UPDATE mensajes SET estado = '$nuevoEstado' WHERE id = '$id'";
    mysqli_query($conexion, $sql);
}

function actualizar_importante($id, $importante)
{
    global $conexion;
    $id = mysqli_real_escape_string($conexion, $id);
    $importante = mysqli_real_escape_string($conexion, $importante);
    $sql = "UPDATE mensajes SET importante = '$importante' WHERE id = '$id'";
    $resultado = mysqli_query($conexion, $sql);
    if (!$resultado) {
        error_log("Error al actualizar importante: " . mysqli_error($conexion));
    }
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
                LEFT JOIN (
                    SELECT * from respuestas
                    WHERE (mensaje_id, fecha_respuesta) IN (
                        SELECT mensaje_id, MAX(fecha_respuesta)
                        FROM respuestas
                        GROUP BY mensaje_id
                    )
                ) AS respuestas ON mensajes.id = respuestas.mensaje_id
                LEFT JOIN usuarios_admin ON respuestas.usuario_admin_id = usuarios_admin.id
                WHERE mensajes.estado != "eliminado"
                ORDER BY mensajes.fecha_creacion DESC, mensajes.id ASC';
    $resultado = mysqli_query($conexion, $sql);

    $filas = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $filas[] = $fila;
    }

    return $filas;
    //echo json_encode(['Estado' => 'ok', 'data' => $filas]);
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
    $sql = "SELECT COUNT(*) AS pendientes FROM mensajes WHERE estado = 'pendiente'";
    $result = mysqli_query($conexion, $sql);
    return ($fila = mysqli_fetch_assoc($result)) ? $fila['pendientes'] : 0;
}
function filtrar_mensajes($estado = '', $servicio = '', $orden = 'desc')
{
    global $conexion;

    $estado = mysqli_real_escape_string($conexion, $estado);
    $servicio = mysqli_real_escape_string($conexion, $servicio);
    $orden = strtoupper($orden) === 'ASC' ? 'ASC' : 'DESC';

    $where = "WHERE nombre != ''";
    if (!empty($estado) && $estado !== 'Todos') {
        $where .= " AND estado = '$estado'";
    }
    if (!empty($servicio) && $servicio !== 'Todos') {
        $where .= " AND servicio = '$servicio'";
    }

    $query = "SELECT * FROM mensajes $where ORDER BY fecha_creacion $orden";
    $result = mysqli_query($conexion, $query);

    $mensajes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $mensajes[] = $row;
    }
    return $mensajes;
}
