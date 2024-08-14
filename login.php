<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php
global $dbConfig, $config;
require 'vendor/autoload.php';
require 'php/api_config.php';
require 'php/get_login.php';

function getLoginFormError() {
    global $message, $messageType;

    if(empty($message)) return;

    echo "<div class=\"d-grid gap-2 mt-3 alert alert-$messageType\" role=\"alert\">$message</div>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <h2 class="text-center my-4">Connexion</h2>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>

                <div class="form-group mt-2">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" name="login">Se connecter</button>
                    <?php if ($config['allowSignup'] == "true"): ?>
                        <button type="button" class="btn btn-secondary" onclick="location.href='register.php'">S'inscrire</button>
                    <?php endif; ?>
                </div>
                <?php getLoginFormError(); ?>
            </form>
        </div>
    </div>
</div>

</body>
</html>