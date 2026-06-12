<?php
/**
 * 1. CONFIGURATION
 */
session_start();
require "assets/config/connexion.php";

$error = "";

/**
 * 2. TRAITEMENT DU FORMULAIRE ET VALIDATION
 */
if (isset($_POST['login'])) {
    // Nettoyage des données
    $email = trim($_POST['email'] ?? "");
    $password = $_POST['password'] ?? "";

    // Validation : Vérifier si les champs sont vides
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } 
    // Validation : Vérifier le format de l'email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide.";
    } 
    else {
        // Recherche de l'utilisateur par email avec requête préparée
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['user_name'] = $user['nom_completed'];
            header("Location: reservation.php");
            exit();
        } else {
            // Message générique pour la sécurité (ne pas préciser si c'est l'email ou le mdp)
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Fitness Pro</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="container">
    <div class="custom-card">
        <h2>Connexion</h2>
        
        <?php if(!empty($error)) echo "<p style='color:red; text-align:center;'>".htmlspecialchars($error)."</p>"; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="input-field" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" class="input-field" required>
            </div>
            <button type="submit" name="login" class="btn-submit">Se connecter</button>
        </form>
        
        <p>Pas encore de compte ? <a href="signup.php">S'inscrire</a></p>
    </div>
</div>

</body>
</html>