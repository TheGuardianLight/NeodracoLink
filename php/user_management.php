<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

require __DIR__ . '/../vendor/autoload.php';

function getUserInfo($dbConfig, $username) {
    $connection = getDbConnection($dbConfig);

    $query = "SELECT * FROM users LEFT JOIN user_info ON users.username = user_info.username WHERE users.username=?";
    $stmt = $connection->prepare($query);
    $stmt->execute([$username]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    return $userInfo;
}

function updateUserInfo($dbConfig, $username, $email, $first_name, $last_name) {
    $connection = getDbConnection($dbConfig);

    $updateQuery = "UPDATE users, user_info 
                    SET users.email = ?, user_info.first_name = ?, user_info.last_name = ?
                    WHERE users.username = user_info.username
                    AND users.username = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->execute([$email, $first_name, $last_name, $username]);
}

function updateUserPassword($dbConfig, $username, $password) {
    $connection = getDbConnection($dbConfig);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $updateQuery = "UPDATE users SET password = ? WHERE username = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->execute([$hashedPassword, $username]);
}

function updateUserProfilePic($dbConfig, $username, $profile_pic) {
    if ($profile_pic['error'] == 0) {
        $connection = getDbConnection($dbConfig);
        $oldUserInfo = getUserInfo($dbConfig, $username);

        // Vérifie si l'utilisateur a déjà une image de profil et supprime l'ancienne si existante
        if (!empty($oldUserInfo['profile_pic_name'])) {
            $oldImagePath = __DIR__.'/../images/profile_pic/' . $oldUserInfo['profile_pic_name'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $extension = pathinfo($profile_pic['name'], PATHINFO_EXTENSION);
        $profile_pic_name = uniqid() . '.' . $extension;
        move_uploaded_file($profile_pic['tmp_name'], __DIR__.'/../images/profile_pic/' . $profile_pic_name);

        $updateQuery = "UPDATE users SET profile_pic_name = ? WHERE username = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->execute([$profile_pic_name, $username]);
    }
}