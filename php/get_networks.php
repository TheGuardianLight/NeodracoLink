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

// Mapper les noms de réseaux à leurs icônes Bootstrap
$iconMap = [
    'discord' => 'bi-discord',
    'telegram' => 'bi-telegram',
    'email' => 'bi-envelope',
    'facebook' => 'bi-facebook',
    'github' => 'bi-github',
    'mastodon' => 'bi-mastodon',
    'patreon' => 'bi-patreon', // Note: Bootstrap Icons may not have specific icons for all networks; fallback or custom handling may be required.
    'tiktok' => 'bi-tiktok',
    'twitter' => 'bi-twitter',
    'instagram' => 'bi-instagram',
    'bluesky' => 'bi-cloud',
    'furaffinity' => 'bi-heart',
    'lien' => 'bi-link',
];
?>

<div id="networks_cards" class="row g-3">
    <?php foreach ($networks as $network): ?>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm" data-network-id="<?= htmlspecialchars($network['id']); ?>">
                <div class="card-body d-flex flex-column justify-content-between p-3">
                    <!-- Titre de la carte avec icône -->
                    <div class="d-flex align-items-center mb-2">
                        <?php
                        $networkName = strtolower(pathinfo($network['icone'], PATHINFO_FILENAME));
                        $iconClass = $iconMap[$networkName] ?? 'bi-question-circle'; // Fallback icon
                        ?>
                        <i class="<?= $iconClass ?> me-2" style="font-size: 1.25rem;"></i>
                        <h5 class="card-title mb-0"><?= htmlspecialchars($network['nom']); ?></h5>
                    </div>
                    <!-- Empty spacer to push the button to the bottom -->
                    <div class="flex-grow-1"></div>
                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-end mt-2">
                        <button class="btn btn-danger remove_network_button btn-sm">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.card.h-100');

        cards.forEach(card => {
            card.addEventListener('click', function (event) {
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