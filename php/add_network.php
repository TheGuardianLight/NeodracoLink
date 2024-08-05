<?php

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';
require 'user_management.php';

session_start();   // start the session
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

    if (empty($networkName)) {
        echo "Le nom du réseau est requis.";
        die();
    }

    // Add network
    $sql = $conn->prepare("INSERT INTO reseaux (nom, url, icone) VALUES (:networkName, :networkUrl, :networkIcon)");
    $sql->bindParam(':networkName', $networkName);
    $sql->bindParam(':networkUrl', $networkUrl);
    $sql->bindParam(':networkIcon', $networkIcon);

    try {
        $sql->execute();
        $lastInsertedId = $conn->lastInsertId(); // Get last inserted network id

        // Link network to user
        $sql = $conn->prepare("INSERT INTO users_reseaux (users_id, reseau_id) VALUES (:userName, :networkId)");
        $sql->bindParam(':userName', $userName);
        $sql->bindParam(':networkId', $lastInsertedId);

        $sql->execute();

    } catch(PDOException $e) {
        echo "Une erreur s'est produite lors de l'ajout : " . $e->getMessage();
        die();
    }

    echo "Ok"; // Successful operation

    $conn = null;
}