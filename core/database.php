<?php

function getConnection(): PDO {
    $config = require '../config/database.php';
    extract($config);

    $dsn = sprintf('mysql:host=%s;dbname=%s;port=%d', $host, $name, $port);

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        die('Error connecting to database: ' . $e->getMessage());
    }
}