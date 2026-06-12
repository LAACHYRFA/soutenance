<?php
/**
 * Configuration de la base de données
 */
session_start();
require "assets/config/connexion.php";

/* ==========================================================
   SAUVEGARDE DE L'ABONNEMENT SÉLECTIONNÉ
   ========================================================== */

if (isset($_GET['id_ab'])) {
    $_SESSION['id_abonnement'] = intval($_GET['id_ab']);
}

$error = "";
$success = "";

/* ==========================================================
   TRAITEMENT DU FORMULAIRE D'INSCRIPTION
   ========================================================== */

if (isset($_POST['signup'])) {

    $email = trim($_POST['email'] ?? "");
    $password = $_POST['password'] ?? "";
    $nom = trim($_POST['nom'] ?? "");

    // Champs vides
    if (empty($email) || empty($password) || empty($nom)) {
        $error = "Tous les champs sont obligatoires.";
    }

    // Email invalide
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide.";
    }

    // Password sécurisé
    elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        $error = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
    }

    else {

        // Vérifier email exist
        $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $insert = $pdo->prepare("
                INSERT INTO utilisateur (nom_completed, email, password)
                VALUES (?, ?, ?)
            ");

            if ($insert->execute([$nom, $email, $hashed_password])) {

                // Redirection vers login
                header("Location: login.php");
                exit();

            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>

<div class="container">

    <div class="custom-card">

        <h2>Inscription</h2>

        <!-- ERROR MESSAGE -->
        <?php if (!empty($error)) : ?>
            <p style="color:red; text-align:center;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" class="auth-form">

            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="nom" class="input-field" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="input-field" required>
            </div>

            <div class="form-group">
                <label>Mot de passe (8 caractères, 1 majuscule, 1 chiffre)</label>
                <input type="password" name="password" class="input-field" required>
            </div>

            <button type="submit" name="signup" class="btn-submit">
                S'inscrire
            </button>

        </form>

        <p>
            Déjà un compte ?
            <a href="login.php">Se connecter</a>
        </p>

    </div>

</div>

</body>
</html>