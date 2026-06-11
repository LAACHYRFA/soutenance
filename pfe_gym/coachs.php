<?php
/**
 * Page Equipements - Fitness Pro
 * Gère l'affichage et la recherche des équipements depuis la base de données
 */

require "assets/config/connexion.php";

// Récupération de la recherche (sécurisée par défaut avec l'opérateur null coalescing)
$rechercher = $_POST['rechercher'] ?? "";

// Construction de la requête SQL de base
$sql = "SELECT * FROM equipement";
$params = [];

// Si le formulaire de recherche est soumis, on ajoute un filtre WHERE
if (isset($_POST['ok']) && !empty($rechercher)) {
    $sql .= " WHERE nom LIKE ?";
    $params = ["%$rechercher%"];
}

// Préparation et exécution de la requête
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
    <style>
        /* Fluidité pour les ancres de navigation */
        html { scroll-behavior: smooth; }
    </style>
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
                <li><a href="index.php#abonnements">Abonnement</a></li>
                <li><a href="index.php#coachs">Coachs</a></li>
                <li><a href="equipements.php" class="active">Equipement</a></li>            
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
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
                // Message si aucun résultat trouvé
                echo "<p style='text-align:center; grid-column: 1/-1; color: #aaa;'>Aucun équipement trouvé.</p>";
            } 
            ?>
        </div>
    </main>

    <footer class="main-footer" id="contact">
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