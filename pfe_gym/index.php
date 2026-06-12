
<?php
require "assets/config/connexion.php";

$stmt = $pdo->query("SELECT * FROM abonnement");
$abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Pro - Accueil</title>
       <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/search.css">
    <link rel="stylesheet" href="assets/css/cards.css">
</head>
<body>

    <header class="main_header">
        <div class="header_logo">
            <img src="assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
            <span class="logo-title">FITNESS PRO</span>
        </div>
        <nav class="header_nav">
            <ul class="nav_links">
                <li><a href="index.php" style="color:red;">Accueil</a></li>
               <li><a href="#abonnement">Abonnement</a></li>
                <li><a href="coachs.php" >Coachs</a></li>
                <li><a href="equipements.php">Equipement</a></li>             
                <li><a href="#contact">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
        <li style="color: #FF0000; font-weight: bold; padding: 10px; display: flex; align-items: center;">
            <?= htmlspecialchars($_SESSION['user_name']) ?>
        </li>
        <?php endif; ?>
            </ul>
        </nav>
    </header>

    
    <section class="hero_section" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/images/page_acceuil/acceuil_bground.jpg'); height: 85vh; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center;">   
        <div class="hero_content">
            <h1 class="hero_title" style="font-size: 48px; font-weight: 900; letter-spacing: 2px; color: #fff; margin-bottom: 20px;">TRANSFORMEZ VOTRE CORPS</h1>
            <p class="subtitle" style="font-size: 18px; color: #ccc; max-width: 600px; margin: auto;">Atteignez vos objectifs fitness avec nos coachs experts et nos programmes personnalisés.</p>
        </div>
    </section>

   <div id="abonnement" class="container">
        <h2 class="section-title" style="text-align: center; font-size: 30px; margin-bottom: 40px; text-transform: uppercase;">choisissezr votre <span>forfait</span></h2>
    <div class="pricing-grid">

         <?php
        if ($abonnements) {
            foreach ($abonnements as $item) {
                $desc = str_replace(',', '<br>', htmlspecialchars($item['description']));
                echo "<div class='custom-card pricing-card'>
                        <h3>" . htmlspecialchars($item['nom']) . "</h3>
                        <div class='card-price'>" . htmlspecialchars($item['prix_total']) . " 💲</div>
                        <p class='card-duration'>📅 Durée: " . htmlspecialchars($item['duree']) . "</p>
                        <p class='card-desc'>$desc</p>
                     <a href='signup.php?id_ab=" . $item['id_ab'] . "' class='btn-submit'>Réserver</a>            }
        } else {
            echo "<p style='text-align:center; grid-column: 1/-1;'>Aucun abonnement disponible pour le moment.</p>";
        }
        ?>

    </div>
</div>
 
    <div class="container">
        <h2 class="section-title" style="text-align: center; font-size: 30px; margin-bottom: 40px; text-transform: uppercase;">Nos <span>coachs</span></h2>
        <div class="cards-grid">
            <div class="custom-card">
                <img src="assets/images/page_acceuil/coach_lina.jpg" alt="Lina El Idrissi">
                <h3>Lina El Idrissi</h3>
                <p class="card-spec">Fitness & Musculation</p>
            </div>
            <div class="custom-card">
                <img src="assets/images/page_acceuil/ouss_bouk.png" alt="Oussama Boukh">
                <h3>Oussama Boukh</h3>
                <p class="card-spec">Fitness & Musculation</p>
            </div>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title" style="text-align: center; font-size: 30px; margin-bottom: 40px; text-transform: uppercase;">NOS <span>ÉQUIPEMENTS</span></h2>
        <div class="cards-grid">
            <div class="custom-card">
                <img src="assets/images/page_acceuil/cardio.webp" alt="Machine cardio">
                <h3>Machine cardio</h3>
            </div>
            <div class="custom-card">
                <img src="assets/images/page_acceuil/kettlebels.avif" alt="Kettlebells">
                <h3>Kettlebells</h3>
            </div>
        </div>
    </div>

    <footer class="main-footer"  id="contact">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="assets/images/page_acceuil/logo.png" alt="Logo Fitness Pro" class="logo_img" style="width:40px;">
                   <span class="logo-title">FITNESS PRO</span>
                </div>
                <p class="footer-text">Transformez votre corps et restez en bonne santé avec nous.</p>
            </div>
            <div class="footer-contact">
                <h3 class="contact-title">NOUS CONTACTER</h3>
                <ul class="contact-list" style="list-style:none; padding:0; margin:0;">
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