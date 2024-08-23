<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php

global $dbConfig;
session_start();

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>

<body>

<?php require 'php/menu.php' ?>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h1 class="text-center mb-0">À propos</h1>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h2 class="h4">Développeur :</h2>
                <p class="lead">
                    <a class="text-decoration-none" href="https://noaledet.fr" hreflang="fr" rel="external" target="_blank">Noa LEDET</a>
                </p>
            </div>
            <div class="mb-4">
                <h2 class="h4">Problèmes et issues :</h2>
                <p class="lead">
                    <a class="text-decoration-none" href="https://github.com/TheGuardianLight/NeodracoLink/issues" target="_blank">Github</a>
                </p>
            </div>
            <div class="mb-4">
                <h2 class="h4">Versions :</h2>
                <table class="table table-striped ml-4 table_about">
                    <thead>
                    <tr>
                        <th>Technologie</th>
                        <th>Version</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($versions as $tech => $version): ?>
                        <tr>
                            <td><?= ucfirst($tech) ?></td>
                            <td><?= $version ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mb-4">
                <h2 class="h4">Ressources :</h2>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Icônes réseaux sociaux :</strong>
                        <a class="text-decoration-none" href="https://www.streamlinehq.com/icons/logos-solid" target="_blank">Logos - Streamline HQ</a>
                    </li>
                    <li class="mb-2">
                        <strong>Icône de lien :</strong>
                        <a class="text-decoration-none" href="https://www.flaticon.com/fr/icone-gratuite/lien_2985013?term=lien&related_id=2985013" title="lien icônes" target="_blank">Créée par alkhalifi design - Flaticon</a>
                    </li>
                    <li class="mb-2">
                        <strong>Logo bluesky :</strong>
                        Par Eric Bailey : <a class="text-decoration-none" href="https://drive.google.com/drive/folders/1RDpuQOQMfM9mXQ61wUYWNZUbgvDc8r-n" rel="nofollow" target="_blank">Google Drive</a>, Domaine public, <a class="text-decoration-none" href="https://commons.wikimedia.org/w/index.php?curid=145139541" target="_blank">Lien</a>
                    </li>
                    <li>
                        <strong>Logo +18 :</strong>
                        <a class="text-decoration-none" href="https://www.flaticon.com/free-icons/18" title="18 icons" rel="nofollow" target="_blank">Créé par Freepik - Flaticon</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>