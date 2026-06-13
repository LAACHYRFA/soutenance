<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
require "../assets/config/connexion.php";

if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) {
    $pdo->prepare("DELETE FROM abonnement WHERE id_ab = ?")->execute([intval($_GET['id'])]);
    header("Location: abonnemnts.php"); exit();
}

if (isset($_POST['save_forfait'])) {
    $nom = $_POST['nom_forfait'];
    $prix = $_POST['prix'];
    $duree = $_POST['duree'];
    $avantages = $_POST['avantages'];

    if (!empty($_POST['forfait_id'])) {
        $pdo->prepare("UPDATE abonnement SET nom=?, prix_total=?, duree=?, description=? WHERE id_ab=?")
            ->execute([$nom, $prix, $duree, $avantages, $_POST['forfait_id']]);
    } else {
        $pdo->prepare("INSERT INTO abonnement (nom, prix_total, duree, description) VALUES (?,?,?,?)")
            ->execute([$nom, $prix, $duree, $avantages]);
    }
    header("Location: abonnemnts.php"); exit();
}

$updateMode = false;
$editData = [];
if (isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id'])) {
    $updateMode = true;
    $stmt = $pdo->prepare("SELECT * FROM abonnement WHERE id_ab = ?");
    $stmt->execute([intval($_GET['id'])]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

$abonnements = $pdo->query("SELECT * FROM abonnement ORDER BY id_ab DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fitness Pro - Gestion Abonnements</title>
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
        <li class="active"><a href="abonnemnts.php">Abonnements</a></li>
        <li><a href="equipements.php">Équipements</a></li>
        <li><a href="coachs.php">Coachs</a></li>
        <li><a href="users.php">Utilisateur</a></li>
    </ul>
</div>

<div class="main-content">
    <header>
        <h2>Gestion des Abonnements</h2>
    </header>
    <div class="content-container">
    <div class="form-section">
        <?php
        $titre = $updateMode ? "Modifier la formule" : "Ajouter une nouvelle formule";
        echo "<h3>$titre</h3>";
        ?>
        
        <form method="POST">
            <?php
            if ($updateMode) {
                echo "<input type='hidden' name='forfait_id' value='{$editData['id_ab']}'>";
            }
            ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nom du Forfait</label>
                    <input type="text" name="nom_forfait" value="<?= $updateMode ? $editData['nom'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Prix (DH)</label>
                    <input type="number" name="prix" value="<?= $updateMode ? $editData['prix_total'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Durée</label>
                    <input type="text" name="duree" value="<?= $updateMode ? $editData['duree'] : '' ?>" required>
                </div>
                <div class="form-group full-width">
                    <label>Avantages</label>
                    <textarea name="avantages"><?= $updateMode ? $editData['description'] : '' ?></textarea>
                </div>
            </div>
            
            <?php
            echo "<button type='submit' name='save_forfait' class='btn-submit'>Enregistrer</button>";
            if ($updateMode) {
                echo " <a href='abonnemnts.php' class='btn-cancel'>Annuler</a>";
            }
            ?>
        </form>
    </div>
</div>
        <div class="table-section">
            <h3>Formules Actuelles</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Nom</th><th>Prix</th><th>Durée</th><th>Avantages</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($abonnements as $item): ?>
                        <tr>
                            <td>#<?= $item['id_ab'] ?></td>
                            <td><strong><?= $item['nom'] ?></strong></td>
                            <td><?= $item['prix_total'] ?> DH</td>
                            <td><?= $item['duree'] ?></td>
                            <td><?= $item['description'] ?></td>
                           <td>
    <a href="?action=modifier&id=<?= $item['id_ab'] ?>" class="btn-edit">
        Modifier
    </a>

    <a href="?action=supprimer&id=<?= $item['id_ab'] ?>"
       class="btn-supprimer"
       onclick="return confirm('Supprimer cet abonnement ?')">
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
