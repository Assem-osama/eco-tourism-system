<?php

$databaseHost = "localhost";
$databaseName = "eco_tourism_final";
$databaseUsername = "root";
$databasePassword = "";
$databasePort = "3306";

try {
    $databaseConnection = new PDO(
        "mysql:host=$databaseHost;port=$databasePort;dbname=$databaseName;charset=utf8mb4",
        $databaseUsername,
        $databasePassword
    );
    $databaseConnection->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $databaseConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    die("Database Connection Failed: " . $exception->getMessage());
}
