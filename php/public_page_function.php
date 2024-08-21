<?php
/**
 * Copyright (c) 2024 - Veivneorul.
 * This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;

$username = $_GET['username'] ?? '';

if (empty($username)) {
    die("Username is required");
}

$conn = getDbConnection($dbConfig);

function fetchUserInfo($conn, $username) {
    $stmt = $conn->prepare("SELECT profile_pic_name FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function fetchUserSites($conn, $username) {
    $stmt = $conn->prepare("
        SELECT reseaux.nom, reseaux.url, reseaux.icone, reseaux.nsfw, reseaux.active 
        FROM reseaux 
        JOIN users_reseaux ON reseaux.id = users_reseaux.reseau_id 
        WHERE users_reseaux.users_id = :username 
        ORDER BY users_reseaux.reseau_order
    ");
    $stmt->execute(['username' => $username]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$userInfo = fetchUserInfo($conn, $username);
if (!$userInfo) {
    die("User not found");
}

$sites = fetchUserSites($conn, $username);