<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

// Ajoutez session_start au début des scripts où vous utilisez $_SESSION
session_start();
require 'user_management.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDbConnection($dbConfig);
    $networkName = $_POST['network_name'];
    $networkUrl = $_POST['network_url'];
    $networkIcon = $_POST['network_icon'];
    $networkNsfw = isset($_POST['network_nsfw']) ? $_POST['network_nsfw'] : 0;
    $networkActive = isset($_POST['network_active']) ? $_POST['network_active'] : 0;
    $categoryId = $_POST['category_id'];

    if (empty($networkName)) {
        die('<div class="alert alert-warning" role="alert">Le nom du réseau est requis.</div>');
    }

    // Add network
    $sql = $conn->prepare("INSERT INTO reseaux (nom, url, icone, nsfw, active) VALUES (:networkName, :networkUrl, :networkIcon, :networkNsfw, :networkActive)");
    $sql->bindParam(':networkName', $networkName);
    $sql->bindParam(':networkUrl', $networkUrl);
    $sql->bindParam(':networkIcon', $networkIcon);
    $sql->bindParam(':networkNsfw', $networkNsfw);
    $sql->bindParam(':networkActive', $networkActive);

    try {
        $sql->execute();
        $lastInsertedId = $conn->lastInsertId(); // Get last inserted network id

        // Link network to user
        $sql = $conn->prepare("INSERT INTO users_reseaux (users_id, reseau_id, reseau_categorie) VALUES (:userName, :networkId, :categoryId)");
        $sql->bindParam(':userName', $userName);
        $sql->bindParam(':networkId', $lastInsertedId);
        $sql->bindParam(':categoryId', $categoryId);

        $sql->execute();

    } catch (PDOException $e) {
        die('<div class="alert alert-danger" role="alert">Une erreur s\'est produite lors de l\'ajout : ' . $e->getMessage() . '</div>');
    }

    echo "Ok"; // Successful operation

    $conn = null;
}