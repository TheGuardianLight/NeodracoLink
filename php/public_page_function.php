<?php
/**
 * Copyright (c) 2024 - Veivneorul.
 * This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;

$username = $_GET['username'] ?? '';

if (empty($username)) {
    die('
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <title>Erreur</title>
</head>
<body>
    <div class="alert alert-danger" role="alertdialog">Le nom d\'utilisateur est requis&nbsp;!</div>
</body>
</html>
');
}

$conn = getDbConnection($dbConfig);

function fetchUserInfo($conn, $username) {
    $stmt = $conn->prepare("SELECT profile_pic_name FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function fetchUserSitesByCategory($conn, $username) {
    $stmt = $conn->prepare("
        SELECT r.nom, r.url, r.icone, r.nsfw, r.active, c.cat_name 
        FROM reseaux r
        JOIN users_reseaux ur ON r.id = ur.reseau_id
        LEFT JOIN categorie c ON ur.reseau_categorie = c.cat_id
        WHERE ur.users_id = :username
        ORDER BY c.cat_name IS NULL, c.cat_name, ur.reseau_order
    ");
    $stmt->execute(['username' => $username]);
    $sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $categorizedSites = [];
    $uncategorized = [];

    foreach ($sites as $site) {
        if (empty($site['cat_name'])) {
            $uncategorized[] = $site;
        } else {
            $categoryName = $site['cat_name'];
            if (!isset($categorizedSites[$categoryName])) {
                $categorizedSites[$categoryName] = [];
            }
            $categorizedSites[$categoryName][] = $site;
        }
    }
    return ['categorized' => $categorizedSites, 'uncategorized' => $uncategorized];
}

$userInfo = fetchUserInfo($conn, $username);
if (!$userInfo) {
    die("User not found");
}

$sitesByCategory = fetchUserSitesByCategory($conn, $username);

$backgroundImage = '/path/to/default/image.jpg';
if (!empty($userInfo['profile_pic_name'])) {
    $backgroundImage = '/path/to/profile/pics/' . htmlspecialchars($userInfo['profile_pic_name']);
}