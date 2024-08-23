<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */
global $dbConfig;
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';
require_once 'php/user_management.php';

$conn = getDbConnection($dbConfig);
$query = $conn->prepare("SELECT reseaux.nom, reseaux.url, reseaux.icone FROM reseaux JOIN users_reseaux ON reseaux.id = users_reseaux.reseau_id WHERE users_reseaux.users_id = :username ORDER BY users_reseaux.reseau_order");
$query->execute(['username' => $_SESSION['username']]);
$sites = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<main class="container my-5">
    <?php foreach ($sites as $site): ?>
        <div class="list-group mb-3">
            <div class="list-group-item d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center flex-grow-1">
                    <a href="<?= htmlspecialchars($site['url']) ?>" class="d-flex align-items-center site-item flex-grow-1 text-decoration-none text-center">
                        <img src="images/icon/<?= htmlspecialchars($site['icone']) ?>" class="img-fluid me-3" alt="Icone de <?= htmlspecialchars($site['nom']) ?>" style="width: 50px; height: 50px;">
                        <h5 class="mb-1 fs-4 flex-grow-1 text-center"><?= htmlspecialchars($site['nom']) ?></h5>
                    </a>
                </div>
                <div class="dropdown">
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
</main>

<?php require 'js/bootstrap_script.html' ?>

<script type="text/javascript">
    function copyToClipboard(e) {
        e.preventDefault();
        const text = e.target.getAttribute('data-clipboard-text');
        const textarea = document.createElement('textarea');
        textarea.textContent = text;
        textarea.style.position = 'fixed';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            console.log('Text copied to clipboard: ', text);
        } catch (error) {
            console.warn('Copy to clipboard failed: ', error);
        } finally {
            document.body.removeChild(textarea);
        }
    }

    function share(e, url) {
        e.preventDefault();
        if (navigator.share) {
            navigator.share({
                title: 'Check out this website',
                text: 'Here is a website I think you will like',
                url: url,
            }).then(() => {
                console.log('Successful share');
            }).catch((error) => {
                console.error('Error sharing:', error);
            });
        } else {
            console.warn("Your browser does not support the Web Share API");
        }
    }
</script>

<?php require 'php/footer.php'?>
</body>

</html>