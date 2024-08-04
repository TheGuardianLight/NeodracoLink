<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

function getUser($connection, $username) {
    $stmt = $connection->prepare('SELECT username,email,password FROM users WHERE username = :username OR email = :email');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

require __DIR__ . '/../vendor/autoload.php';

global $dbConfig, $config;

session_start();

$message = '';
$messageType = '';

if(isset($_POST['login'])) {
    $username = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si les champs nom d'utilisateur ou mot de passe sont vides
    if(empty($username)) {
        $message = 'Le champ Email est vide';
        $messageType = 'warning';
    } elseif(empty($password)) {
        $message = 'Le champ Mot de passe est vide';
        $messageType = 'warning';
    } else {
        $connection = getDbConnection($dbConfig);
        $user = getUser($connection, $username);

        // Vérifier si l'utilisateur existe dans la base de données
        if(!$user) {
            $message = "Erreur : L'utilisateur n'existe pas";
            $messageType = 'danger';
        } elseif(!password_verify($password, $user['password'])) {
            $message = "Erreur : Le mot de passe est incorrect";
            $messageType = 'danger';
        } else {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            header("Location: index.php");
            exit();
        }
    }
}