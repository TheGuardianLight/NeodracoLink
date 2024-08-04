<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

use JetBrains\PhpStorm\NoReturn;

require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

// Récupération de la configuration de la base de données à partir de api_config.php
global $dbConfig;

// Obtention de la connexion à la base de données
$conn = getDbConnection($dbConfig);

if(isset($_POST['export'])){
    exportData($conn);
}

if(isset($_POST['import'])){
    // Vérifiez que le fichier a bien été chargé
    if (!isset($_FILES['file']['tmp_name'])) {
        die('Aucun fichier n a été chargé');
    }

    $filename = $_FILES['file']['tmp_name'];

    importData($filename, $conn);
}

function exportData($conn): void
{
    // Obtention des tables à exporter
    $tables = ['users', 'user_info', 'reseaux', 'users_reseaux'];

    // Préparer les données d'exportation
    $exportData = [];

    // Exportation des données de chaque table
    foreach($tables as $table) {
        $results = $conn->query("SELECT * FROM $table");
        $all_rows = $results->fetchAll(PDO::FETCH_ASSOC);
        if($all_rows) {
            $exportData[$table] = $all_rows;
        }
    }

    // Headers pour indiquer le téléchargement d'un fichier json
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="data_export.json"');

    // Écriture des données dans la sortie PHP, qui est redirigée vers le navigateur
    echo json_encode($exportData);
}

#[NoReturn] function importData($filename, $conn): void
{
    // Importe les données à partir d'un fichier JSON
    $importData = json_decode(file_get_contents($filename), true);
    foreach($importData as $table => $rows) {
        $result = $conn->query("SHOW COLUMNS FROM $table");
        if ($result === false) {
            die("Erreur lors de l'exécution de la requête SQL pour la table '$table' : " . print_r($conn->errorInfo(), true));
        }
        // Récupérer les noms des colonnes directement depuis la structure de la table
        $columns = $result->fetchAll(PDO::FETCH_COLUMN);
        foreach($rows as $row) {
            $values = array_values($row);
            $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                $table,
                implode(", ", $columns),
                implode(", ", array_fill(0, count($columns), "?"))
            );
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
        }
    }
    header("Location: ../data.php?success=1");
    exit();
}