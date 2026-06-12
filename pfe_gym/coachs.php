<?php
/**
 * 1. CONFIGURATION ET CONNEXION
 */
// Inclusion du fichier de connexion à la base de données
require "assets/config/connexion.php";

// Initialisation des variables pour la recherche
$rechercher = "";
$params = [];

/**
 * 2. LOGIQUE DE RECHERCHE ET VALIDATION
 */
// Construction de la requête SQL de base pour récupérer les coachs
$sql = "SELECT * FROM coach";

// Vérification si le formulaire est soumis et si le champ n'est pas vide
if (isset($_POST['ok'])) {
    // Nettoyage de la saisie utilisateur (trim supprime les espaces inutiles)
    $rechercher = trim($_POST['rechercher'] ?? "");

    // Validation : Si l'utilisateur a tapé quelque chose, on filtre les résultats
    if (!empty($rechercher)) {
        // Utilisation d'une requête préparée pour éviter l'injection SQL
        $sql .= " WHERE nom_complet LIKE ?";
        $params = ["%$rechercher%"];
    }
}

// Préparation et exécution de la requête
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$coachs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachs - Fitness Pro</title>
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
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#abonnement">Abonnement</a></li>
                <li><a href="coachs.php" style="color:red;">Coachs</a></li>
                <li><a href="equipements.php">Equipement</a></li>            
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h2 class="section-title">Nos <span>Coachs</span></h2>

        <div class="search-container">
            <form method="POST" class="search-form">
                <input type="text" name="rechercher" class="input-field" placeholder="Rechercher un coach par nom..." value="<?= htmlspecialchars($rechercher) ?>">
                <button type="submit" name="ok" class="btn-submit">Rechercher</button>
            </form>
        </div>

        <div class="cards-grid">
            <?php 
            // Vérification si des coachs ont été trouvés
            if (!empty($coachs)) { 
                foreach ($coachs as $coach) { 
                    // Sécurisation des données affichées avec htmlspecialchars
                    $nom = htmlspecialchars($coach['nom_complet']);
                    $spec = htmlspecialchars($coach['specialite']);
                    $email = htmlspecialchars($coach['email']);
                    $tel = htmlspecialchars($coach['telephone']);
                    $img = htmlspecialchars($coach['image']);
            ?>
                    <div class="custom-card">
                        <img src="<?= $img ?>" alt="<?= $nom ?>">
                        <h3><?= $nom ?></h3>
                        <p class="card-spec"><?= $spec ?></p>
                        <p style="color: #aaa; font-size: 14px;">📧 <?= $email ?></p>
                        <p style="color: #aaa; font-size: 14px;">📞 <?= $tel ?></p>
                    </div>
            <?php 
                } 
            } else { 
                // Message si aucun résultat ne correspond à la recherche
                echo "<p style='text-align:center; grid-column: 1/-1; color: #aaa;'>Aucun coach trouvé avec ce nom.</p>";
            } 
            ?>
        </div>
    </main>

    <footer class="main-footer" id="contact">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
                   <span class="logo-title">FITNESS PRO</span>
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