<?php
session_start();
require "config/connexion.php";

$error = "";
if (isset($_GET['id_ab'])) $_SESSION['id_abonnement'] = intval($_GET['id_ab']);

if(isset($_POST['ok'])){
    $nom = trim($_POST['nom_completed']);
    $email = trim($_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tel = trim($_POST['telephone']);
    $date = date('Y-m-d H:i:s');

    $check = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        $error = "Cet email est déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom_completed, email, password, telephone, date_inscription) VALUES(?,?,?,?,?)");
        if($stmt->execute([$nom, $email, $pass, $tel, $date])){
            $id_user = $pdo->lastInsertId();
            
            if(isset($_SESSION['id_abonnement'])) {
                $pdo->prepare("INSERT INTO inscription (id_utilisateur, id_ab, date_inscription) VALUES (?, ?, ?)")
                    ->execute([$id_user, $_SESSION['id_abonnement'], $date]);
                unset($_SESSION['id_abonnement']);
            }
            header("Location: login.php?msg=success");
            exit();
        } else {
            $error = "Erreur lors de l'inscription.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Fitness Pro</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="container">
    <div class="custom-card">
        <h2>Créer un compte</h2>
        <?php if($error) echo "<p style='color: red;'>$error</p>"; ?>

        <form method="POST" class="auth-form">
            <div class="form-group"><label>Nom complet</label><input type="text" name="nom_completed" class="input-field" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="input-field" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" class="input-field" required></div>
            <div class="form-group"><label>Téléphone</label><input type="text" name="telephone" class="input-field" required></div>
            <button type="submit" name="ok" class="btn-submit">S'inscrire</button>
        </form>
        
        <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a></p>
    </div>
</div>

</body>
</html>