<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
require "../config/connexion.php";

// Suppression
if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) { 
    $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?")->execute([intval($_GET['id'])]);
    header("Location: users.php"); exit(); 
}

// Ajout
if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom_completed, email, password, telephone, date_inscription) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['nom_complet'], $_POST['email'], $_POST['password'], $_POST['telephone'], $_POST['date_inscription']]);
    header("Location: users.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
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
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="abonnemnts.php">Abonnements</a></li>
        <li><a href="equipements.php">Équipements</a></li>
        <li><a href="coachs.php">Coachs</a></li>
        <li class="active"><a href="users.php">Utilisateur</a></li>
        <li><a href="messages.php">Messages</a></li>
    </ul>
</div>

<div class="main-content">
    <header><h2>Gestion des utilisateurs</h2></header>
    <div class="content-container">
        
        <div class="form-section">
            <h3>Ajouter un utilisateur</h3>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group"><label>Nom complet</label><input type="text" name="nom_complet" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <div class="form-group"><label>Téléphone</label><input type="text" name="telephone" required></div>
                    <div class="form-group"><label>Date d'inscription</label><input type="date" name="date_inscription" required></div>
                </div>
                <button type="submit" name="ajouter" class="btn-submit">Enregistrer</button>
            </form>
        </div>

        <div class="table-section">
            <h3>Liste des utilisateurs</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Téléphone</th><th>Date</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = $pdo->query("SELECT * FROM utilisateur ORDER BY id_utilisateur DESC")->fetchAll(PDO::FETCH_ASSOC);
                        foreach($users as $u) {
                            echo "<tr>
                                    <td>#{$u['id_utilisateur']}</td>
                                    <td>{$u['nom_completed']}</td>
                                    <td>{$u['email']}</td>
                                    <td>{$u['telephone']}</td>
                                    <td>{$u['date_inscription']}</td>
                                    <td>
                                        <a href='?action=supprimer&id={$u['id_utilisateur']}' class='btn-supprimer' onclick='return confirm(\"Supprimer cet utilisateur ?\")'>Supprimer</a>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>