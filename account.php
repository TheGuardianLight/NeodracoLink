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
    <link rel="stylesheet" href="css/cropper.css"/>
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
                <input type="hidden" id="profile_pic_cropped" name="cropped_image"> <!-- Mise à jour du champ -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= !empty($userInfo['profile_pic_name']) ? "/images/profile_pic/" . htmlspecialchars($userInfo['profile_pic_name']) : '/images/default.png' ?>" class="img-fluid rounded-start" alt="Photo de profil">
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
            </form>
        </div>
    </div>

    <!-- Modal pour le recadrage de l'image -->
    <div class="modal fade" id="cropImagePop" tabindex="-1" aria-labelledby="cropImagePopLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="upload-demo" class="img-fluid" src="" alt="Image à recadrer">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="cropImageBtn">Recadrer et Télécharger</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclure les scripts JS en fin de document pour un chargement plus rapide -->
<script src="js/jquery-3.7.1.js"></script>
<script src="js/cropper.js" defer></script>

<script defer>
    document.addEventListener('DOMContentLoaded', function () {
        var cropper;
        const fixedWidth = 400;  // Largeur fixe pour l'affichage de l'image
        const fixedHeight = 400; // Hauteur fixe pour l'affichage de l'image

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById('upload-demo');
                    img.src = e.target.result;
                    img.onload = function () {
                        // Redimensionner l'image pour qu'elle tienne dans les dimensions fixes, tout en conservant les proportions
                        const naturalWidth = img.naturalWidth;
                        const naturalHeight = img.naturalHeight;

                        if (naturalWidth / naturalHeight > fixedWidth / fixedHeight) {
                            img.width = fixedWidth;
                            img.height = (naturalHeight / naturalWidth) * fixedWidth;
                        } else {
                            img.width = (naturalWidth / naturalHeight) * fixedHeight;
                            img.height = fixedHeight;
                        }

                        // Initialiser Cropper.js
                        cropper = new Cropper(img, {
                            aspectRatio: 1, // Carré
                            viewMode: 2, // Restreindre le recadrage à l'intérieur du conteneur
                            autoCropArea: 1, // Recadrage automatique initial de 100%
                            responsive: true,
                            zoomable: true, // Autoriser le zoom
                            background: false, // Enlever l'arrière-plan gris
                            movable: true,
                            rotatable: true, // Permettre la rotation
                            scalable: true, // Permettre le dimensionnement
                        });
                    };
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Gestion de l'événement de changement de fichier
        document.getElementById('profile_pic').addEventListener('change', function () {
            var modal = new bootstrap.Modal(document.getElementById('cropImagePop'), {
                keyboard: false
            });
            modal.show();
            readFile(this);
        });

        // Gestion de l'événement de recadrage et de sauvegarde de l'image
        document.getElementById('cropImageBtn').addEventListener('click', function () {
            var canvas = cropper.getCroppedCanvas({
                width: fixedWidth,
                height: fixedHeight,
            });
            canvas.toBlob(function (blob) {
                // Convertir le blob en base64 pour l'envoyer via le formulaire
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function () {
                    var base64Data = reader.result;
                    document.getElementById('profile_pic_cropped').value = base64Data;

                    // Fermer le modal et soumettre le formulaire
                    bootstrap.Modal.getInstance(document.getElementById('cropImagePop')).hide();
                    document.getElementById('uploadForm').submit();
                };
            });
        });

        // Gestion de l'événement de clic sur le bouton de téléchargement
        document.getElementById('uploadBtn').addEventListener('click', function () {
            document.getElementById('profile_pic').click();
        });
    });
</script>

<?php require 'php/footer.php'?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>