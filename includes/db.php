<?php
// Cargar autoload para Dotenv
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables del .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Leer variables desde el entorno
$server   = $_ENV['DB_SERVER'];
$user     = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME'];

// Conexión mysqli
$conexion = mysqli_connect($server, $user, $password, $database);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Conexión PDO
try {
    $pdo = new PDO("mysql:host=$server;dbname=$database;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
