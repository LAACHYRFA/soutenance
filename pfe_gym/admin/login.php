<?php
/**
 * ==========================================
 * PAGE DE CONNEXION ADMIN
 * ==========================================
 * - Authentification simple de l'administrateur
 * - Utilise des sessions PHP
 */

session_start();

/**
 * Si l'admin est déjà connecté,
 * on le redirige vers le dashboard
 */
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

// Variable pour afficher les erreurs
$error = "";

/**
 * Traitement du formulaire de connexion
 */
if (isset($_POST['connexion'])) {

    // Récupération et nettoyage des données
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";

    /**
     * Validation des champs
     */
    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {

        /**
         * Authentification (version simple)
         * ⚠️ À améliorer avec base de données + password_hash en production
         */
        if ($username === "laachyr farah" && $password === "farah123") {

            // Création de la session admin
            $_SESSION['admin'] = $username;

            // Redirection vers dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Identifiants incorrects.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin - Fitness Pro</title>

    <!-- CSS GLOBAL (réutilisation de ton design) -->
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>

<!-- =========================
     CONTAINER LOGIN
========================= -->
<div class="container">

    <!-- CARTE LOGIN -->
    <div class="custom-card">

        <h2>Connexion Admin</h2>

        <!-- AFFICHAGE ERREUR -->
        <?php if (!empty($error)): ?>
            <p style="color:red; margin-top:10px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <!-- FORMULAIRE -->
        <form method="POST" class="auth-form">

            <!-- Champ username -->
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text"
                       name="username"
                       class="input-field"
                       placeholder="Entrer votre nom">
            </div>

            <!-- Champ password -->
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password"
                       name="password"
                       class="input-field"
                       placeholder="••••••••">
            </div>

            <!-- Bouton connexion -->
            <button type="submit"
                    name="connexion"
                    class="btn-submit">
                Se connecter
            </button>

        </form>

    </div>

</div>

</body>
</html>