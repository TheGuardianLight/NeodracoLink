<?php require 'php/profile_image.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            Neodraco's Link
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown align-self-center">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="modify.php">Sites & Réseaux</a></li>
                        <li><a class="dropdown-item" href="data.php">Export/Import data</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown align-self-center">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Compte
                        <?php if (!empty($userInfo['profile_pic_name'])): ?>
                            <img src="/images/profile_pic/<?= $userInfo['profile_pic_name'] ?>" class="rounded-circle" width="30" alt="Photo de profil" style="margin-left: 5px;">
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                        <li>
                            <a class="dropdown-item" href="account.php">Mon profil</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="php/logout.php">Déconnexion</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>