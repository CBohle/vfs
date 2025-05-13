CREATE DATABASE bd_test_vfs;
CREATE USER 'usuario_test_vfs'@'localhost' IDENTIFIED BY 'ipssgrupo4';
GRANT ALL PRIVILEGES ON bd_test_vfs . * TO 'usuario_test_vfs'@'localhost';
FLUSH PRIVILEGES;

USE bd_test_vfs;

CREATE TABLE mensajes (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    servicio VARCHAR(80) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'leido', 'respondido', 'eliminado') NOT NULL DEFAULT 'pendiente'
);

INSERT INTO mensajes (id, nombre, apellido, email, telefono, servicio, mensaje, estado) VALUES 
(1,'Nombre 1','Apellido 1','Email1@prueba.com','111111','Otros','Mensaje 1','pendiente'),
(2,'Nombre 2','Apellido 2','Email2@prueba.com','222222','Tasacion de bienes raices','Mensaje 2','leido'),
(3,'Nombre 3','Apellido 3','Email3@prueba.com','333333','Ambos servicios','Mensaje 3','respondido');


CREATE TABLE curriculum (
    id INT PRIMARY KEY,
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

INSERT INTO curriculum (id, nombre, apellido, fecha_nacimiento, rut, email, telefono, direccion, comuna, region, estudio, institucion_educacional, ano_titulacion, formacion_tasacion,formacion_tasacion_descripcion, anos_experiencia_tasacion, empresa_tasacion, disponibilidad_comuna, disponibilidad_region, movilizacion_propia, archivo, estado) VALUES 
(1,'Nombre 1','Apellido 1','1999-10-21','111111-1','Email1@prueba.com','111111','direccion1','Comuna 1','region1','estudio1','institucion educacional 1','2001',TRUE,'Descripcion 1','2','empresa',TRUE, TRUE,TRUE,'Archivo 1','pendiente'),
(2,'Nombre 2','Apellido 2','1999-10-22','111111-2','Email2@prueba.com','111112','direccion2','Comuna 2','region2','estudio2','institucion educacional 2','2002',FALSE,'Descripcion 2','3','empresa',TRUE, TRUE,TRUE,'Archivo 2','pendiente'),
(3,'Nombre 3','Apellido 3','1999-10-23','111111-3','Email3@prueba.com','111113','direccion3','Comuna 3','region3','estudio3','institucion educacional 3','2003',TRUE,'Descripcion 3','4','empresa',TRUE, TRUE,TRUE,'Archivo 3','pendiente');

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

INSERT INTO usuarios_admin (id, nombre, apellido, email, password, rol, activo) VALUES 
(1,'Nombre 1','Apellido 1','Email1@prueba.com','1111','Admin','true'),
(2,'Nombre 2','Apellido 2','Email2@prueba.com','2222','Admin','true'),
(3,'Nombre 3','Apellido 3','Email3@prueba.com','3333','Admin','false');

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
(1, 2, 'Gracias por contactarnos. Hemos recibido tu solicitud y te responderemos pronto msg1.'),
(3, 1, 'Gracias por contactarnos. Hemos recibido tu solicitud y te responderemos pronto msg3.');