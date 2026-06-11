<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
require "../config/connexion.php";

// Suppression
if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) { 
    $pdo->prepare("DELETE FROM equipement WHERE id_eq = ?")->execute([intval($_GET['id'])]);
    header("Location: equipements.php"); exit(); 
}

// Traitement Ajout / Modification
if (isset($_POST['save_equipement'])) {
    $id = $_POST['equipement_id'] ?? null;
    $nom = $_POST['nom']; 
    $cat = $_POST['categorie']; 
    $qte = intval($_POST['quantite']); 
    $img = $_FILES['image']['name'] ? $_FILES['image']['name'] : ($_POST['old_image'] ?? 'default.jpg');
    
    if ($_FILES['image']['name']) move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $img);
    
    if ($id) {
        $pdo->prepare("UPDATE equipement SET nom=?, categorie=?, quantite=?, image=? WHERE id_eq=?")
            ->execute([$nom, $cat, $qte, $img, $id]);
    } else {
        $pdo->prepare("INSERT INTO equipement (nom, categorie, quantite, image) VALUES (?,?,?,?)")
            ->execute([$nom, $cat, $qte, $img]);
    }
    header("Location: equipements.php"); exit();
}

// Mode modification
$editData = null;
if (isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id'])) { 
    $stmt = $pdo->prepare("SELECT * FROM equipement WHERE id_eq = ?");
    $stmt->execute([intval($_GET['id'])]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fitness Pro - Gestion Équipements</title>
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
        <li class="active"><a href="equipements.php">Équipements</a></li>
        <li><a href="coachs.php">Coachs</a></li>
        <li><a href="users.php">Utilisateur</a></li>
        <li><a href="messages.php">Messages</a></li>
    </ul>
</div>

<div class="main-content">
    <header><h2>Gestion des Équipements</h2></header>
    <div class="content-container">
        <div class="form-section">
            <h3><?= $editData ? "Modifier le matériel" : "Ajouter un Équipement" ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="equipement_id" value="<?= $editData['id_eq'] ?? '' ?>">
                <input type="hidden" name="old_image" value="<?= $editData['image'] ?? '' ?>">
                
                <div class="form-grid">
                    <div class="form-group"><label>Nom</label><input type="text" name="nom" value="<?= $editData['nom'] ?? '' ?>" required></div>
                    <div class="form-group"><label>Catégorie</label><input type="text" name="categorie" value="<?= $editData['categorie'] ?? '' ?>" required></div>
                    <div class="form-group"><label>Quantité</label><input type="number" name="quantite" value="<?= $editData['quantite'] ?? '' ?>" required></div>
                    <div class="form-group"><label>Image</label><input type="file" name="image"></div>
                </div>
                <button type="submit" name="save_equipement" class="btn-submit">Enregistrer</button>
                <?php if ($editData) echo '<a href="equipements.php" class="btn-cancel">Annuler</a>'; ?>
            </form>
        </div>

        <div class="table-section">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Image</th><th>Nom</th><th>Catégorie</th><th>Qté</th><th>Actions</th></tr></thead>
                <tbody>
                <?php 
                $equipements = $pdo->query("SELECT * FROM equipement ORDER BY id_eq DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($equipements as $eq) {
                    echo "<tr>
                        <td>#{$eq['id_eq']}</td>
                        <td><img src='/pfe_gym/{$eq['image']}' class='table-img'></td>
                        <td><strong>{$eq['nom']}</strong></td>
                        <td>{$eq['categorie']}</td>
                        <td>{$eq['quantite']}</td>
                        <td>
                            <a href='?action=modifier&id={$eq['id_eq']}' class='btn-edit'>Modifier</a>
                            <a href='?action=supprimer&id={$eq['id_eq']}' class='btn-supprimer' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
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