<?php
session_start();
require "config/connexion.php";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérification : si l'utilisateur existe et que le mot de passe correspond
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['user_name'] = $user['nom_completed'];
        header("Location: reservation.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Fitness Pro</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="container">
    <div class="custom-card">
        <h2>Connexion</h2>
        
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="input-field" required>
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