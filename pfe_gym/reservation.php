<?php
/* ==========================================================
   DÉMARRAGE DE LA SESSION ET CONNEXION À LA BASE DE DONNÉES
   ========================================================== */

session_start();
require "assets/config/connexion.php";

/* ==========================================================
   VÉRIFICATION DE LA CONNEXION DE L'UTILISATEUR
   ========================================================== */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* ==========================================================
   RÉCUPÉRATION DES INFORMATIONS DE LA SESSION
   ========================================================== */

$id_user = $_SESSION['user_id'];
$id_ab = filter_var($_SESSION['id_abonnement'] ?? 0, FILTER_VALIDATE_INT);

$error = "";

/* ==========================================================
   TRAITEMENT DE LA CONFIRMATION DE RÉSERVATION
   ========================================================== */

if (isset($_POST['confirmer'])) {

    /* Vérification de la validité de l'abonnement */
    if ($id_ab !== false && $id_ab > 0) {

        try {

            /* Insertion de la réservation dans la base de données */
            $stmt = $pdo->prepare("
                INSERT INTO reservation
                (id_utilisateur, id_ab, date_reservation)
                VALUES (?, ?, NOW())
            ");

            $stmt->execute([$id_user, $id_ab]);

            /* Suppression de l'abonnement de la session */
            unset($_SESSION['id_abonnement']);

            /* Message de confirmation et redirection */
            echo "<script>
                    alert('Réservation enregistrée avec succès !');
                    window.location.href='index.php';
                  </script>";
            exit();

        } catch (PDOException $e) {

            $error = "Erreur lors de l'enregistrement de la réservation.";

        }

    } else {

        $error = "Abonnement invalide.";

    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Confirmation de Réservation - Fitness Pro</title>

    <link rel="stylesheet" href="assets/css/reservation.css">

</head>

<body>

<div class="reservation-container">

    <!-- Titre principal -->
    <h2>Récapitulatif de votre Réservation</h2>

    <!-- Affichage du message d'erreur -->
    <?php
    if (!empty($error)) {
        echo "<p style='color:red; text-align:center;'>"
             . htmlspecialchars($error) .
             "</p>";
    }
    ?>

    <!-- Informations de la réservation -->
    <ul>

        <li>
            <strong>ID Utilisateur :</strong>
            # <?= htmlspecialchars($id_user) ?>
        </li>

        <li>
            <strong>ID Abonnement :</strong>
            # <?= ($id_ab !== false) ? htmlspecialchars($id_ab) : "Non valide"; ?>
        </li>

        <li>
            <strong>Date :</strong>
            <?= date("Y-m-d H:i:s") ?>
        </li>

    </ul>

    <!-- Formulaire de confirmation -->
    <form method="POST">

        <button
            type="submit"
            name="confirmer"
            class="btn-submit">

            Confirmer la réservation

        </button>

    </form>

    <!-- Liens des actions -->
    <div class="actions">

        <a href="index.php" class="link-annuler">
            Annuler
        </a>

        |

        <a href="logout.php" class="link-logout">
            Déconnexion
        </a>

    </div>

</div>

</body>
</html>