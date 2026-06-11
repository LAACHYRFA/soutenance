<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require "../config/connexion.php";

// Suppression
if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) {

    $pdo->prepare("DELETE FROM coach WHERE id_coach = ?")
        ->execute([intval($_GET['id'])]);

    header("Location: coachs.php");
    exit();
}

// Ajout / Modification
if (isset($_POST['save_coach'])) {

    $id = $_POST['coach_id'] ?? null;

    $nom = $_POST['nom'];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tel = $_POST['telephone'];

    $img = $_FILES['img']['name']
        ? $_FILES['img']['name']
        : ($_POST['old_img'] ?? 'default.jpg');

    if ($_FILES['img']['name']) {
        move_uploaded_file(
            $_FILES['img']['tmp_name'],
            "../images/" . $img
        );
    }

    if ($id) {

        $pdo->prepare("UPDATE coach SET nom_complet=?, image=?,  specialite=?, email=?, telephone=?
            WHERE id_coach=?")->execute([$nom, $img,$spec,$email, $tel, $id
        ]);

    } else {

        $pdo->prepare("INSERT INTO coach(nom_complet,image,specialite,email,telephone)
        VALUES (?,?,?,?,?)")->execute([$nom,$img,$spec, $email,$tel
          ]);
    }

    header("Location: coachs.php");
    exit();
}

// Mode modification
$editData = null;

if (
    isset($_GET['action']) &&
    $_GET['action'] == "modifier" &&
    isset($_GET['id'])
) {

    $stmt = $pdo->prepare("SELECT * FROM coach WHERE id_coach = ?");
    $stmt->execute([intval($_GET['id'])]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fitness Pro - Gestion Coachs</title>

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
        <li class="active"><a href="coachs.php">Coachs</a></li>
        <li><a href="users.php">Utilisateur</a></li>
        <li><a href="messages.php">Messages</a></li>
    </ul>
</div>

<div class="main-content">

    <header>
        <h2>Gestion des Coachs</h2>
    </header>

    <div class="content-container">

        <div class="form-section">

            <h3>
                <?= $editData ? "Modifier le coach" : "Ajouter un Coach" ?>
            </h3>

            <form method="POST" enctype="multipart/form-data">

                <input type="hidden" name="coach_id" value="<?= $editData['id_coach'] ?? '' ?>">

                <input type="hidden" name="old_img"  value="<?= $editData['image'] ?? '' ?>">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text"name="nom" value="<?= $editData['nom_complet'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Spécialité</label>
                        <input type="text" name="spec" value="<?= $editData['specialite'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= $editData['email'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" value="<?= $editData['telephone'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="img">
                    </div>

                </div>

                <button type="submit" name="save_coach" class="btn-submit"> Enregistre</button>

                <?php
                if ($editData) {
                    echo '<a href="coachs.php" class="btn-cancel">Annuler</a>';
                }
                ?>

            </form>

        </div>

        <div class="table-section">

            <table class="data-table">

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

                <tbody>

                <?php

                $coachs = $pdo->query("
                    SELECT *FROM coach ORDER BY id_coach DESC")->fetchAll(PDO::FETCH_ASSOC);

                foreach ($coachs as $c) {

                    echo "<tr>
                        <td>#{$c['id_coach']}</td>
                        <td><img src='/pfe_gym/{$c['image']}' class='table-img'></td>
                        <td><strong>{$c['nom_complet']}</strong></td>
                        <td>{$c['specialite']}</td>
                        <td>{$c['email']}</td>
                        <td>{$c['telephone']}</td>
                        <td>
                            <a href='?action=modifier&id={$c['id_coach']}' class='btn-edit'>Modifier</a>
                            <a href='?action=supprimer&id={$c['id_coach']}' class='btn-supprimer' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
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