<?php global $username;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;
require 'vendor/autoload.php';
require 'php/api_config.php';
require 'php/public_page_function.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Link | <?php echo htmlspecialchars($username); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<!-- Section de fond flouté -->
<?php if (!empty($userInfo['profile_pic_name'])): ?>
    <div class="background-blur" style="background-image: url('/images/profile_pic/<?php echo $userInfo['profile_pic_name']; ?>');"></div>
<?php else: ?>
    <div class="background-blur" style="background-image: url('/images/default.png');"></div>
<?php endif; ?>

<?php require 'php/menu.php'; ?>

<div class="content">
    <main class="container my-5 text-white">
        <!-- Section utilisateur -->
        <div class="text-center mb-4">
            <?php if (!empty($userInfo['profile_pic_name'])): ?>
                <img src="/images/profile_pic/<?php echo $userInfo['profile_pic_name']; ?>" class="rounded-circle" alt="Photo de profil" style="width: 100px; height: 100px;">
            <?php else: ?>
                <img src="/images/default.png" class="rounded-circle" alt="Photo de profil par défaut" style="width: 100px; height: 100px;">
            <?php endif; ?>
            <h2 class="mt-3 text-black">@<?php echo htmlspecialchars($_GET['username']); ?></h2>
        </div>

        <?php if (empty($sites)): ?>
            <div class="alert alert-warning">Aucun réseau disponible pour cet utilisateur.</div>
        <?php else: ?>
            <?php foreach ($sites as $site): ?>
                <div class="list-group">
                    <div class="list-group-item align-items-start d-flex">
                        <a href="<?php echo htmlspecialchars($site['url']); ?>"
                           class="d-flex align-items-center site-item"
                           style="flex-grow: 1;"
                           target="_blank"
                           rel="external"
                           onclick="warnBeforeNsfw(event, '<?php echo htmlspecialchars($site['url']); ?>', <?php echo (int)$site['nsfw']; ?>, <?php echo (int)$site['active']; ?>)">
                            <img src="images/icon/<?php echo htmlspecialchars($site['icone']); ?>" class="img-fluid me-3" alt="Icone de <?php echo htmlspecialchars($site['nom']); ?>" style="width: 50px; height: 50px;">
                            <h5 class="mb-1 text-center fs-4 placeholder-glow" style="width: 100%"><?php echo htmlspecialchars($site['nom']); ?></h5>
                        </a>
                        <div class="dropdown">
                            <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#" data-clipboard-text="<?php echo htmlspecialchars($site['url']); ?>" onclick="copyToClipboard(event)">Copier</a></li>
                                <li><a class="dropdown-item" href="#" onclick="share(event, '<?php echo htmlspecialchars($site['url']); ?>')">Partager</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

<!-- Modal de mise en garde pour liens désactivés ou NSFW -->
<div class="modal fade" id="nsfwModal" tabindex="-1" aria-labelledby="nsfwModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="nsfwModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3 text-danger"></i>
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer justify-content-center" id="modalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="continueBtn">Continuer</button>
            </div>
        </div>
    </div>
</div>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>
<script type="text/javascript" src="js/public_page.js"></script>

</body>
</html>