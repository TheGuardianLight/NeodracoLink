<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$dbConfig = [
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
];

$config = [
    'allowSignup' => $_ENV['ALLOW_SIGNUP'],
];

// Function to get the database connection
function getDbConnection($dbConfig): PDO
{
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};port={$dbConfig['port']}";
    $user = $dbConfig['user'];
    $password = $dbConfig['password'];
    $connection = "";
    try {
        $connection = new PDO($dsn, $user, $password);
    } catch (PDOException $exception) {
        echo "Connection failed: " . $exception->getMessage();
    }

    return $connection;
}