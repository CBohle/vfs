<?php
    require_once __DIR__ . '/../../includes/db.php'; // ← Activa cuando uses base de datos real

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
        $id = $_POST['id'];
        $nuevoEstado = $_POST['estado'];

        //agregar la funcion para actualizar el estado
        actualizar_estado($id, $nuevoEstado);
        header("Location: mensajes.php");
        exit;
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

    function actualizar_estado($id, $nuevoEstado) {
        global $conexion;
        $id = mysqli_real_escape_string($conexion, $id);
        $nuevoEstado = mysqli_real_escape_string($conexion, $nuevoEstado);
        $sql = "UPDATE mensajes SET estado = '$nuevoEstado' WHERE id = '$id'";
        mysqli_query($conexion, $sql);
    }

    function ver_mensajes() {
        global $conexion;

        $sql = 'SELECT
                    mensajes.*, 
                    respuestas.*,
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

    function obtenerClaseEstado($estado) {
        $estado = strtolower($estado);
        return match ($estado) {
            'respondido' => 'bg-secondary text-light',
            'pendiente', 'leido' => 'bg-danger text-light',
            'eliminado' => 'bg-secondary text-light',
            default => 'bg-secondary',
        };
    }
?>