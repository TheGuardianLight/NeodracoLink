<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';



// Récupérer les données POST
$networkId = $_POST['networkId'] ?? null;
$newOrder = $_POST['newOrder'] ?? null;

// Vérifier que les données POST sont valides
if ($networkId === null || $newOrder === null) {
    echo json_encode(['status' => 'error', 'error' => 'networkId or newOrder not provided']);
    exit;
}

$db = getDbConnection($dbConfig);

// Préparer la requête SQL pour mettre à jour l'ordre du réseau
$query = $db->prepare("UPDATE users_reseaux SET reseau_order = :reseau_order WHERE reseau_id = :networkId AND users_id = :userId");

// Exécuter la requête avec les nouvelles données
$result = $query->execute([
    ':reseau_order' => $newOrder,
    ':networkId' => $networkId,
    ':userId' => $_SESSION['username']
]);

// Vérifier si l'opération a réussi
if ($result && $query->rowCount() > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'error' => 'Failed to update order']);
}