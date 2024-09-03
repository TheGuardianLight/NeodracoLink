<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig, $username;
require 'vendor/autoload.php';
require 'php/api_config.php';
require 'php/public_page_function.php';

$username = $username ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Link | <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="styles.css">
    <?php require 'php/favicon.php'; ?>
</head>
<body>

<?php
$backgroundImage = !empty($userInfo['profile_pic_name']) ? "/images/profile_pic/{$userInfo['profile_pic_name']}" : '/images/default.png';
?>

<div class="background-blur" style="background-image: url('<?= $backgroundImage ?>');"></div>

<?php require 'php/menu.php'; ?>

<div class="content">
    <main class="container my-5 text-white">
        <div class="text-center mb-5">
            <img id="profileImage" src="<?= htmlspecialchars($backgroundImage) ?>" class="rounded-circle shadow" alt="Photo de profil" style="width: 100px; height: 100px;">
            <h2 class="mt-4 text-dark">@<?= htmlspecialchars($_GET['username']) ?></h2>
        </div>

        <?php if (empty($sitesByCategory['categorized']) && empty($sitesByCategory['uncategorized'])): ?>
            <div class="alert alert-warning rounded-3">Aucun réseau disponible pour cet utilisateur.</div>
        <?php else: ?>
            <?php if (!empty($sitesByCategory['uncategorized'])): ?>
                <div class="d-flex justify-content-center mb-4">
                    <h3 class="category-title">Autres Réseaux</h3>
                </div>
                <div class="list-group mb-5">
                    <?php foreach ($sitesByCategory['uncategorized'] as $site): ?>
                        <div class="list-group-item align-items-center d-flex justify-content-between rounded-3 shadow-sm p-3 bg-body mb-3">
                            <a href="<?= htmlspecialchars($site['url']) ?>"
                               class="d-flex align-items-center site-item flex-grow-1 text-decoration-none"
                               target="_blank"
                               rel="external"
                               onclick="warnBeforeNsfw(event, '<?= htmlspecialchars($site['url']) ?>', <?= (int)$site['nsfw'] ?>, <?= (int)$site['active'] ?>)">
                                <img src="images/icon/<?= htmlspecialchars($site['icone']) ?>" class="img-fluid me-3 rounded-3 shadow" alt="Icone de <?= htmlspecialchars($site['nom']) ?>" style="width: 50px; height: 50px;">
                                <h5 class="fs-5 flex-grow-1 text-dark"><?= htmlspecialchars($site['nom']) ?></h5>
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton-<?= htmlspecialchars($site['url']) ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-lg"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?= htmlspecialchars($site['url']) ?>">
                                    <li><a class="dropdown-item" href="#" data-clipboard-text="<?= htmlspecialchars($site['url']) ?>" onclick="copyToClipboard(event)">Copier</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="share(event, '<?= htmlspecialchars($site['url']) ?>')">Partager</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php foreach ($sitesByCategory['categorized'] as $categoryName => $sites): ?>
                <div class="d-flex justify-content-center mb-4">
                    <h3 class="category-title"><?= htmlspecialchars($categoryName) ?></h3>
                </div>
                <div class="list-group mb-5">
                    <?php foreach ($sites as $site): ?>
                        <div class="list-group-item align-items-center d-flex justify-content-between rounded-3 shadow-sm p-3 bg-body mb-3">
                            <a href="<?= htmlspecialchars($site['url']) ?>"
                               class="d-flex align-items-center site-item flex-grow-1 text-decoration-none"
                               target="_blank"
                               rel="external"
                               onclick="warnBeforeNsfw(event, '<?= htmlspecialchars($site['url']) ?>', <?= (int)$site['nsfw'] ?>, <?= (int)$site['active'] ?>)">
                                <img src="images/icon/<?= htmlspecialchars($site['icone']) ?>" class="img-fluid me-3 rounded-3 shadow" alt="Icone de <?= htmlspecialchars($site['nom']) ?>" style="width: 50px; height: 50px;">
                                <h5 class="fs-5 flex-grow-1 text-dark"><?= htmlspecialchars($site['nom']) ?></h5>
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton-<?= htmlspecialchars($site['url']) ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-lg"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?= htmlspecialchars($site['url']) ?>">
                                    <li><a class="dropdown-item" href="#" data-clipboard-text="<?= htmlspecialchars($site['url']) ?>" onclick="copyToClipboard(event)">Copier</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="share(event, '<?= htmlspecialchars($site['url']) ?>')">Partager</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

<script src="js/color-thief.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const img = document.getElementById('profileImage');
        const colorThief = new ColorThief();

        if (img.complete) {
            applyColors();
        } else {
            img.addEventListener('load', applyColors);
        }

        function applyColors() {
            const palette = colorThief.getPalette(img, 2); // Get a palette of 2 colors
            const gradientColors = palette.map(color => `rgb(${color[0]}, ${color[1]}, ${color[2]})`).join(', ');

            const gradient = `linear-gradient(135deg, ${gradientColors})`;
            document.documentElement.style.setProperty('--category-background', gradient);

            // Assuming the first color in the palette is the most dominant for text color contrast
            const textColor = getContrastYIQ(palette[0][0], palette[0][1], palette[0][2]);
            document.documentElement.style.setProperty('--category-text-color', textColor);
        }

        function getContrastYIQ(r, g, b) {
            const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            return (yiq >= 128) ? 'black' : 'white';
        }
    });
</script>

<style>
    .category-title {
        display: inline-block;
        padding: 10px 20px;
        background: var(--category-background, linear-gradient(135deg, #6a11cb 0%, #2575fc 100%));
        color: var(--category-text-color, white);
        font-size: 1.5rem;
        border-radius: 12px;
        border: none;
    }

    .list-group-item {
        border: 1px solid #ddd;
    }

    .list-group-item a,
    .list-group-item img,
    .list-group-item h5,
    .list-group-item .dropdown {
        margin: 0; /* Remove margins */
        padding: 0; /* Remove padding */
    }

    .list-group-item.d-flex {
        flex-wrap: nowrap; /* Ensure no wrapping */
    }

    .site-item {
        margin: 0; /* Further ensure margins are reduced */
        padding: 0;
    }
</style>

<?php require 'php/footer.php'; ?>
<?php require 'js/bootstrap_script.html'; ?>
<script type="text/javascript" src="js/public_page.js"></script>

</body>
</html>