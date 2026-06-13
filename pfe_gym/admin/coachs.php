<?php
// Démarrage de la session et vérification de l'accès administrateur
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
require "../assets/config/connexion.php";

// Variable de gestion des erreurs
$error = "";

// Suppression d'un coach
if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) {

    $pdo->prepare("DELETE FROM coach WHERE id_coach = ?")
        ->execute([$_GET['id']]);

    header("Location: coachs.php");
    exit();
}

// Ajout ou modification d'un coach
if (isset($_POST['save_coach'])) {

    // Récupération des données du formulaire
    $id = $_POST['coach_id'] ?? null;
    $nom = trim($_POST['nom']);
    $spec = trim($_POST['spec']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['telephone']);

    // Validation des champs
    if (empty($nom) || empty($spec) || empty($email) || empty($tel)) {
        $error = "Veuillez remplir tous les champs.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    }
    elseif (!preg_match('/^[0-9]+$/', $tel)) {
        $error = "Le téléphone doit contenir uniquement des chiffres.";
    }

    // Gestion de l'image
    $img = $_FILES['img']['name']
        ? $_FILES['img']['name']
        : ($_POST['old_img'] ?? 'default.jpg');

    if ($_FILES['img']['name']) {
        move_uploaded_file(
            $_FILES['img']['tmp_name'],
            "../images/" . $img
        );
    }

    // Enregistrement des données si aucune erreur
    if (empty($error)) {

        if ($id) {

            // Modification
            $pdo->prepare("
                UPDATE coach
                SET nom_complet=?, image=?, specialite=?, email=?, telephone=?
                WHERE id_coach=?
            ")->execute([
                $nom,
                $img,
                $spec,
                $email,
                $tel,
                $id
            ]);

        } else {

            // Ajout
            $pdo->prepare("
                INSERT INTO coach
                (nom_complet, image, specialite, email, telephone)
                VALUES (?,?,?,?,?)
            ")->execute([
                $nom,
                $img,
                $spec,
                $email,
                $tel
            ]);
        }

        header("Location: coachs.php");
        exit();
    }
}

// Chargement des données pour modification
$editData = null;

if (isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id'])) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM coach
        WHERE id_coach = ?
    ");

    $stmt->execute([$_GET['id']]);

    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de la liste des coachs
$coachs = $pdo->query("
    SELECT *
    FROM coach
    ORDER BY id_coach DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fitness Pro - Gestion Coachs</title>

    <!-- Fichiers CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/forms_tables.css">
</head>
<body>

<!-- Barre latérale -->
<div class="sidebar">

    <!-- Logo du site -->
    <div class="logo-container">
        <img src="../assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
        <div class="sidebar-logo">FITNESS PRO</div>
    </div>

    <!-- Menu de navigation -->
    <ul class="sidebar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="abonnemnts.php">Abonnements</a></li>
        <li><a href="equipements.php">Équipements</a></li>
        <li class="active"><a href="coachs.php">Coachs</a></li>
        <li><a href="users.php">Utilisateur</a></li>
    </ul>

</div>

<!-- Contenu principal -->
<div class="main-content">

    <!-- En-tête de la page -->
    <header>
        <h2>Gestion des Coachs</h2>
    </header>

    <!-- Conteneur principal -->
    <div class="content-container">

        <!-- Section formulaire -->
        <div class="form-section">

            <!-- Titre dynamique (ajout / modification) -->
            <h3>
                <?= $editData ? "Modifier le coach" : "Ajouter un Coach" ?>
            </h3>

            <!-- Formulaire d'ajout / modification -->
            <form method="POST" enctype="multipart/form-data">

                <!-- ID caché (mode modification) -->
                <input type="hidden" name="coach_id" value="<?= $editData['id_coach'] ?? '' ?>">

                <!-- Ancienne image -->
                <input type="hidden" name="old_img" value="<?= $editData['image'] ?? '' ?>">

                <!-- Champs du formulaire -->
                <div class="form-grid">

                    <!-- Nom complet -->
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="nom" value="<?= $editData['nom_complet'] ?? '' ?>" required>
                    </div>

                    <!-- Spécialité -->
                    <div class="form-group">
                        <label>Spécialité</label>
                        <input type="text" name="spec" value="<?= $editData['specialite'] ?? '' ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= $editData['email'] ?? '' ?>" required>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" value="<?= $editData['telephone'] ?? '' ?>" required>
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="img">
                    </div>

                </div>

                <!-- Bouton enregistrer -->
                <button type="submit" name="save_coach" class="btn-submit">
                    Enregistrer
                </button>

                <!-- Bouton annuler en mode modification -->
                <?php if ($editData): ?>
                    <a href="coachs.php" class="btn-cancel">Annuler</a>
                <?php endif; ?>

            </form>

        </div>

        <!-- Section tableau -->
        <div class="table-section">

            <!-- Tableau des coachs -->
            <table class="data-table">

                <!-- En-tête tableau -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Spécialité</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <!-- Liste des coachs -->
                <tbody>

                <?php
                // Récupération des coachs depuis la base de données
                $coachs = $pdo->query("
                    SELECT * FROM coach ORDER BY id_coach DESC
                ")->fetchAll(PDO::FETCH_ASSOC);

                // Affichage des coachs
                foreach ($coachs as $c) {
                    echo "<tr>

                        <!-- ID coach -->
                        <td>#{$c['id_coach']}</td>

                        <!-- Image coach -->
                        <td><img src='/pfe_gym/{$c['image']}' class='table-img'></td>

                        <!-- Nom -->
                        <td><strong>{$c['nom_complet']}</strong></td>

                        <!-- Spécialité -->
                        <td>{$c['specialite']}</td>

                        <!-- Email -->
                        <td>{$c['email']}</td>

                        <!-- Téléphone -->
                        <td>{$c['telephone']}</td>

                        <!-- Actions -->
                        <td>
                            <a href='?action=modifier&id={$c['id_coach']}' class='btn-edit'>
                                Modifier
                            </a>

                          <td>
                       <a href='?action=modifier&id={$c['id_coach']}' class='btn-edit'>Modifier</a>
                     <a href='?action=supprimer&id={$c['id_coach']}' class='btn-supprimer'>Supprimer</a>
                       </td>

                    </tr>";
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
