<?php
// Cargar autoload para Dotenv
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables del .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// BASE_URL
$esLocal = ($_SERVER['HTTP_HOST'] === 'localhost');

if ($esLocal) {
    define('BASE_URL', 'http://localhost/vfs/');
} else {
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
}

// BASE_ADMIN_URL
define('BASE_ADMIN_URL', BASE_URL . 'admin/');

?>