<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

require __DIR__ . '/../vendor/autoload.php';
function getUserInfo($dbConfig, $username){
    $connection = getDbConnection($dbConfig);

    $query = "SELECT * FROM users LEFT JOIN user_info ON users.username = user_info.username WHERE users.username=?";
    $stmt = $connection->prepare($query);
    $stmt->execute([$username]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    return $userInfo;
}

function updateUserInfo($dbConfig, $username, $email, $first_name, $last_name, $password, $profile_pic){
    $connection = getDbConnection($dbConfig);
    $password = password_hash($password, PASSWORD_BCRYPT);

    $profile_pic_name = '';
    if($profile_pic['error'] == 0) {
        $extension = pathinfo($profile_pic['name'], PATHINFO_EXTENSION);
        $profile_pic_name = uniqid() . '.' . $extension;
        move_uploaded_file($profile_pic['tmp_name'], __DIR__.'/../images/profile_pic/' . $profile_pic_name);
    }

    $updateQuery = "UPDATE users, user_info 
                    SET users.email = ?, user_info.first_name = ?, user_info.last_name = ?, users.password = ?, users.profile_pic_name = ?
                    WHERE users.username = user_info.username
                    AND users.username = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->execute([$email, $first_name, $last_name, $password, $profile_pic_name, $username]);
}