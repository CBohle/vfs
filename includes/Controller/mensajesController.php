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

    function actualizar_estado($id, $nuevoEstado) {
        global $conexion;

        $sql = "UPDATE mensajes SET estado = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $nuevoEstado, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
?>