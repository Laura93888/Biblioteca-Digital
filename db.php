<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("funciones.php");

$config = require __DIR__ . "/config.php";

$bbdd = new db(
    $config["host"],
    $config["port"],
    $config["db"],
    $config["user"],
    $config["pass"]
);