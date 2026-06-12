<?php
// Démarrage de la session
session_start();

// Suppression de toutes les variables de session
session_unset();

// Destruction complète de la session
session_destroy();

// Redirection de l'utilisateur vers la page d'accueil
header("Location: index.php");

// Arrêt de l'exécution du script
exit();
?>