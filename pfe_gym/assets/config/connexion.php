<?php

$host = "localhost";
$dbname = "salle_de_sport";
$username = "root";
$password = "";

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    

} catch (PDOException $e) {

    echo "Erreur de connexion : " . $e->getMessage();

}
?>