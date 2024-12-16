<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=webtech_fall2024_lady_hagan",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
