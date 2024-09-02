<?php

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

function fetchCategories() {
    global $dbConfig;
    try {
        // Utilisation de la fonction getDbConnection pour établir la connexion
        $pdo = getDbConnection($dbConfig);
        // Définition de l'attribut pour lancer des exceptions en cas d'erreur de requête
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT cat_id, cat_name FROM categorie");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Enregistrement de l'erreur (plutôt que de mourir)
        error_log("Error fetching categories: " . $e->getMessage());
        return [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        addCategory($_POST['cat_name']);
    } elseif (isset($_POST['edit_category'])) {
        editCategory($_POST['cat_id'], $_POST['new_cat_name']);
    } elseif (isset($_POST['delete_category'])) {
        deleteCategory($_POST['cat_id']);
    }

    header("Location: ../categories.php");
    exit;
}

function addCategory($cat_name) {
    global $dbConfig;
    try {
        $pdo = getDbConnection($dbConfig);
        $stmt = $pdo->prepare("INSERT INTO categorie (cat_name) VALUES (:cat_name)");
        $stmt->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error adding category: " . $e->getMessage());
    }
}

function editCategory($cat_id, $new_cat_name) {
    global $dbConfig;
    try {
        $pdo = getDbConnection($dbConfig);
        $stmt = $pdo->prepare("UPDATE categorie SET cat_name = :new_cat_name WHERE cat_id = :cat_id");
        $stmt->bindParam(':new_cat_name', $new_cat_name, PDO::PARAM_STR);
        $stmt->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error editing category: " . $e->getMessage());
    }
}

function deleteCategory($cat_id) {
    global $dbConfig;
    session_start(); // Démarrage de la session
    try {
        $pdo = getDbConnection($dbConfig);
        $stmt = $pdo->prepare("DELETE FROM categorie WHERE cat_id = :cat_id");
        $stmt->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        // Vérifiez si l'erreur est une violation de contrainte d'intégrité
        if ($e->getCode() == 23000 && strpos($e->getMessage(), '1451 Cannot delete or update a parent row') !== false) {
            // Stockez le message d'erreur dans une session pour pouvoir l'afficher après la redirection
            $_SESSION['error_message'] = "Impossible de supprimer la catégorie. Veuillez assigner les réseaux de cette catégorie à une autre catégorie existante.";
        } else {
            die("Error deleting category: " . $e->getMessage());
        }
    }

    header("Location: ../categories.php");
    exit();
}