<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig, $formFields;
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';
require_once 'php/user_management.php';

$userInfo = getUserInfo($dbConfig, $_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mon compte</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="styles.css"/>
    <link rel="stylesheet" href="css/croppie.css"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container my-3">
    <!-- Formulaire pour les informations personnelles -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="bi bi-person-circle me-2"></i>Informations personnelles</h3>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">
                Information : votre profil public sera accessible ici :
                <a href="https://link.neodraco.fr/<?= htmlspecialchars($_SESSION['username']) ?>" hreflang="fr" target="_blank" rel="noopener noreferrer">
                    https://link.neodraco.fr/<?= htmlspecialchars($_SESSION['username']) ?>
                </a>
            </p>
            <form method="post" class="row g-3">
                <input type="hidden" name="update_info" value="1">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userInfo['username']) ?>" disabled>
                    </div>
                </div>
                <?php foreach ($formFields as $fieldName => $fieldData) : ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="<?= htmlspecialchars($fieldName) ?>" class="form-label"><?= htmlspecialchars($fieldData[0]); ?></label>
                            <input type="<?= htmlspecialchars($fieldData[1]) ?>" class="form-control" id="<?= htmlspecialchars($fieldName) ?>" name="<?= htmlspecialchars($fieldName) ?>" value="<?= htmlspecialchars($userInfo[$fieldName]) ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Mettre à jour les informations</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulaire pour changer le mot de passe -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="bi bi-lock me-2"></i>Changer le mot de passe</h3>
        </div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="update_password" value="1">
                <div class="col-md-6">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Mettre à jour le mot de passe</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulaire pour changer l'image de profil -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="bi bi-image me-2"></i>Changer l'image de profil</h3>
        </div>
        <div class="card-body">
            <form id="uploadForm" method="post" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="update_profile_pic" value="1">
                <input type="hidden" id="profile_pic_cropped" name="profile_pic_cropped">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <?php if (!empty($userInfo['profile_pic_name'])): ?>
                                    <img src="/images/profile_pic/<?= htmlspecialchars($userInfo['profile_pic_name']) ?>" class="img-fluid rounded-start" alt="Photo de profil">
                                <?php else: ?>
                                    <img src="/images/default.png" class="img-fluid rounded-start" alt="Photo de profil par défaut">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Photo de profil</h5>
                                    <label for="profile_pic" class="form-label">Choisir une nouvelle image</label>
                                    <p class="text-muted">Veuillez sélectionner une image au format carré.</p>
                                    <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept=".png, .jpg, .jpeg, .webp, .svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-primary" id="uploadBtn"><i class="bi bi-save me-2"></i>Choisir et Recadrer l'image de profil</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="cropImagePop">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="upload-demo" src="">
                </div>
                <div class="modal-footer">
                    <button id="cropImageBtn">Recadrer et Télécharger</button>
                </div>
            </div>
        </div>
    </div>
    <input type="file" id="profile_pic">
    <input type="hidden" id="profile_pic_cropped">
    <button id="uploadBtn">Télécharger</button>
    <form id="uploadForm" method="post" action="your_upload_endpoint">
        <input type="hidden" name="cropped_image" id="cropped_image">
    </form>
</div>

<!-- Inclure les scripts JS en fin de document pour un chargement plus rapide -->
<script src="js/jquery-3.7.1.js"></script>
<script src="js/croppie.js" defer></script>

<script defer>
    document.addEventListener('DOMContentLoaded', function () {
        var cropper;
        var originalHeight;

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById('upload-demo');
                    img.src = e.target.result;
                    img.onload = function () {
                        // Stocker la hauteur originale de l'image
                        originalHeight = img.naturalHeight;

                        // Initialiser Cropper.js
                        cropper = new Cropper(img, {
                            aspectRatio: 1, // Carré
                            viewMode: 1, // Limite au conteneur
                            autoCropArea: 1, // Recadrage automatique initial de 100%
                            responsive: true,
                            zoomable: true,
                            movable: true,
                            minCropBoxHeight: 200,
                            minCropBoxWidth: 200
                        });

                        // Ajuster le dézoom maximum selon la hauteur originale
                        cropper.zoomTo(200 / originalHeight);
                    };
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Gestion de l'événement de changement de fichier
        $('#profile_pic').on('change', function () {
            $('#cropImagePop').modal('show');
            readFile(this);
        });

        // Gestion de l'événement de recadrage et de sauvegarde de l'image
        $('#cropImageBtn').on('click', function () {
            var canvas = cropper.getCroppedCanvas({
                height: originalHeight // Taille en hauteur de l'image originale
            });
            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('cropped_image', blob);

                // Mettre à jour le champ caché pour le formulaire
                $('#cropped_image').val(blob);

                $('#cropImagePop').modal('hide');
                $('#uploadForm').submit();
            });
        });

        // Gestion de l'événement de clic sur le bouton de téléchargement
        $('#uploadBtn').on('click', function () {
            $('#profile_pic').click();
        });
    });
</script>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>