<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

global $dbConfig;

require __DIR__ . '/../vendor/autoload.php';
require_once 'api_config.php';

$db = getDbConnection($dbConfig);

$query = $db->prepare("SELECT reseaux.*, users_reseaux.reseau_order FROM reseaux JOIN users_reseaux ON reseaux.id = users_reseaux.reseau_id WHERE users_reseaux.users_id = :username ORDER BY users_reseaux.reseau_order");
$query->execute(['username' => $_SESSION['username']]);

$networks = $query->fetchAll();
?>

<div id="networks_cards" class="row">
    <?php foreach ($networks as $network): ?>
        <!-- Votre code pour chaque carte de réseau -->
        <div class="col-md-6 mb-3">
            <div class="card h-100" data-network-id="<?= htmlspecialchars($network['id']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($network['nom']); ?></h5>
                    <!-- Ajouter un champ d'input pour le reseau_order avec la valeur actuelle -->
                    <label for="order-<?= htmlspecialchars($network['id']); ?>">Ordre:</label>
                    <input type="number" id="order-<?= htmlspecialchars($network['id']); ?>" value="<?= htmlspecialchars($network['reseau_order']); ?>" min="1">
                    <!-- Ajouter un bouton pour soumettre le reseau_order mis à jour -->
                    <button class="btn btn-primary update-order">Mettre à jour l'ordre</button>
                    <button class="btn btn-danger remove_network_button">Supprimer</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>