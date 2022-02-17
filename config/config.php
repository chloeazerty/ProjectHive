<?php

require '../env.local.php';

// Utilisation d'un bloc try-catch pour capturer les erreurs de connexion
try {
    // Connexion à la BDD via PDO
    $connexion = new PDO(DB_HOST, DB_USER, DB_PASS);
    // Définition du mode d'erreur sur Exception
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $error) {
    echo "Erreur : " . $error->getMessage();
}

if(isset($_GET['logout'])) {
    session_destroy();
    header('location:index.php');
}
