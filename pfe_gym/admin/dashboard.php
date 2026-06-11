<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
require "../assets/config/connexion.php";

// Récupération des totaux
$forfaits  = $pdo->query("SELECT COUNT(*) FROM abonnement")->fetchColumn();
$coachs    = $pdo->query("SELECT COUNT(*) FROM coach")->fetchColumn();
$users     = $pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
$equips    = $pdo->query("SELECT COUNT(*) FROM equipement")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Pro - Dashboard</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/forms_tables.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
            <img src="../assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
            <div class="sidebar-logo">FITNESS PRO</div>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="dashboard.php">Dashboard</a></li>
            <li><a href="abonnemnts.php">Abonnements</a></li>
            <li><a href="equipements.php">Équipements</a></li>
            <li><a href="coachs.php">Coachs</a></li>
            <li><a href="users.php">Utilisateur</a></li>
            <li><a href="messages.php">Messages</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2 class="dashboard-title">Dashboard</h2>
        </header>
        <div class="content-container">
            <div class="dashboard-cards">
                <?php 
                    echo "<div class='card'><div class='card-icon'>💳</div><h1>$forfaits</h1><p>Forfaits</p></div>";
                    echo "<div class='card'><div class='card-icon'>👤</div><h1>$coachs</h1><p>Coachs</p></div>";
                    echo "<div class='card'><div class='card-icon'>👥</div><h1>$users</h1><p>Utilisateurs</p></div>";
                    echo "<div class='card'><div class='card-icon'>🏋️</div><h1>$equips</h1><p>Équipements</p></div>";
                ?>
            </div>
        </div>
    </div>
</body>
</html>