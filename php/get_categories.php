<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

$conn = getDbConnection($dbConfig);

try {
    $sql = "SELECT cat_id, cat_name FROM categorie";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Erreur: ' . $e->getMessage()));
}

$conn = null;
?>