<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;

require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

    // remove_network.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Connexion à la base de données et autres inclusions...
        require_once 'api_config.php';

        $db = getDbConnection($dbConfig);

        // Sécurisation des données envoyées par le POST.
        $networkId = $_POST['network_id']; // Vous devriez valider cette donnée avant de l'utiliser.

        $query = $db->prepare("DELETE FROM reseaux WHERE id = :networkId");
        $result = $query->execute(['networkId' => $networkId]);

        if ($result) {
            echo "Ok";
        } else {
            echo "Erreur lors de la suppression.";
        }
    } else {
        echo "Méthode non autorisée.";
    }