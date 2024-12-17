<?php
try {
    $host = 'localhost';
    $dbname = 'litdb';
    $username = 'root';        
    $password = '';           

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("USE $dbname");
    
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    throw new Exception("Database connection failed");
}
