<?php

session_start();

if (!isset($_SESSION["usuario_id"]) || ($_SESSION["rol"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}

require_once("db.php");

$idPrestamo = (int)($_POST["id_prestamo"] ?? 0);

if ($idPrestamo > 0) {
    $bbdd->devolverPrestamo($idPrestamo);
}

header("Location: admin.php");
exit;
