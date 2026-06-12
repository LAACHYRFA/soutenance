<?php
/**
 * 1. CONFIGURATION ET CONNEXION
 * On inclut le fichier de connexion à la base de données (PDO).
 */
require "assets/config/connexion.php";

// Initialisation des variables pour éviter les erreurs de type "undefined index"
$message_erreur = "";
$rechercher = "";

/**
 * 2. VALIDATION DES DONNÉES (TRAITEMENT DU FORMULAIRE)
 * On vérifie si le bouton "ok" a été cliqué.
 */
if (isset($_POST['ok'])) {
    // trim() : Supprime les espaces inutiles au début et à la fin de la saisie.
    // htmlspecialchars() : Empêche l'exécution de codes malveillants (attaques XSS).
    $rechercher = trim($_POST['rechercher'] ?? "");


    // Validation : On vérifie si le champ n'est pas vide
    if (empty($rechercher)) {
        $message_erreur = "Veuillez saisir un mot-clé pour effectuer la recherche.";
    }
}

/**
 * 3. REQUÊTE SQL SÉCURISÉE
 * On utilise des requêtes préparées avec des marqueurs "?" pour empêcher l'injection SQL.
 */
$sql = "SELECT * FROM equipement";
$params = [];

// Si une recherche est valide, on ajoute une clause WHERE à la requête
if (!empty($rechercher)) {
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
</head>
<body>

    <!-- En-tête -->
    <header class="main_header">
        <div class="header_logo">
            <img src="assets/images/page_acceuil/logo.png" alt="Logo" class="logo_img">
           <span class="logo-title">FITNESS PRO</span>
        </div>
        <nav class="header_nav">
            <ul class="nav_links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#abonnemnet">Abonnement</a></li>
                <li><a href="coachs.php">Coachs</a></li>
                <li><a href="equipements.php"  style="color:red;">Equipement</a></li>            
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2 class="section-title">Notre <span>Matériel</span></h2>

        <!-- Barre de recherche -->
        <div class="search-container">
            <form method="POST" class="search-form">
                <input type="text" name="rechercher" class="input-field" placeholder="Rechercher par nom..." value="<?= htmlspecialchars($rechercher) ?>">
                <button type="submit" name="ok" class="btn-submit">Rechercher</button>
            </form>
            
            <!-- Affichage du message d'erreur si la saisie est invalide -->
            <?php if (!empty($message_erreur)): ?>
                <p style='color:red; text-align:center; margin-top: 10px;'><?= $message_erreur ?></p>
            <?php endif; ?>
        </div>

        <!-- Grille des équipements -->
        <div class="cards-grid">
            <?php 
            if (!empty($equipements)) { 
                foreach ($equipements as $eq) {
                    // Sécurisation de l'affichage des données provenant de la BDD
                    $nom = htmlspecialchars($eq['nom']);
                    $cat = htmlspecialchars($eq['categorie']);
                    $img = htmlspecialchars($eq['image']);
                    $qte = (int)$eq['quantite']; // Force la valeur en entier pour la sécurité

                    echo "<div class='custom-card'>
                            <img src='./{$img}' alt='{$nom}'>
                            <h3>{$nom}</h3>
                            <p style='color: #aaa; font-size:14px;'>Catégorie: {$cat}</p>
                            <div class='card-price' style='font-size:16px;'>Quantité: {$qte}</div>
                          </div>";
                } 
            } else { 
                echo "<p style='text-align:center; grid-column: 1/-1; color: #aaa;'>Aucun équipement ne correspond à votre recherche.</p>";
            } 
            ?>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="main-footer" id="contact">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="assets/images/page_acceuil/logo.png" alt="Logo Fitness Pro" class="logo_img">
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