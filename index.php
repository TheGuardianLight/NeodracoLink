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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<main class="container my-5">
    <?php foreach ($sites as $site): ?>
        <div class="list-group">
            <div class="list-group-item align-items-start d-flex">
                <a href="<?php echo $site['url']; ?>" class="d-flex align-items-center" style="flex-grow: 1;">
                    <img src="images/icon/<?php echo $site['icone']; ?>" class="img-fluid me-3" alt="Icone de <?php echo $site['nom']; ?>" style="width: 50px; height: 50px;">
                    <h5 class="mb-1 text-center fs-4 placeholder-glow" style="width: 100%"><?php echo $site['nom']; ?></h5>
                </a>
                <div class="dropdown">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-lg"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#" data-clipboard-text="<?php echo $site['url']; ?>" onclick="copyToClipboard(event)">Copier</a></li>
                        <li><a class="dropdown-item" href="#" onclick="share(event, '<?php echo $site['url']; ?>')">Partager</a></li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

<script type="text/javascript">

    function copyToClipboard(e) {
        var text = e.target.getAttribute('data-clipboard-text');
        var textarea = document.createElement('textarea');
        textarea.textContent = text;
        textarea.style.position = 'fixed';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            return document.execCommand('copy');
        } catch (ex) {
            console.warn('Copy to clipboard failed.', ex);
            return false;
        } finally {
            document.body.removeChild(textarea);
        }
    }

    function share(e, url) {
        if (navigator.share) {
            navigator.share({
                title: 'Check out this website',
                text: 'Here is a website I think you will like',
                url: url,
            })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
        } else {
            console.log("Your browser does not support the Web Share API");
        }
        e.preventDefault();
    }
</script>

</body>
</html>