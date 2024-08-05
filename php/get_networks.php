<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;

require __DIR__ . '/../vendor/autoload.php';
require_once 'api_config.php';

$db = getDbConnection($dbConfig);

$query = $db->prepare("SELECT reseaux.* FROM reseaux JOIN users_reseaux ON reseaux.id = users_reseaux.reseau_id WHERE users_reseaux.users_id = :username");
$query->execute(['username' => $_SESSION['username']]);

$networks = $query->fetchAll();
?>

<div id="networks_cards" class="row">
    <?php foreach ($networks as $network): ?>
        <div class="col-md-6 mb-3">
            <div class="card h-100" data-network-id="<?= htmlspecialchars($network['id']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($network['nom']); ?></h5>
                    <button class="btn btn-danger remove_network_button">Supprimer</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>