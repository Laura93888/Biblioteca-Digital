<?php

function obtenerConexion(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('BIBLIOTECA_DB_HOST') ?: '127.0.0.1';
    $nombre = getenv('BIBLIOTECA_DB_NAME') ?: 'biblioteca';
    $usuario = getenv('BIBLIOTECA_DB_USER') ?: 'biblioteca_user';
    $password = getenv('BIBLIOTECA_DB_PASSWORD') ?: 'Biblioteca123!';

    $dsn = "mysql:host={$host};dbname={$nombre};charset=utf8mb4";

    $pdo = new PDO($dsn, $usuario, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}