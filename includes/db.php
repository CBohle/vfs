<?php
    //Conexión a DB
    $server = 'localhost';
    $user = 'usuario_test_vfs';
    $password = 'ipssgrupo4';
    $database = 'bd_test_vfs';

    $conexion = mysqli_connect($server,$user,$password,$database);
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>

<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bd_test_vfs;charset=utf8", "usuario_test_vfs", "ipssgrupo4");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
