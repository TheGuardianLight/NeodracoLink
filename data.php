<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php
global $dbConfig;
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';

$conn = getDbConnection($dbConfig);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Gestion des données</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php'; ?>
</head>
<body>

<?php require 'php/menu.php'; ?>

<?php
if (isset($_GET['success']) && $_GET['success'] === '1') {
    echo '<div class="alert alert-success" role="alert">';
    echo 'L\'importation des données a réussi!';
    echo '</div>';
}
?>

<div class="container">
    <!-- Tabs -->
    <ul class="nav nav-pills my-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="export-tab" data-bs-toggle="pill" data-bs-target="#export"
                    type="button" role="tab" aria-controls="export">Exporter les données
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="import-tab" data-bs-toggle="pill" data-bs-target="#import" type="button"
                    role="tab" aria-controls="import">Importer les données
            </button>
        </li>
    </ul>

    <!-- Tabs content -->
    <div class="tab-content" id="adminTabsContent">
        <!-- Export tab -->
        <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="export-tab">
            <div class="card my-4">
                <div class="card-body">
                    <form action="php/manager.php" method="post">
                        <button type="submit" name="export" class="btn btn-primary">Exporter les données</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Import tab -->
        <div class="tab-pane fade" id="import" role="tabpanel" aria-labelledby="import-tab">
            <div class="card my-4">
                <div class="card-body">
                    <form action="php/manager.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input class="form-control" type="file" name="file">
                        </div>
                        <button type="submit" name="import" class="btn btn-primary">Importer les données</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn = null; // fermeture de la connexion à la base de données
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    // tab switcher function
    function switchTab() {
        const tabs = Array.from(document.querySelectorAll('#adminTabs .nav-link'));
        const activeTabName = localStorage.getItem('activeTab');
        const activeTab = tabs.find(tab => tab.id === activeTabName);
        if (activeTab) {
            const tab = new bootstrap.Tab(activeTab);
            tab.show();
        }
    }

    // tab click listener
    const adminTabsEl = document.querySelector('#adminTabs')
    adminTabsEl.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('nav-link')) {
            localStorage.setItem('activeTab', target.id);
        }
    });

    // call on page load
    document.addEventListener("DOMContentLoaded", function () {
        switchTab();
    });
</script>

<?php require 'php/footer.php';?>

</body>
</html>