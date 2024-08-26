<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;
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

function updateUserProfilePic($dbConfig, $username, $profile_pic_base64) {
    if (!empty($profile_pic_base64)) {
        $connection = getDbConnection($dbConfig);
        $oldUserInfo = getUserInfo($dbConfig, $username);

        // Vérifie si l'utilisateur a déjà une image de profil et supprime l'ancienne si existante
        if (!empty($oldUserInfo['profile_pic_name'])) {
            $oldImagePath = __DIR__ . '/../images/profile_pic/' . $oldUserInfo['profile_pic_name'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $profile_pic_name = uniqid() . '.png';
        $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $profile_pic_base64));
        file_put_contents(__DIR__ . '/../images/profile_pic/' . $profile_pic_name, $image_data);

        $updateQuery = "UPDATE users SET profile_pic_name = ? WHERE username = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->execute([$profile_pic_name, $username]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_info'])) {
        $updatedInfo = array_intersect_key(
            $_POST, array_flip(['email', 'first_name', 'last_name'])
        );
        updateUserInfo($dbConfig, $_SESSION['username'], $updatedInfo['email'], $updatedInfo['first_name'], $updatedInfo['last_name']);
    }
    if (isset($_POST['update_profile_pic']) && !empty($_POST['profile_pic_cropped'])) {
        updateUserProfilePic($dbConfig, $_SESSION['username'], $_POST['profile_pic_cropped']);
    }
    if (isset($_POST['update_password'])) {
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        if ($password === $confirm_password) {
            updateUserPassword($dbConfig, $_SESSION['username'], $password);
        } else {
            echo "Les mots de passe ne correspondent pas.";
        }
    }
    $userInfo = getUserInfo($dbConfig, $_SESSION['username']);
}

$formFields = [
    'email' => ['Email', 'email'],
    'first_name' => ['Prénom', 'text'],
    'last_name' => ['Nom de famille', 'text']
];