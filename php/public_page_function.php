<?php global $dbConfig;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

$username = $_GET['username'] ?? '';

if (empty($username)) {
    echo "Username is required";
    exit;
}

$conn = getDbConnection($dbConfig);

// Requête pour obtenir les informations de l'utilisateur
$queryUser = $conn->prepare("SELECT profile_pic_name FROM users WHERE username = :username");
$queryUser->execute(['username' => $username]);
$userInfo = $queryUser->fetch(PDO::FETCH_ASSOC);

$querySites = $conn->prepare("SELECT reseaux.nom, reseaux.url, reseaux.icone, reseaux.nsfw, reseaux.active FROM reseaux JOIN users_reseaux ON reseaux.id = users_reseaux.reseau_id WHERE users_reseaux.users_id = :username ORDER BY users_reseaux.reseau_order");
$querySites->execute(['username' => $username]);
$sites = $querySites->fetchAll(PDO::FETCH_ASSOC);