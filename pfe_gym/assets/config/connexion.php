<?php
/**
 * CONFIGURATION DE LA CONNEXION À LA BASE DE DONNÉES
 */

// Informations de connexion
$host = "localhost";
$dbname = "salle_de_sport";
$username = "root";
$password = "";

try {
    // Initialisation de la connexion PDO avec encodage UTF-8
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configuration des attributs pour afficher les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Gestion des erreurs en cas d'échec de connexion
    echo "Erreur de connexion : " . $e->getMessage();
}
?>