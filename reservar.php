<?php

session_start();

require_once("db.php");


/* =========================
   COMPROBAR SESIÓN
   ========================= */

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}


/* =========================
   OBTENER DATOS
   ========================= */

$idUsuario = (int)$_SESSION["usuario_id"];
$idLibro = (int)($_POST["id_libro"] ?? 0);


/* =========================
   COMPROBAR LIBRO
   ========================= */

if ($idLibro <= 0) {
    header("Location: index.php");
    exit;
}

$libro = obtenerLibroPorId($idLibro);

if (!$libro) {
    header("Location: index.php");
    exit;
}


/* =========================
   CREAR PRÉSTAMO
   ========================= */

if (crearPrestamo($idUsuario, $idLibro)) {

    header("Location: panelusuario.php?reserva=ok");
    exit;
}


/* =========================
   SI YA NO ESTÁ DISPONIBLE
   ========================= */

header("Location: libro.php?id=" . $idLibro . "&reserva=no");
exit;