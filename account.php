<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php
global $dbConfig;
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';
require_once 'php/user_management.php';

$userInfo = getUserInfo($dbConfig, $_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updatedInfo = array_intersect_key(
        $_POST, array_flip(['email', 'first_name', 'last_name', 'password'])
    );
    updateUserInfo($dbConfig, $_SESSION['username'], ...array_values($updatedInfo));
    $userInfo = getUserInfo($dbConfig, $_SESSION['username']);
}

$formFields = [
    'username' => ['Nom d\'utilisateur', 'text', true],
    'email' => ['Email', 'email'],
    'first_name' => ['Prénom', 'text'],
    'last_name' => ['Nom de famille', 'text'],
    'password' => ['New password', 'password'],
    'confirm_password' => ['Confirm New password', 'password']
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mon compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container my-3">
    <form method="post" class="row g-3">
        <?php foreach ($formFields as $fieldName => $fieldData) :
            $label = $fieldData[0];
            $type = $fieldData[1];
            $disabled = $fieldData[2] ?? false;
            ?>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="<?= $fieldName ?>" class="form-label"><?= $label ?></label>
                    <input type="<?= $type ?>" class="form-control" id="<?= $fieldName ?>" name="<?= $fieldName ?>"
                           value="<?= $fieldName === 'password' || $fieldName === 'confirm_password' ? '' : $userInfo[$fieldName] ?>"
                        <?= $disabled ? 'disabled' : '' ?>>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>

<?php require 'php/footer.php'?>

</body>
</html>