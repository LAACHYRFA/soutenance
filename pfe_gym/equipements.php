<?php
require "config/connexion.php";

$rechercher = $_POST['rechercher'] ?? "";
$sql = "SELECT * FROM equipement";
$params = [];

if (isset($_POST['ok']) && !empty($rechercher)) {
    $sql .= " WHERE nom LIKE ?";
    $params = ["%$rechercher%"];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Équipements - Fitness Pro</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/search.css">
    <link rel="stylesheet" href="assets/css/cards.css">
</head>
<body>

   
    <header class="main_header">
        <div class="header_logo">
            <img src="assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
            <span>FITNESS PRO</span>
        </div>
        <nav class="header_nav">
            <ul class="nav_links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#abonnemnet">Abonnement</a></li>
                <li><a href="coachs.php" >Coachs</a></li>
                <li><a href="equipements.php" class="active">Equipement</a></li>             
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2 class="section-title">Notre <span>Matériel</span></h2>

        <div class="search-container">
            <form method="POST" class="search-form">
                <input type="text" name="rechercher" class="input-field" placeholder="Rechercher par nom..." value="<?= htmlspecialchars($rechercher) ?>">
                <button type="submit" name="ok" class="btn-submit">Rechercher</button>
            </form>
        </div>

        <div class="cards-grid">
            <?php 
            if ($equipements) { 
                foreach ($equipements as $eq) {
                    echo "<div class='custom-card'>
                            <img src='./{$eq['image']}' alt='Equipement'>
                            <h3>{$eq['nom']}</h3>
                            <p style='color: #aaa; font-size:14px;'>Catégorie: {$eq['categorie']}</p>
                            <div class='card-price' style='font-size:16px;'>Quantité: {$eq['quantite']}</div>
                          </div>";
                } 
            } else { 
                echo "<p style='text-align:center; grid-column: 1/-1; color: #aaa;'>Aucun équipement trouvé.</p>";
            } 
            ?>
        </div>
    </div>

    <footer class="main-footer"  id="contact">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="assets/images/page_acceuil/logo.png" alt="Logo Fitness Pro" class="logo_img">
                    <span>FITNESS PRO</span>
                </div>
                <p class="footer-text">Transformez votre corps et restez en bonne santé avec nous.</p>
            </div>
            <div class="footer-contact">
                <h3 class="contact-title">NOUS CONTACTER</h3>
                <ul>
                    <li>Tanger, Maroc</li>
                    <li>+212-612345678</li>
                    <li>gym@email.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom"><p>&copy; 2026 Fitness Pro – Tous droits réservés.</p></div>
    </footer>
       
</body>
</html>