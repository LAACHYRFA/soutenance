<?php
/**
 * Page de connexion de l'administration
 * Gère l'authentification sécurisée des administrateurs
 */

session_start();

// Vérification : si l'admin est déjà connecté, on le redirige vers le dashboard
if (isset($_SESSION['admin'])) { 
    header("Location: dashboard.php"); 
    exit(); 
}

// Initialisation de la variable de message d'erreur
$erreur = ""; 

// Traitement de la soumission du formulaire
if (isset($_POST['connexion'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validation des champs : vérification de la saisie
    if (empty($username)) {
        $erreur = "Le nom d'utilisateur est requis.";
    } elseif (empty($password)) {
        $erreur = "Le mot de passe est requis.";
    } else {
        // Authentification : comparaison des identifiants
        if ($username === "admin" && $password === "admin123") {
            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit();
        } else { 
            // Erreur générique pour des raisons de sécurité
            $erreur = "Identifiants incorrects !";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Fitness Pro</title>

      <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/forms_tables.css">
</head>
</head>
<body class="login-body"> 
    
    <div class="login-container">
        <div class="login-card">
            <div class="logo-area"><h2>FITNESS PRO</h2></div>
            
            <?php
            // Affichage du message d'erreur via echo si la variable n'est pas vide
            if (!empty($erreur)) {
                echo '<div class="error-message"><p>' . $erreur . '</p></div>';
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Identifiant</label>
                    <input type="text" name="username" placeholder="Nom d'utilisateur">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••">
                </div>
                <button type="submit" name="connexion" class="btn-submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>