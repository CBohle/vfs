<?php
    //Conexión a DB
    $server = 'localhost';
    $user = 'vfscl_user_bd';
    $password = 'BzV4!oRV)s66r8';
    $database = 'vfscl_bd';

    $conexion = mysqli_connect($server,$user,$password,$database);
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>

<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=vfscl_bd;charset=utf8", "vfscl_user_bd", "BzV4!oRV)s66r8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
