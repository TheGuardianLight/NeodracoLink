<?php global $linkId, $linkData;

/**
 * Copyright (c) 2024 - Veivneorul.
 * This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';
require_once 'php/user_management.php';
require 'php/detail-link_function.php';

$categories = getCategories();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Link | Détail d'un site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Modifier le lien</h1>
    <form action="detail-link.php?id=<?= htmlspecialchars($linkId) ?>" method="post" class="shadow p-4 rounded bg-light">

        <div class="form-group mb-3">
            <label for="network_name" class="form-label">Nom<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="network_name" name="name" value="<?= htmlspecialchars($linkData['nom']) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="network_url" class="form-label">URL<span class="text-danger">*</span></label>
            <input type="url" class="form-control" id="network_url" name="url" value="<?= htmlspecialchars($linkData['url']) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="network_icon" class="form-label">Icône<span class="text-danger">*</span></label>
            <select class="form-select" id="network_icon" name="icon" required>
                <option value="">Choisissez une icône...</option>
                <?php
                $icons = [
                    "discord.svg" => "Discord", "telegram.svg" => "Telegram", "email.svg" => "Email",
                    "facebook.svg" => "Facebook", "github.svg" => "Github", "mastodon.svg" => "Mastodon",
                    "patreon.svg" => "Patreon", "tiktok.svg" => "Tiktok", "x_twitter.svg" => "Twitter",
                    "instagram.svg" => "Instagram", "bluesky.svg" => "Bluesky", "furaffinity.png" => "Fur Affinity",
                    "lien.png" => "Lien externe"
                ];
                foreach ($icons as $fileName => $displayName) {
                    $selected = $linkData['icone'] == $fileName ? 'selected' : '';
                    echo "<option value=\"{$fileName}\" {$selected}>{$displayName}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="network_category" class="form-label">Catégorie</label>
            <select class="form-select" id="network_category" name="category">
                <option value="">Choisissez une catégorie...</option>
                <?php
                foreach ($categories as $category) {
                    // Vérifie si le lien a une catégorie, sinon utilise une valeur par défaut.
                    $selected = (isset($linkData['reseau_categorie']) && $linkData['reseau_categorie'] == $category['cat_id']) ? 'selected' : '';
                    echo "<option value=\"{$category['cat_id']}\" {$selected}>{$category['cat_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="network_order" class="form-label">Ordre<span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="network_order" name="order" value="<?= htmlspecialchars($linkData['reseau_order']) ?>" required>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="network_nsfw" name="nsfw" <?= $linkData['nsfw'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="network_nsfw">NSFW</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="network_active" name="active" <?= $linkData['active'] ? 'checked' : '' ?>>
            <label class="form-check-label" for "network_active">Active</label>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">Enregistrer les modifications</button>
        </div>
    </form>
</div>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>