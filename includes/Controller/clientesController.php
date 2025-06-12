<?php
require_once __DIR__ . '/../includes/db.php';

function actualizar_campo_cliente($campo, $valor, $id)
{
    global $conexion;
    $sql = "UPDATE clientes SET $campo = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('si', $valor, $id);
    return $stmt->execute();
}

function actualizar_estado($id, $nuevoEstado)
{
    return actualizar_campo_cliente('estado', $nuevoEstado, $id);
}

function actualizar_importante($id, $importante)
{
    return actualizar_campo_cliente('importante', $importante, $id);
}

function ver_clientes()
{
    global $conexion;
    $sql = 'SELECT clientes.* FROM clientes WHERE clientes.estado != "eliminado" ORDER BY clientes.fecha_creacion DESC, clientes.id ASC';
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
        'activo' => 'bg-success text-light',
        'inactivo' => 'bg-warning text-dark',
        'eliminado' => 'bg-secondary text-light'
    };
}

function actualizar_estado_cliente($id)
{
    global $conexion;
    $nuevo_estado = 'activo'; 

    $sql_update = "UPDATE clientes SET estado = ? WHERE id = ?";
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