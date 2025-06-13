<?php
$esLocal = ($_SERVER['HTTP_HOST'] === 'localhost');

if ($esLocal) {
    define('BASE_URL', 'http://localhost/vfs/');
} else {
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
}

define('BASE_ADMIN_URL', BASE_URL . 'admin/');

?>
