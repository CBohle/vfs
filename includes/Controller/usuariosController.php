<?php
require_once __DIR__ . '/../../includes/db.php';

function obtenerRoles() {
    global $conexion;
    $result = $conexion->query("SELECT id, nombre, descripcion, activo FROM roles");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function obtenerRolPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT id, nombre, descripcion, activo FROM roles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $rol = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$rol) return null;

    $stmt = $conexion->prepare("SELECT modulo, accion FROM permisos WHERE rol_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rol['permisos'][$row['modulo']][$row['accion']] = true;
    }
    return $rol;
}

function guardarRol($datos, $permisos) {
    global $conexion;

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
        foreach ($acciones as $accion => $_) {
            $stmt = $conexion->prepare("INSERT INTO permisos (rol_id, modulo, accion) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $rol_id, $modulo, $accion);
            $stmt->execute();
        }
    }

    return true;
}


