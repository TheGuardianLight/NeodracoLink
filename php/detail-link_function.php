<?php

global $dbConfig;

/**
 * Copyright (c) 2024 - Veivneorul.
 * This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

if (!function_exists('fetchLinkData')) {
    // Fonction pour récupérer les données du lien
    function fetchLinkData($conn, $linkId) {
        $query = $conn->prepare(
            "SELECT r.*, ur.reseau_order, ur.reseau_categorie 
             FROM reseaux r
             JOIN users_reseaux ur ON r.id = ur.reseau_id
             WHERE r.id = :id"
        );
        $query->execute(['id' => $linkId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('updateLinkData')) {
    // Fonction pour mettre à jour les données du lien dans `reseaux` et `users_reseaux`
    function updateLinkData($conn, $linkId, $name, $url, $icon, $nsfw, $active, $order, $category) {
        // Mise à jour de la table `reseaux`
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

        // Mise à jour de la table `users_reseaux`
        $updateUsersReseauxQuery = $conn->prepare(
            "UPDATE users_reseaux
             SET reseau_order = :reseau_order, reseau_categorie = :reseau_categorie
             WHERE reseau_id = :reseau_id"
        );
        $updateUsersReseauxQuery->execute([
            'reseau_order' => $order,
            'reseau_categorie' => $category,
            'reseau_id' => $linkId
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

// Récupérer les catégories
$categories = getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $url = $_POST['url'] ?? '';
    $icon = $_POST['icon'] ?? '';
    $order = $_POST['order'] ?? 0;
    $nsfw = isset($_POST['nsfw']) ? 1 : 0;
    $active = isset($_POST['active']) ? 1 : 0;
    $category = $_POST['category'] ?? null;

    updateLinkData($conn, $linkId, $name, $url, $icon, $nsfw, $active, $order, $category);

    header("Location: detail-link.php?id={$linkId}");
    exit;
}

// Fonction pour obtenir les catégories
function getCategories() {
    global $dbConfig;

    try {
        $pdo = getDbConnection($dbConfig);
        $stmt = $pdo->prepare("SELECT cat_id, cat_name FROM categorie");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching categories: " . $e->getMessage());
    }
}