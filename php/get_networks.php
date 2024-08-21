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

<div id="networks_cards" class="row g-4">
    <?php foreach ($networks as $network): ?>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm" data-network-id="<?= htmlspecialchars($network['id']); ?>">
                <div class="card-body">
                    <!-- Titre de la carte -->
                    <h5 class="card-title"><?= htmlspecialchars($network['nom']); ?></h5>

                    <!-- Ordre du réseau -->
                    <div class="mb-3">
                        <label for="order-<?= htmlspecialchars($network['id']); ?>" class="form-label">Ordre:</label>
                        <input type="number" class="form-control" id="order-<?= htmlspecialchars($network['id']); ?>" value="<?= htmlspecialchars($network['reseau_order']); ?>" min="1">
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary update-order">Mettre à jour l'ordre</button>
                        <button class="btn btn-danger remove_network_button">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card.h-100');

        cards.forEach(card => {
            card.addEventListener('click', function(event) {
                // Vérifier si le clic provient du bouton "Supprimer"
                const removeButton = card.querySelector('.remove_network_button');

                if (!removeButton.contains(event.target)) {
                    const networkId = card.getAttribute('data-network-id');
                    window.location.href = `detail-link.php?id=${networkId}`;
                }
            });
        });
    });
</script>