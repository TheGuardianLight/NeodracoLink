<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

$host = $_POST["dbhost"];
$port = $_POST["dbport"];
$dbname = $_POST["dbname"];
$dbuser = $_POST["dbuser"];
$dbpassword = $_POST["dbpassword"];

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);

try {
    $connection = new PDO($dsn, $dbuser, $dbpassword);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        CREATE TABLE `categorie`  (
            `cat_id` int NOT NULL AUTO_INCREMENT,
            `cat_name` varchar(255) NULL,
            PRIMARY KEY (`cat_id`)
        ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
        
        CREATE TABLE `reseaux`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `icone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `nsfw` tinyint(1) NULL DEFAULT NULL,
            `active` tinyint(1) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        
        CREATE TABLE `user_info`  (
            `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`username`) USING BTREE
        ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
        
        CREATE TABLE `users`  (
            `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `profile_pic_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`username`) USING BTREE
        ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        
        CREATE TABLE `users_reseaux`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `users_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `reseau_id` int(11) NULL DEFAULT NULL,
            `reseau_order` int(11) NULL DEFAULT NULL,
            `reseau_categorie` int NOT NULL,
            PRIMARY KEY (`id`, `reseau_categorie`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
        
        ALTER TABLE `user_info` ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE RESTRICT;
        ALTER TABLE `users_reseaux` ADD CONSTRAINT `users_reseaux_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE RESTRICT;
        ALTER TABLE `users_reseaux` ADD CONSTRAINT `users_reseaux_ibfk_2` FOREIGN KEY (`reseau_id`) REFERENCES `reseaux` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
        ALTER TABLE `users_reseaux` ADD CONSTRAINT `users_reseaux_ibfk_3` FOREIGN KEY (`reseau_categorie`) REFERENCES `categorie` (`cat_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
    ";

    $connection->exec($sql);

    $envPath = __DIR__ . '/../.env';
    $env = [];

    if (file_exists($envPath)) {
        try {
            $dotenv = new Dotenv();
            $env = $dotenv->parse(file_get_contents($envPath), $envPath);
        } catch (PathException $exception) {
            throw new Exception('Erreur de chemin du fichier .env: ' . $exception->getMessage());
        }
    }

    $env['DB_HOST'] = $host;
    $env['DB_PORT'] = $port;
    $env['DB_NAME'] = $dbname;
    $env['DB_USER'] = $dbuser;
    $env['DB_PASSWORD'] = $dbpassword;
    $env['ALLOW_SIGNUP'] = "true";

    $envData = '';
    foreach ($env as $key => $value) {
        $envData .= sprintf("%s=\"%s\"\n", $key, $value);
    }

    file_put_contents($envPath, $envData);

    touch('../install.lock');

    echo json_encode(['success' => 'La base de données a été configurée avec succès.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données', 'details' => 'Erreur de base de données: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur d\'installation', 'details' => $e->getMessage()]);
}