<?php
// Protección del panel de administración con sesión


session_start(); //Inicia la sesión para poder acceder a las variables de sesión.
if (!isset($_SESSION['admin_logged_in'])) { //Verifica si la sesión 'admin_logged_in' está activa.
    header('Location: login.php'); //Si no está activa redirige al usuario a login.php y no deja que acceda a la página.
    exit();
}
