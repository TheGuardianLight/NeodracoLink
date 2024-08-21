<?php

global $dbConfig;

/**
 * Copyright (c) 2024 - Veivneorul.
 * This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

if (!function_exists('fetchLinkData')) {
    // Fonction pour récupérer les données du lien
    function fetchLinkData($conn, $linkId) {
        $query = $conn->prepare("SELECT * FROM reseaux WHERE id = :id");
        $query->execute(['id' => $linkId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('updateLinkData')) {
    // Fonction pour mettre à jour les données du lien
    function updateLinkData($conn, $linkId, $name, $url, $icon, $nsfw, $active) {
        $updateQuery = $conn->prepare(
            "UPDATE reseaux
             SET nom = :nom, url = :url, icone = :icone, nsfw = :nsfw, active = :active
             WHERE id = :id"
        );
        $updateQuery->execute([
            'nom' => $name,
            'url' => $url,
            'icone' => $icon,
            'nsfw' => $nsfw,
            'active' => $active,
            'id' => $linkId
        ]);
    }
}

// Connexion à la base de données
$conn = getDbConnection($dbConfig);

$linkId = $_GET['id'] ?? null;
if (empty($linkId)) {
    die("Invalid link ID");
}

// Récupérer les données du lien
$linkData = fetchLinkData($conn, $linkId);

if (!$linkData) {
    die("Link not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $url = $_POST['url'] ?? '';
    $icon = $_POST['icon'] ?? '';
    $nsfw = isset($_POST['nsfw']) ? 1 : 0;
    $active = isset($_POST['active']) ? 1 : 0;

    updateLinkData($conn, $linkId, $name, $url, $icon, $nsfw, $active);

    header("Location: detail-link.php?id={$linkId}");
    exit;
}