<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

session_start();
session_destroy();
header('Location: ../login.php'); // vous pouvez le rediriger vers la page que vous voulez
exit;