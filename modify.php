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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>

<body>
<?php require 'php/menu.php' ?>

<div class="align-items-center">
    <!-- Toast de succès -->

    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
         aria-atomic="true" id="add-success-toast">
        <div class="d-flex">
            <div class="toast-body">
                Opération réussie ! Le réseau a été ajouté.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
         aria-atomic="true" id="update-success-toast">
        <div class="d-flex">
            <div class="toast-body">
                Opération réussie ! Le réseau a été mis à jour.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
         aria-atomic="true" id="remove-success-toast">
        <div class="d-flex">
            <div class="toast-body">
                Opération réussie ! Le réseau a été supprimé.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
        </div>
    </div>

    <!-- Toast d'échec -->

    <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive"
         aria-atomic="true" id="add-failure-toast">
        <div class="d-flex">
            <div class="toast-body">
                Échec de l'opération. Impossible d'ajouter le réseau.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive"
         aria-atomic="true" id="update-failure-toast">
        <div class="d-flex">
            <div class="toast-body">
                Échec de l'opération. Impossible de mettre à jour le réseau.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive"
         aria-atomic="true" id="remove-failure-toast">
        <div class="d-flex">
            <div class="toast-body">
                Échec de l'opération. Impossible de supprimer le réseau.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
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

<div class="container my-5">
    <!-- Titre principal -->
    <h1 class="text-center mb-4">Gestion des réseaux sociaux</h1>

    <div class="row">
        <!-- Section Réseaux existants -->
        <div class="col-md-8">
            <h3 class="mb-4">Réseaux existants</h3>
            <div id="networks_cards" class="row g-4">
                <?php require 'php/get_networks.php'; ?>
            </div>
        </div>

        <div class="col-md-4">
            <h3 class="mb-4">Ajouter un réseau</h3>
            <form id="add_network_form" class="p-3 bg-light shadow-sm rounded">
                <div class="mb-3">
                    <label for="network_name" class="form-label">Nom du réseau</label>
                    <input type="text" id="network_name" class="form-control" placeholder="Nom du réseau" required>
                </div>
                <div class="mb-3">
                    <label for="network_url" class="form-label">URL du réseau</label>
                    <input type="url" id="network_url" class="form-control" placeholder="URL du réseau" required>
                </div>
                <div class="mb-3">
                    <label for="network_icon" class="form-label">Icône</label>
                    <select id="network_icon" class="form-select" required>
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
                        <option value="furaffinity.png">Fur Affinity</option>
                        <option value="lien.png">Lien externe</option>
                    </select>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" id="network_nsfw" class="form-check-input">
                    <label for="network_nsfw" class="form-check-label">NSFW</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" id="network_active" class="form-check-input">
                    <label for="network_active" class="form-check-label">Actif</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
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