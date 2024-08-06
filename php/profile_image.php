<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */


global $dbConfig;
$username = $_SESSION['username'] ?? null;
$imagePath = null;

if ($username !== null) {
    $userInfo = getUserInfo($dbConfig, $username);
    if (!empty($userInfo['profile_pic_name'])) {
        $imagePath = "/images/profile_pic/" . $userInfo['profile_pic_name'];
    }
}
