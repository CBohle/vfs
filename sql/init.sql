CREATE DATABASE bd_test_vfs;
CREATE USER 'usuario_test_vfs'@'localhost' IDENTIFIED BY 'ipssgrupo4';
GRANT ALL PRIVILEGES ON bd_test_vfs . * TO 'usuario_test_vfs'@'localhost';
FLUSH PRIVILEGES;

USE bd_test_vfs;

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

INSERT INTO mensajes (nombre, apellido, email, telefono, servicio, mensaje, importante, estado) VALUES 
('Camila', 'Rojas', 'camila.rojas@mail.com', '987654321', 'Otros', 'Hola, quisiera saber si también hacen informes de avalúo para seguros.',FALSE, 'respondido'),
('Jorge', 'Paredes', 'jorge.paredes@mail.com', '965432187', 'Tasacion de bienes raices', 'Necesito una tasación urgente para una propiedad en Ñuñoa, ¿cuánto demoran?',FALSE, 'leido'),
('María', 'Torres', 'maria.torres@mail.com', '912345678', 'Ambos servicios', 'Estoy buscando tasar una propiedad y también un vehículo, ¿ustedes pueden hacerlo?',FALSE, 'respondido'),
('Luis', 'González', 'luis.gonzalez@mail.com', '923456789', 'Tasacion de bienes raices', '¿Realizan tasaciones para propiedades rurales? Tengo un terreno en Melipilla.',TRUE, 'respondido'),
('Fernanda', 'Vega', 'fernanda.vega@mail.com', '934567891', 'Otros', '¿Atienden los fines de semana? Me gustaría agendar una consulta.',FALSE,'pendiente');

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
    estudio VARCHAR(50) NOT NULL,
    institucion_educacional VARCHAR(100) NOT NULL,
    ano_titulacion INT NOT NULL,
    formacion_tasacion BOOLEAN NOT NULL DEFAULT FALSE,
    formacion_tasacion_descripcion TEXT,
    anos_experiencia_tasacion INT NOT NULL DEFAULT 0,
    empresa_tasacion VARCHAR(100) NOT NULL,
    disponibilidad_comuna BOOLEAN NOT NULL DEFAULT FALSE,
    disponibilidad_region BOOLEAN NOT NULL DEFAULT FALSE,
    movilizacion_propia BOOLEAN NOT NULL DEFAULT FALSE,
    archivo VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    importante BOOLEAN NOT NULL DEFAULT FALSE,
    estado ENUM('pendiente', 'leido','eliminado') NOT NULL DEFAULT 'pendiente'
);

INSERT INTO curriculum (nombre, apellido, fecha_nacimiento, rut, email, telefono, direccion, comuna, region, estudio, institucion_educacional, ano_titulacion, formacion_tasacion, formacion_tasacion_descripcion, anos_experiencia_tasacion, empresa_tasacion, disponibilidad_comuna, disponibilidad_region, movilizacion_propia, archivo, importante, estado) VALUES
('Carlos', 'Pérez', '1985-04-10', '12345678-9', 'carlos.perez@example.com', '987654321', 'Calle Ficticia 123', 'Comuna Central', 'Región Metropolitana', 'Ingeniería Civil', 'Universidad Central', 2007, TRUE, 'Certificado de formación en tasación inmobiliaria', 5, 'Tasaciones Pérez Ltda', TRUE, TRUE, TRUE, 'archivo1.pdf', FALSE, 'pendiente'),
('Ana', 'González', '1990-08-25', '23456789-0', 'ana.gonzalez@example.com', '987654322', 'Avenida Siempre Viva 456', 'Comuna Sur', 'Región del Libertador', 'Arquitectura', 'Universidad del Libertador', 2012, FALSE, '', 4, 'Inmobiliaria Sur S.A.', TRUE, TRUE, TRUE, 'archivo2.pdf', TRUE, 'leido'),
('Luis', 'Martínez', '1992-02-15', '34567890-1', 'luis.martinez@example.com', '987654323', 'Calle Nueva 789', 'Comuna Norte', 'Región Andina', 'Tasación Inmobiliaria', 'Instituto Nacional de Tasación', 2015, TRUE, 'Curso completo en tasación de propiedades', 3, 'Tasaciones Andinas', TRUE, FALSE, TRUE, 'archivo3.pdf', FALSE, 'respondido'),
('María', 'López', '1987-12-05', '45678901-2', 'maria.lopez@example.com', '987654324', 'Calle Real 321', 'Comuna Oeste', 'Región Centro', 'Ingeniería Comercial', 'Universidad Comercial', 2009, TRUE, 'Diplomado en Tasación de Bienes Raíces', 6, 'Inmobiliaria Comercial S.A.', TRUE, TRUE, FALSE, 'archivo4.pdf', TRUE, 'pendiente'),
('Pedro', 'Sánchez', '1995-03-18', '56789012-3', 'pedro.sanchez@example.com', '987654325', 'Calle Falsa 654', 'Comuna Este', 'Región Norte', 'Gestión Inmobiliaria', 'Universidad Norte', 2017, FALSE, '', 2, 'Consultora Inmobiliaria', FALSE, TRUE, TRUE, 'archivo5.pdf', FALSE, 'eliminado');

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255),
    activo BOOLEAN NOT NULL DEFAULT TRUE
);
INSERT INTO roles (nombre, descripcion, activo) VALUES
('admin', 'Acceso completo a todas las funcionalidades', TRUE),
('rrhh', 'Visualiza y edita postulaciones', TRUE),
('ejecutivo', 'Gestiona mensajes y clientes', TRUE),
('practicante', 'Visualiza mensajes y clientes', FALSE);

CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

INSERT INTO permisos (rol_id, modulo, accion) VALUES
(1, 'mensajes', 'ver'), (1, 'mensajes', 'modificar'), (1, 'mensajes', 'crear'), (1, 'mensajes', 'eliminar'),
(1, 'postulaciones', 'ver'), (1, 'postulaciones', 'modificar'), (1, 'postulaciones', 'crear'), (1, 'postulaciones', 'eliminar'),
(1, 'clientes', 'ver'), (1, 'clientes', 'modificar'), (1, 'clientes', 'crear'), (1, 'clientes', 'eliminar'),
(1, 'usuarios', 'ver'), (1, 'usuarios', 'modificar'), (1, 'usuarios', 'crear'), (1, 'usuarios', 'eliminar'),
(1, 'roles', 'ver'), (1, 'roles', 'modificar'), (1, 'roles', 'crear'), (1, 'roles', 'eliminar');

INSERT INTO permisos (rol_id, modulo, accion) VALUES
(2, 'postulaciones', 'ver'),
(2, 'postulaciones', 'modificar');

INSERT INTO permisos (rol_id, modulo, accion) VALUES
(3, 'mensajes', 'ver'), (3, 'mensajes', 'modificar'), (3, 'mensajes', 'eliminar'),
(3, 'clientes', 'ver'), (3, 'clientes', 'modificar');

INSERT INTO permisos (rol_id, modulo, accion) VALUES
(4, 'mensajes', 'ver'),
(4, 'clientes', 'ver');

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
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);  

INSERT INTO usuarios_admin (nombre, apellido, email, password, rol_id, activo) VALUES
('Jaime', 'Farias', 'email1@prueba.com', '1234', 1, TRUE),
('Federico', 'Mendez', 'email2@prueba.com', '1234', 1, TRUE),
('Berta', 'Vega', 'email3@prueba.com', '1234', 1, FALSE);

CREATE TABLE respuestas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mensaje_id INT NOT NULL,
    usuario_admin_id INT NOT NULL,
    respuesta TEXT NOT NULL,
    fecha_respuesta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mensaje_id) REFERENCES mensajes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_admin_id) REFERENCES usuarios_admin(id) ON DELETE CASCADE
);

INSERT INTO respuestas (mensaje_id, usuario_admin_id, respuesta) VALUES
(1, 2, 'Hola Camila, sí, realizamos informes de avalúo para seguros. Podemos coordinar una reunión para revisar los antecedentes.'),
(3, 1, 'Hola María, efectivamente realizamos tasaciones tanto de bienes raíces como de vehículos. Te contactaremos para solicitar más detalles.'),
(4, 2, 'Hola Luis, sí realizamos tasaciones de terrenos rurales, incluyendo zonas como Melipilla. Te contactaremos para coordinar una visita.');

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

INSERT INTO clientes (tipo_persona, nombre_empresa, nombre_contacto, apellido_contacto, email_contacto, telefono_contacto, tipo_activos,detalle_activos, notas,usuario_admin_id,estado) VALUES
('Natural', 'No aplica', 'Carlos', 'Muñoz', 'carlos.munoz@mail.com', '912345678', 'Propiedad Residencial', 'Casa','Cliente interesado en tasaciones para propiedades residenciales.', 1, 'activo'),
('Juridica', 'Constructora Andes Ltda.', 'Laura', 'Reyes', 'laura.reyes@andesltda.cl', '922334455', 'Activo Industrial','Terreno Industrial', 'Requieren tasaciones trimestrales para informes contables.', 2, 'inactivo'),
('Juridica', 'Inversiones Sur S.A.', 'Felipe', 'González', 'felipe.gonzalez@invsur.cl', '933221100', 'Propiedad Residencial','Departamento', 'Solicitan evaluación de 3 propiedades nuevas en Valdivia.', 1, 'activo');