<?php
$conn;

function dbConnect()
{
    global $conn;
    $servername = 'db'; 
    $username = 'root';
    $password = 'mariadb';
    $dbname = 'todo_app';

    try {
        // Anslut till databasen
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        // echo "Connection success"; 
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
