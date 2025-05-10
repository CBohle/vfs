CREATE DATABASE bd_test_vfs;
CREATE USER 'usuario_test_vfs'@'localhost' IDENTIFIED BY 'ipssgrupo4';
GRANT ALL PRIVILEGES ON bd_test_vfs . * TO 'usuario_test_vfs'@'localhost';
FLUSH PRIVILEGES;

USE bd_test_vfs;

//Crear las tablas en db (clientes, mensajes, usuarios_admin)
CREATE TABLE mensajes (
    id INT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    empresa VARCHAR(100),
    region VARCHAR(100),
    mensaje TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'leido', 'respondido') NOT NULL DEFAULT 'pendiente'
);

//Poblacion de la tabla consulta_cliente con datos de prueba
INSERT INTO mensajes (id, nombre, email, telefono,empresa, region, mensaje, estado) VALUES 
(1,'Prueba 1','prueba1@gmail.com','11111111','empresa 1','region 1','mensaje 1','pendiente'),
(2,'Prueba 2','prueba2@gmail.com','11111111','empresa 2','region 2','mensaje 2','leido'),
(3,'Prueba 3','prueba3@gmail.com','11111111','empresa 3','region 3','mensaje 3','respondido');