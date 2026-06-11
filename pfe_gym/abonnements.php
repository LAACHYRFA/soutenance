<?php
// Connexion à la base de données
require "connexion.php";

// Récupération directe de tous les abonnements depuis la table 'abonnement'
$stmt = $pdo->query("SELECT * FROM abonnement");
$abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnements - Fitness Pro</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/search.css">
    <link rel="stylesheet" href="assets/css/cards.css">
</head>
<body>

 <header class="main_header">
        <div class="header_logo">
            <img src="assets/images/page_acceuil/logo.png" alt="Logo Fitness Pro" class="logo_img">
            <span style="font-family: 'Arial Black', Gadget, sans-serif;text-transform: uppercase;">FITNESS PRO</span>
        </div>
        <nav class="header_nav">
            <ul class="nav_links">
                <li><a href="index.php" >Accueil</a></li>
                <li><a href="abonnements.php" class="active">Abonnement</a></li>
                <li><a href="coachs.php">Coachs</a></li>
                <li><a href="equipements.php">Equipement</a></li>             
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

   <div class="container">
    <h2 class="section-title">Nos <span>Forfaits</span></h2>

    <div class="pricing-grid">
        <?php
        // Vérification si la liste des abonnements n'est pas vide
        if (!empty($abonnements)) {
            // Boucle pour afficher chaque forfait dynamiquement
            foreach ($abonnements as $item) {
                echo "<div class='custom-card pricing-card'>";
                
                // Affichage du nom du forfait
                echo "<h3>" . htmlspecialchars($item['nom']) . "</h3>";
                
                // Affichage du prix total
                echo "<div class='card-price'>" . htmlspecialchars($item['prix_total']) . " 💲</div>";
                
                // Affichage de la durée de l'abonnement
                echo "<p class='card-duration'>📅 Durée: " . htmlspecialchars($item['duree']) . "</p>";
                
                // Affichage de la description avec conversion des virgules en retour à la ligne
                echo "<p class='card-desc'>" . str_replace(',', "<br>", htmlspecialchars($item['description'])) . "</p>";
                
                // Bouton de réservation
                echo "<a href='signup.php?id_ab=" . $item['id_ab'] . "' class='btn-submit'>Réserver</a>";
                echo "</div>";
            }
        } else {
            // Message si aucun abonnement n'est trouvé
            echo "<p style='text-align:center; grid-column: 1/-1;'>Aucun abonnement disponible pour le moment.</p>";
        }
        ?>
    </div>
</div>

    <footer class="main-footer">
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
                <ul class="contact-list">
                    <li>Tanger, Maroc</li>
                    <li>+212-612345678</li>
                    <li>gym@email.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Fitness Pro – Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>