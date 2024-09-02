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
        <div class="text-center mb-4">
            <img src="<?= htmlspecialchars($backgroundImage) ?>" class="rounded-circle" alt="Photo de profil" style="width: 100px; height: 100px;">
            <h2 class="mt-3 text-black">@<?= htmlspecialchars($_GET['username']) ?></h2>
        </div>

        <?php if (empty($sites)): ?>
            <div class="alert alert-warning rounded-3">Aucun réseau disponible pour cet utilisateur.</div>
        <?php else: ?>
            <?php foreach ($sites as $site): ?>
                <div class="list-group mb-3">
                    <div class="list-group-item align-items-start d-flex rounded-3 flex-column flex-md-row text-center text-md-start">
                        <a href="<?= htmlspecialchars($site['url']) ?>"
                           class="d-flex align-items-center site-item flex-grow-1 text-decoration-none text-center text-md-start"
                           target="_blank"
                           rel="external"
                           onclick="warnBeforeNsfw(event, '<?= htmlspecialchars($site['url']) ?>', <?= (int)$site['nsfw'] ?>, <?= (int)$site['active'] ?>)">
                            <img src="images/icon/<?= htmlspecialchars($site['icone']) ?>" class="img-fluid me-3 rounded-3" alt="Icone de <?= htmlspecialchars($site['nom']) ?>" style="width: 50px; height: 50px;">
                            <h5 class="mb-1 fs-4 flex-grow-1 text-center text-md-start"><?= htmlspecialchars($site['nom']) ?></h5>
                        </a>
                        <div class="dropdown mt-2 mt-md-0 ms-md-3 mobile-show">
                            <button class="btn" type="button" id="dropdownMenuButton-<?= htmlspecialchars($site['url']) ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?= htmlspecialchars($site['url']) ?>">
                                <li><a class="dropdown-item" href="#" data-clipboard-text="<?= htmlspecialchars($site['url']) ?>" onclick="copyToClipboard(event)">Copier</a></li>
                                <li><a class="dropdown-item" href="#" onclick="share(event, '<?= htmlspecialchars($site['url']) ?>')">Partager</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

<div class="modal fade" id="nsfwModal" tabindex="-1" aria-labelledby="nsfwModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="nsfwModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i id="warningIcon" class="fas fa-exclamation-triangle fa-2x mb-3 text-danger"></i>
                <i id="prohibitedIcon"><img src="images/logo/no-minors.png" alt="Interdiction aux mineurs" class="d-block mx-auto mb-3" style="display: none; width: 100px; height: 100px;" /></i>
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer d-flex justify-content-center" id="modalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelBtn">Annuler</button>
                <button type="button" class="btn btn-primary" id="continueBtn">Continuer</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="okBtn" style="display: none;">Ok</button>
            </div>
        </div>
    </div>
</div>

<?php require 'php/footer.php'; ?>
<?php require 'js/bootstrap_script.html'; ?>
<script type="text/javascript" src="js/public_page.js"></script>

</body>
</html>