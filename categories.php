<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig, $username;
require 'vendor/autoload.php';
require_once 'php/user_management.php';
require 'php/manage_cat.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch categories
$categories = fetchCategories();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Link | Catégories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="styles.css">
    <?php require 'php/favicon.php'; ?>
</head>
<body>

<?php require 'php/menu.php'; ?>

<?php
// Vérifiez s'il y a un message d'erreur dans la session
$error_message = "";
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    // Supprimez le message d'erreur après l'avoir stocké
    unset($_SESSION['error_message']);
}
?>

<!-- Modal Bootstrap -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Erreur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.7.1.js"></script>

<script>
    $(document).ready(function() {
        var errorMsg = <?php echo json_encode($error_message); ?>;
        if (errorMsg !== "") {
            $('#errorModal').modal('show'); // Affiche le modal avec le message d'erreur
        }

        // Assurer que le bouton "Fermer" fonctionne
        $('.close, .btn-secondary').click(function() {
            $('#errorModal').modal('hide');
        });
    });
</script>

<div class="container mt-5">
    <h2>Gérer les catégories</h2>

    <!-- Add Category Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Ajouter une nouvelle catégorie</h5>
        </div>
        <div class="card-body">
            <form action="php/manage_cat.php" method="POST">
                <div class="mb-3">
                    <label for="cat_name" class="form-label">Nom de la catégorie</label>
                    <input type="text" class="form-control" id="cat_name" name="cat_name" placeholder="Entrer le nom de la catégorie" required>
                    <div class="form-text">Choisissez un nom descriptif et unique pour la nouvelle catégorie.</div>
                </div>
                <button class="btn btn-primary" type="submit" name="add_category">
                    <i class="fas fa-plus-circle"></i> Ajouter
                </button>
            </form>
        </div>
    </div>

    <!-- Categories List -->
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><?= htmlspecialchars($category['cat_name']) ?></h5>
                        <!-- Edit Category Form -->
                        <form action="php/manage_cat.php" method="POST">
                            <input type="hidden" name="cat_id" value="<?= $category['cat_id'] ?>">
                            <div class="input-group mb-3">
                                <label for="new_cat_name_<?= $category['cat_id'] ?>" class="input-group-text">Nouveau nom</label>
                                <input type="text" class="form-control" id="new_cat_name_<?= $category['cat_id'] ?>" name="new_cat_name" placeholder="Nouveau nom" required>
                            </div>
                            <button class="btn btn-secondary w-100" type="submit" name="edit_category">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                        </form>
                        <hr>
                        <!-- Delete Category Button -->
                        <form action="php/manage_cat.php" method="POST">
                            <input type="hidden" name="cat_id" value="<?= htmlspecialchars($category['cat_id']) ?>">
                            <button type="submit" class="btn btn-danger w-100" name="delete_category">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'php/footer.php'; ?>
<?php require 'js/bootstrap_script.html'; ?>

</body>
</html>