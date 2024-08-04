<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';

$conn = getDbConnection($dbConfig);
$query = $conn->prepare("SELECT reseaux.nom, reseaux.url, reseaux.icone FROM reseaux JOIN users_reseaux ON reseaux.id = users_reseaux.reseau_id WHERE users_reseaux.users_id = :username");
$query->execute(['username' => $_SESSION['username']]);
$sites = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mon Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<main class="container my-5">
    <div class="row card-container">
        <?php foreach ($sites as $site): ?>
            <div class="col-md-4">
                <div class="card mb-4 cursor">
                    <img src="<?php echo $site['icone']; ?>" class="card-img-top" alt="Icone de <?php echo $site['nom']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $site['nom']; ?></h5>
                        <a href="<?php echo $site['url']; ?>" class="btn btn-primary">Visitez le site</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require 'php/footer.php'?>

</body>
</html>