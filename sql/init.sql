CREATE DATABASE bd_test_vfs;
CREATE USER 'usuario_test_vfs'@'localhost' IDENTIFIED BY 'ipssgrupo4';
GRANT ALL PRIVILEGES ON bd_test_vfs . * TO 'usuario_test_vfs'@'localhost';
FLUSH PRIVILEGES;

USE bd_test_vfs;

//Crear las tablas en db (clientes, mensajes, usuarios_admin)
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
    estado ENUM('pendiente', 'leido', 'respondido') NOT NULL DEFAULT 'pendiente'
);

//Poblacion de la tabla consulta_cliente con datos de prueba
INSERT INTO mensajes (id, nombre, apellido, email, telefono, servicio, mensaje, estado) VALUES 
(1,'Nombre 1','Apellido 1','Email1@prueba.com','111111','Otros','Mensaje 1','pendiente'),
(2,'Nombre 2','Apellido 2','Email2@prueba.com','222222','Tasacion de bienes raices','Mensaje 2','leido'),
(3,'Nombre 3','Apellido 3','Email3@prueba.com','333333','Ambos servicios','Mensaje 3','respondido');


CREATE TABLE curriculum (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    rut VARCHAR(12) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    comuna VARCHAR(50) NOT NULL,
    archivo VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'leido', 'respondido') NOT NULL DEFAULT 'pendiente'
);
//Poblacion de la tabla curriculum con datos de prueba
INSERT INTO curriculum (id, nombre, apellido, rut, email, telefono, comuna, archivo, estado) VALUES 
(1,'Nombre 1','Apellido 1','111111-1','Email1@prueba.com','111111','Comuna 1','Archivo 1','pendiente'),
(2,'Nombre 2','Apellido 2','222222-2','Email2@prueba.com','222222','Comuna 2','Archivo 2','leido'),
(3,'Nombre 3','Apellido 3','333333-3','Email3@prueba.com','333333','Comuna 3','Archivo 3','respondido');

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

//Poblacion de la tabla usuarios_admin con datos de prueba
INSERT INTO usuarios_admin (id, nombre, apellido, email, password, rol, activo) VALUES 
(1,'Nombre 1','Apellido 1','Email1@prueba.com','1111','admin','true'),
(2,'Nombre 2','Apellido 2','Email2@prueba.com','2222','admin','true'),
(3,'Nombre 3','Apellido 3','Email3@prueba.com','3333','admin','false');