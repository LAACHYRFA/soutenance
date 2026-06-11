<?php
session_start();
require "config/connexion.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$id_user = $_SESSION['user_id'];
$id_ab = $_SESSION['id_abonnement'] ?? "Aucun";

if (isset($_POST['confirmer']) && isset($_SESSION['id_abonnement'])) {
    $pdo->prepare("INSERT INTO reservation (id_utilisateur, id_ab, date_reservation) VALUES (?, ?, NOW())")
        ->execute([$id_user, $_SESSION['id_abonnement']]);
    
    unset($_SESSION['id_abonnement']);
    echo "<script>alert('Réservation enregistrée !'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Réservation</title>
    <link rel="stylesheet" href="css/reservation.css">
</head>
<body>

<div class="reservation-container">
    <h2>Récapitulatif de votre Réservation</h2>
    <ul>
        <li><strong>ID Utilisateur :</strong> # <?= $id_user ?></li>
        <li><strong>ID Abonnement :</strong> # <?= $id_ab ?></li>
        <li><strong>Date :</strong> <?= date('Y-m-d H:i:s') ?></li>
    </ul>

    <form method="POST">
        <button type="submit" name="confirmer" class="btn-submit">Confirmer la réservation</button>
    </form>

    <div class="actions">
        <a href="abonnements.php">Annuler</a> | <a href="logout.php">Déconnexion</a>
    </div>
</div>

</body>
</html>