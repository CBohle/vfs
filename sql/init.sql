CREATE DATABASE vfscl_bd;
CREATE USER 'vfscl_user_bd'@'localhost' IDENTIFIED BY 'BzV4!oRV)s66r8';
GRANT ALL PRIVILEGES ON vfscl_bd . * TO 'vfscl_user_bd'@'localhost';
FLUSH PRIVILEGES;

USE vfscl_bd;

CREATE TABLE mensajes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    servicio VARCHAR(80) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    importante BOOLEAN NOT NULL DEFAULT FALSE,
    estado ENUM('pendiente', 'leido', 'respondido', 'eliminado') NOT NULL DEFAULT 'pendiente'
);

CREATE TABLE curriculum (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    rut VARCHAR(12) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255) NOT NULL,
    comuna VARCHAR(50) NOT NULL,
    region VARCHAR(50) NOT NULL,
    estudios VARCHAR(50) NOT NULL,
    institucion_educacional VARCHAR(100) NOT NULL,
    ano_titulacion INT NOT NULL,
    formacion_tasacion BOOLEAN NOT NULL DEFAULT FALSE,
    detalle_formacion TEXT,
    anos_experiencia_tasacion INT NOT NULL DEFAULT 0,
    otra_empresa VARCHAR(100) NOT NULL,
    disponibilidad_comuna BOOLEAN NOT NULL DEFAULT FALSE,
    disponibilidad_region BOOLEAN NOT NULL DEFAULT FALSE,
    movilizacion_propia BOOLEAN NOT NULL DEFAULT FALSE,
    archivo VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    importante BOOLEAN NOT NULL DEFAULT FALSE,
    estado ENUM('pendiente', 'leido', 'respondido','eliminado') NOT NULL DEFAULT 'pendiente'
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255),
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE usuarios_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    reset_token VARCHAR(255), 
    token_expira DATETIME,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);  

CREATE TABLE respuestas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mensaje_id INT NOT NULL,
    usuario_admin_id INT NOT NULL,
    respuesta TEXT NOT NULL,
    fecha_respuesta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mensaje_id) REFERENCES mensajes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_admin_id) REFERENCES usuarios_admin(id) ON DELETE CASCADE
);

CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_persona ENUM('Natural', 'Juridica') NOT NULL,
    nombre_empresa VARCHAR(100),
    nombre_contacto VARCHAR(50) NOT NULL,
    apellido_contacto VARCHAR(50) NOT NULL,
    email_contacto VARCHAR(100) NOT NULL,
    telefono_contacto VARCHAR(20),
    tipo_activos VARCHAR(50) NOT NULL,
    detalle_activos VARCHAR(50) NOT NULL,
    notas TEXT,
    usuario_admin_id INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Activo', 'Inactivo','Eliminado') NOT NULL DEFAULT 'activo',
    importante BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (usuario_admin_id) REFERENCES usuarios_admin(id) ON DELETE CASCADE
);