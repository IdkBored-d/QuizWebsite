<?php
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_NAME = "finalproject";

    function getDB(){
        try{
            $pdo = new PDO("mysql:host=" . DB_SERVER .
                ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
