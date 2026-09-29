<?php

session_start();

// Vaciar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Volver al login
header("Location: index.php");
exit;
?>
