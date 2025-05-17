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