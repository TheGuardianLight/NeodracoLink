<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;
session_start();

require __DIR__ . '/vendor/autoload.php';
require 'php/api_config.php';
require_once 'php/user_management.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Gestion des réseaux sociaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>

<body>
<?php require 'php/menu.php' ?>

<div class="align-items-center">
<!-- Toast de succès -->

<div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="add-success-toast">
    <div class="d-flex">
        <div class="toast-body">
            Opération réussie ! Le réseau a été ajouté.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
</div>

<div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="update-success-toast">
    <div class="d-flex">
        <div class="toast-body">
            Opération réussie ! Le réseau a été mis à jour.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
</div>

<div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="remove-success-toast">
    <div class="d-flex">
        <div class="toast-body">
            Opération réussie ! Le réseau a été supprimé.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
</div>

<!-- Toast d'échec -->

<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="add-failure-toast">
    <div class="d-flex">
        <div class="toast-body">
            Échec de l'opération. Impossible d'ajouter le réseau.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
</div>

<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="update-failure-toast">
    <div class="d-flex">
        <div class="toast-body">
            Échec de l'opération. Impossible de mettre à jour le réseau.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
</div>

<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="remove-failure-toast">
    <div class="d-flex">
        <div class="toast-body">
            Échec de l'opération. Impossible de supprimer le réseau.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
</div>

<!-- Modal d'erreur -->

<div class="modal" id="error-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="error-modal-label">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="error-modal-body">
                <!-- Le message d'erreur sera inséré ici -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
</div>

<div class="container my-3"> <!-- ajout d'un espace à l'extérieur du conteneur (marge en haut et en bas) -->
    <h1 class="mb-3">Gestion des réseaux sociaux</h1> <!-- ajout d'un espace en dessous du titre (marge en bas) -->
    <div class="row">
        <div class="col">
            <h3 class="mb-3">Réseaux existants</h3>
            <div id="networks_cards" class="row">
                <?php require 'php/get_networks.php'; ?>
            </div>
        </div>

        <div class="col mt-3">
            <h3 class="mb-3">Ajouter un réseau</h3>
            <form id="add_network_form">
                <div class="mb-3">
                    <input type="text" class="form-control mb-2" id="network_name" placeholder="Nom du réseau" required>
                    <input type="url" class="form-control mb-2" id="network_url" placeholder="URL du réseau" required>
                    <select class="form-select mb-2" id="network_icon" required>
                        <option value="">Choisissez une icône...</option>
                        <option value="discord.svg">Discord</option>
                        <option value="telegram.svg">Telegram</option>
                        <option value="email.svg">Email</option>
                        <option value="facebook.svg">Facebook</option>
                        <option value="github.svg">Github</option>
                        <option value="mastodon.svg">Mastodon</option>
                        <option value="patreon.svg">Patreon</option>
                        <option value="tiktok.svg">Tiktok</option>
                        <option value="x_twitter.svg">Twitter</option>
                        <option value="instagram.svg">Instagram</option>
                        <option value="bluesky.svg">Bluesky</option>
                        <option value="lien.png">Lien externe</option>
                    </select>
                    <button type="submit" class="btn btn-primary m-1">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="error-modal" tabindex="-1" aria-labelledby="error-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="error-modal-label">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="error-modal-body">
                <!-- Le message d'erreur sera inséré ici -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

</body>

<?php require 'php/footer.php' ?>

<script type="text/javascript" src="js/modify.js"></script>

<?php require 'js/bootstrap_script.html' ?>
</html>