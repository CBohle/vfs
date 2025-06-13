<?php
session_start();
session_unset();
session_destroy();

require_once __DIR__ . '/includes/config.php'; // Ajuste a la ruta de config.php
header("Location: " . BASE_URL . "admin/login.php");
exit;
?>
