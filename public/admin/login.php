<?php
// Login de los administradores


// login.php (después de validar usuario y contraseña)

session_start();
$_SESSION['admin_logged_in'] = true;
header('Location: dashboard.php');

