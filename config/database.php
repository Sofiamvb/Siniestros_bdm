<?php

function conectarDb(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $host = $_ENV['DB_HOST'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASS'] ?? '';
    $schema = $_ENV['DB_SCHEMA_BDM'] ?? '';

    try {
        $db = new mysqli($host, $user, $pass, $schema);
        $db->set_charset('utf8mb4');
        return $db;
    } catch (mysqli_sql_exception $e) {
        exit('Database connection error: ' . $e->getMessage());
    }
}