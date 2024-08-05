<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;

require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = getDbConnection($dbConfig);
    $network_id = $_POST['network_id'];

    if (empty($network_id)) {
        echo "L'ID du réseau est requis.";
        exit();
    }

    // delete from users_reseaux table first
    $sql = $conn->prepare("DELETE FROM users_reseaux WHERE reseau_id = :network_id");
    $sql->bindParam(':network_id', $network_id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo "Erreur lors de la suppression de 'users_reseaux' : " . $e->getMessage();
        exit();
    }

    $sql = $conn->prepare("DELETE FROM reseaux WHERE id = :network_id");
    $sql->bindParam(':network_id', $network_id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo "Erreur lors de la suppression de 'reseaux' : " . $e->getMessage();
        exit();
    }

    echo "Ok";

    $conn = null;
}