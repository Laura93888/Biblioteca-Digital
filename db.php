<?php

session_start();

include_once("db.php");

$config = require __DIR__ . "/config.php";

$bbdd = new db(
    $config["host"],
    $config["port"],
    $config["db"],
    $config["user"],
    $config["pass"]
);