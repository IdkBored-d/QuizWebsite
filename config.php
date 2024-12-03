<?php
//    const DB_SERVER = "localhost";
//    const DB_USER = "root";
//    const DB_PASSWORD = "root";
//    const DB_NAME = "finalproject";
//
//    function getDB(){
//        try{
//            $pdo = new PDO("mysql:host=" . DB_SERVER .
//                ";dbname=" . DB_NAME,
//                DB_USER,
//                DB_PASSWORD);
//            $pdo->setAttribute(PDO::ATTR_ERRMODE,
//                PDO::ERRMODE_EXCEPTION);
//            return $pdo;
//        }
//        catch(PDOException $e){
//            echo $e->getMessage();
//        }
//    }

$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'finalproject';
$db_port = 3306;

$pdo = new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
);

if ($pdo->connect_error) {
    echo 'Errno: ' . $pdo->connect_errno;
    echo '<br>';
    echo 'Error: ' . $pdo->connect_error;
    exit();
}

echo 'Success: A proper connection to MySQL was made.';
echo '<br>';
echo 'Host information: ' . $pdo->host_info;
echo '<br>';
echo 'Protocol version: ' . $pdo->protocol_version;

$pdo->close();