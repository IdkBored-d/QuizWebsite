<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'finalproject';
$db_charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;dbname=$db_db;charset=$db_charset";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $db_user, $db_password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo 'Success: A proper connection to MySQL was made.<br>';
    echo 'Database: ' . $db_db . '<br>';
    echo 'Connection Charset: ' . $db_charset . '<br>';
} catch (PDOException $e) {
    // Handle connection errors
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>