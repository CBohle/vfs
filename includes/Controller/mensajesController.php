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

        $sql = 'SELECT * 
                FROM mensajes 
                ORDER BY fecha DESC';
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