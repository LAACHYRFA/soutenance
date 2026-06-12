<?php
// Démarrage de la session
session_start();

// Vérification de l'accès administrateur
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
require "../assets/config/connexion.php";
// Variable de gestion des erreurs
$error = "";

/* =========================
   SUPPRESSION UTILISATEUR
   ========================= */
if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) {

    // Suppression de l'utilisateur sélectionné
    $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?")
        ->execute([intval($_GET['id'])]);

    header("Location: users.php");
    exit();
}

/* =========================
   AJOUT UTILISATEUR
   ========================= */
if (isset($_POST['ajouter'])) {

    // Récupération des données
    $nom = trim($_POST['nom_complet']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $tel = trim($_POST['telephone']);
    $date = $_POST['date_inscription'];

    // Validation des champs obligatoires
    if (empty($nom) || empty($email) || empty($password) || empty($tel) || empty($date)) {
        $error = "Veuillez remplir tous les champs.";
    }
    // Validation email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    }
    // Validation téléphone (chiffres uniquement)
    elseif (!preg_match('/^[0-9]+$/', $tel)) {
        $error = "Téléphone invalide.";
    }

    // Si aucune erreur, insertion en base de données
    if (empty($error)) {

        $stmt = $pdo->prepare("
            INSERT INTO utilisateur
            (nom_completed, email, password, telephone, date_inscription)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $nom,
            $email,
            $password,
            $tel,
            $date
        ]);

        header("Location: users.php");
        exit();
    }
}

/* =========================
   RÉCUPÉRATION UTILISATEURS
   ========================= */
$users = $pdo->query("
    SELECT *
    FROM utilisateur
    ORDER BY id_utilisateur DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <!-- Titre de la page -->
    <title>Gestion des Utilisateurs</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/forms_tables.css">
</head>

<body>

<!-- Barre latérale -->
<div class="sidebar">

    <!-- Logo -->
    <div class="logo-container">
        <img src="../assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
        <div class="sidebar-logo">FITNESS PRO</div>
    </div>

    <!-- Menu -->
    <ul class="sidebar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="abonnemnts.php">Abonnements</a></li>
        <li><a href="equipements.php">Équipements</a></li>
        <li><a href="coachs.php">Coachs</a></li>
        <li class="active"><a href="users.php">Utilisateur</a></li>
    </ul>

</div>

<!-- Contenu principal -->
<div class="main-content">

    <!-- Titre -->
    <header>
        <h2>Gestion des utilisateurs</h2>
    </header>

    <div class="content-container">

        <!-- Formulaire ajout utilisateur -->
        <div class="form-section">

            <h3>Ajouter un utilisateur</h3>

            <!-- Affichage erreur -->
            <?php if (!empty($error)): ?>
                <p style="color:red; margin-bottom:10px;">
                    <?= $error ?>
                </p>
            <?php endif; ?>

            <form method="POST">

                <div class="form-grid">

                    <!-- Nom complet -->
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="nom_complet" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" required>
                    </div>

                    <!-- Date inscription -->
                    <div class="form-group">
                        <label>Date d'inscription</label>
                        <input type="date" name="date_inscription" required>
                    </div>

                </div>

                <!-- Bouton enregistrer -->
                <button type="submit" name="ajouter" class="btn-submit">
                    Enregistrer
                </button>

            </form>

        </div>

        <!-- Tableau utilisateurs -->
        <div class="table-section">

            <h3>Liste des utilisateurs</h3>

            <div class="table-container">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($users as $u): ?>

                        <tr>

                            <!-- ID -->
                            <td>#<?= $u['id_utilisateur'] ?></td>

                            <!-- Nom -->
                            <td><?= $u['nom_completed'] ?></td>

                            <!-- Email -->
                            <td><?= $u['email'] ?></td>

                            <!-- Téléphone -->
                            <td><?= $u['telephone'] ?></td>

                            <!-- Date inscription -->
                            <td><?= $u['date_inscription'] ?></td>

                            <!-- Actions -->
                            <td>
                                <a href="?action=supprimer&id=<?= $u['id_utilisateur'] ?>"
                                   class="btn-supprimer"
                                   onclick="return confirm('Supprimer cet utilisateur ?')">
                                    Supprimer
                                </a>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>