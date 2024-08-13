<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

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
    if (isset($_POST['update_info'])) {
        $updatedInfo = array_intersect_key(
            $_POST, array_flip(['email', 'first_name', 'last_name'])
        );
        updateUserInfo($dbConfig, $_SESSION['username'], $updatedInfo['email'], $updatedInfo['first_name'], $updatedInfo['last_name']);
    }
    if (isset($_POST['update_profile_pic']) && isset($_FILES['profile_pic'])) {
        updateUserProfilePic($dbConfig, $_SESSION['username'], $_FILES['profile_pic']);
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

    <!-- Formulaire pour les informations personnelles -->
    <form method="post" class="row g-3">
        <input type="hidden" name="update_info" value="1">
        <h3>Informations personnelles</h3>
        <p class="card-text text-muted">
            Information&nbsp;: votre profil public sera accessible ici&nbsp;: <a href="https://link.neodraco.fr/<?= htmlspecialchars($_SESSION['username']) ?>" hreflang="fr" target="_blank">https://link.neodraco.fr/<?= htmlspecialchars($_SESSION['username']) ?></a>
        </p>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $userInfo['username'] ?>" disabled>
            </div>
        </div>
        <?php foreach ($formFields as $fieldName => $fieldData) :
            $label = $fieldData[0];
            $type = $fieldData[1];
            ?>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="<?= $fieldName ?>" class="form-label"><?= $label ?></label>
                    <input type="<?= $type ?>" class="form-control" id="<?= $fieldName ?>" name="<?= $fieldName ?>"
                           value="<?= $userInfo[$fieldName] ?>">
                </div>
            </div>
        <?php endforeach; ?>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Mettre à jour les informations</button>
        </div>
    </form>

    <!-- Formulaire pour changer l'image de profil -->
    <form method="post" enctype="multipart/form-data" class="row g-3 mt-4">
        <input type="hidden" name="update_profile_pic" value="1">
        <h3>Changer l'image de profil</h3>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <?php if (!empty($userInfo['profile_pic_name'])): ?>
                            <img src="/images/profile_pic/<?= $userInfo['profile_pic_name'] ?>" class="img-fluid rounded-start" alt="Photo de profil">
                        <?php else: ?>
                            <img src="/images/default.png" class="img-fluid rounded-start" alt="Photo de profil par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Photo de profil</h5>
                            <label for="profile_pic" class="form-label">Choisir une nouvelle image</label>
                            <p class="text-muted">Veuillez sélectionner une image au format carré.</p>
                            <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Mettre à jour l'image de profil</button>
        </div>
    </form>

    <!-- Formulaire pour changer le mot de passe -->
    <form method="post" class="row g-3 mt-4">
        <input type="hidden" name="update_password" value="1">
        <h3>Changer le mot de passe</h3>
        <div class="col-md-6">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="col-md-6">
            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
        </div>
    </form>
</div>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>