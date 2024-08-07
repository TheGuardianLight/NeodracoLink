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

$json = file_get_contents('php/versions.json');
$versions = json_decode($json, true);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>A propos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>

<body>

<?php require 'php/menu.php' ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="text-center mb-4">A propos :</h1>
            <p class="lead">
                <strong>Développeur&nbsp;:</strong> <a class="text-decoration-none" href="https://noaledet.fr" hreflang="fr" rel="external" target="_blank">Noa LEDET</a>
            </p>
            <p class="lead">
                <strong>Problèmes et issues&nbsp;:</strong> <a class="text-decoration-none" href="https://github.com/TheGuardianLight/hub-site/issues" target="_blank">Github</a>
            </p>
            <p class="lead">
                <strong>Versions&nbsp;:</strong>
            </p>
            <table class="table table-striped ml-4 table_about">
                <?php foreach($versions as $tech => $version): ?>
                    <tr>
                        <td><?= ucfirst($tech) ?></td>
                        <td><?= $version ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="card-body">
            <p class="lead">
                <strong>Icônes&nbsp;:</strong>
                <a class="icon-link" href="https://www.streamlinehq.com/icons/logos-solid">
                    https://www.streamlinehq.com/icons/logos-solid
                </a>
            </p>
        </div>
    </div>
</div>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>