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
    estado ENUM('pendiente', 'leido', 'respondido','eliminado') NOT NULL DEFAULT 'pendiente'
);

INSERT INTO curriculum (nombre, apellido, fecha_nacimiento, rut, email, telefono, direccion, comuna, region, estudio, institucion_educacional, ano_titulacion, formacion_tasacion,formacion_tasacion_descripcion, anos_experiencia_tasacion, empresa_tasacion, disponibilidad_comuna, disponibilidad_region, movilizacion_propia, archivo, estado) VALUES 
('Nombre 1','Apellido 1','1999-10-21','111111-1','Email1@prueba.com','111111','direccion1','Comuna 1','region1','estudio1','institucion educacional 1','2001',TRUE,'Descripcion 1','2','empresa',TRUE, TRUE,TRUE,'Archivo 1','pendiente'),
('Nombre 2','Apellido 2','1999-10-22','111111-2','Email2@prueba.com','111112','direccion2','Comuna 2','region2','estudio2','institucion educacional 2','2002',FALSE,'Descripcion 2','3','empresa',TRUE, TRUE,TRUE,'Archivo 2','pendiente'),
('Nombre 3','Apellido 3','1999-10-23','111111-3','Email3@prueba.com','111113','direccion3','Comuna 3','region3','estudio3','institucion educacional 3','2003',TRUE,'Descripcion 3','4','empresa',TRUE, TRUE,TRUE,'Archivo 3','pendiente');

CREATE TABLE usuarios_admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(20) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO usuarios_admin (nombre, apellido, email, password, rol, activo) VALUES 
('Jaime','Farias','Email1@prueba.com','1111','Admin',TRUE),
('Federico','Mendez','Email2@prueba.com','2222','Admin',TRUE),
('Berta','Vega','Email3@prueba.com','3333','Admin',FALSE);

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
    tipo_persona ENUM('natural', 'juridica') NOT NULL,
    nombre_empresa VARCHAR(100),
    nombre_contacto VARCHAR(50) NOT NULL,
    apellido_contacto VARCHAR(50) NOT NULL,
    email_contacto VARCHAR(100) NOT NULL,
    telefono_contacto VARCHAR(20),
    tipo_activos VARCHAR(50) NOT NULL,
    notas TEXT,
    usuario_admin_id INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (usuario_admin_id) REFERENCES usuarios_admin(id) ON DELETE CASCADE
);

INSERT INTO clientes (tipo_persona, nombre_empresa, nombre_contacto, apellido_contacto, email_contacto, telefono_contacto, tipo_activos, notas,usuario_admin_id) VALUES
('natural', NULL, 'Carlos', 'Muñoz', 'carlos.munoz@mail.com', '912345678', 'Bienes raíces', 'Cliente interesado en tasaciones para propiedades residenciales.', 1),
('juridica', 'Constructora Andes Ltda.', 'Laura', 'Reyes', 'laura.reyes@andesltda.cl', '922334455', 'Terrenos y maquinaria', 'Requieren tasaciones trimestrales para informes contables.', 2),
('juridica', 'Inversiones Sur S.A.', 'Felipe', 'González', 'felipe.gonzalez@invsur.cl', '933221100', 'Departamentos', 'Solicitan evaluación de 3 propiedades nuevas en Valdivia.', 1);