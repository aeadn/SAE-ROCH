<?php
require_once('backoffice/connexions_et_id/connexionBDD.php');
require_once('backoffice/commandes/recuperer_donnees.php');

// Récupération de l’ID de la catégorie "Video" (insensible à la casse)
$videoCategoryQuery = "SELECT * FROM categories WHERE LOWER(nom) = 'video' LIMIT 1";
$videoCategoryResult = mysqli_query($connexion_bdd, $videoCategoryQuery);
if ($videoCategoryResult && mysqli_num_rows($videoCategoryResult) > 0) {
    $videoCategoryId = mysqli_fetch_assoc($videoCategoryResult)['id'];
} else {
    $videoCategoryId = 0; // Si la catégorie 'video' n'est pas trouvée
}

// Récupérer les données
$competences = recupererDonnees($connexion_bdd, "SELECT * FROM `competences`;"); 
$information = recupererDonnees($connexion_bdd, "SELECT * FROM `info`;"); 
$projets = recupererDonnees($connexion_bdd, "SELECT * FROM `projets`;"); 
$parcours = mysqli_query($connexion_bdd, "SELECT * FROM experience ORDER BY dateF ASC"); 

// Query Projets (tous les projets)
$projetsQuery = "
    SELECT projets.*, categories.nom AS categorie_nom
    FROM projets
    LEFT JOIN categories ON projets.idCategorie = categories.id
";
$projets = recupererDonnees($connexion_bdd, $projetsQuery);

// Query Video (uniquement les projets vidéo)
$projetsVideoQuery = "
    SELECT projets.*, categories.nom AS categorie_nom
    FROM projets
    LEFT JOIN categories ON projets.idCategorie = categories.id
    WHERE categories.nom = 'Video'  -- Filtrage pour la catégorie vidéo
";
$projetsVideo = recupererDonnees($connexion_bdd, $projetsVideoQuery);

// Sécuriser si vide
if (!is_array($competences)) { $competences = []; }
if (!is_array($information)) { $information = []; }
?>

<?php
// Fonction pour transformer le lien YouTube en URL d'intégration
function transformerLienYoutube($lien) {
    $pattern = "#^https?://(?:www\.)?youtube\.com/watch\?v=([a-zA-Z0-9_-]+)#";  // Utilisation de '#' comme délimiteur
    $replacement = "https://www.youtube.com/embed/$1";
    return preg_replace($pattern, $replacement, $lien);
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Alpha by HTML5 UP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="landing is-preload">
    <?php include('header.php'); ?>

    <div id="page-wrapper">
        <!-- Banner -->
        <section id="banner">
            <?php if (!empty($information[0]['photo'])): ?>
                <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                    <img src="<?= htmlspecialchars($information[0]['photo']); ?>" alt="Photo de profil" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
                </div>
            <?php endif; ?>
            <h2><?= htmlspecialchars($information[0]['prenom']) . ' ' . htmlspecialchars($information[0]['nom']); ?></h2>
            <p>Je suis <?= htmlspecialchars($information[0]['statut']); ?>.</p>
        </section>

        <!-- Main -->
        <section id="main" class="container">

            <!-- Présentation -->
            <section class="box special">
                <header class="major">
                    <h2>Présentation</h2>
                    <p><?= htmlspecialchars($information[0]['intro']); ?></p>
                </header>
                <span class="image featured"><img src="images/pic01.jpg" alt="" /></span>
            </section>

            <!-- Compétences -->
            <section id="competences" class="box special features">
                <h2>Mes Compétences</h2>
                <div class="features-row">
                    <?php foreach ($competences as $index => $competence): ?>
                        <?php if ($index % 2 == 0): ?><div class="features-row"><?php endif; ?>
                        <section>
                            <h3><?= htmlspecialchars($competence['nom']); ?></h3>
                            <p><?= htmlspecialchars($competence['texte']); ?></p>
                        </section>
                        <?php if ($index % 2 == 1 || $index == count($competences) - 1): ?></div><?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Projets -->
            <div id="projets" class="column">
                <h2 class="text-3xl font-bold text-center mb-6 w-full">Mes projets<br></h2>
                <?php foreach ($projets as $projet): ?>
                    <?php if ($projet['idCategorie'] != $videoCategoryId): ?>
                        <div class="col-6">
                            <section class="box special" >
                                <span class="image featured" style="width: auto; height: 300px; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                                <img src="<?= htmlspecialchars($projet['img']); ?>" alt="Image du projet" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </span>
                                <div style="padding: 20px; text-align: center;">
                                    <h3><?= htmlspecialchars($projet['titre']); ?></h3>
                                    <p><?= htmlspecialchars($projet['texte']); ?></p>
                                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($projet['categorie_nom']); ?></p>
                                </div>
                            </section>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <!-- Projets vidéos -->
            <div id="projets-videos" class="column">
                <h2 class="text-3xl font-bold text-center mb-6 w-full">Mes projets vidéos<br></h2>
                <?php if ($projetsVideo): ?>
                        <div class="col-6" >
                        <?php foreach ($projetsVideo as $projet): ?>
                            <section class="box special" >
                                <div>
                                    <h3><?= htmlspecialchars($projet['titre']); ?></h3>
                                    <?php 
                                        // Vérifie si un lien vidéo est présent
                                        if (!empty($projet['lien'])) {
                                            $lienEmbed = transformerLienYoutube($projet['lien']); // Applique la fonction de transformation du lien
                                            // L'iframe YouTube avec plus d'espace
                                            echo "<iframe width='100%' height='500' src='" . htmlspecialchars($lienEmbed) . "' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                                        }
                                    ?>
                                    <p><?= htmlspecialchars($projet['texte']); ?></p>
                                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($projet['categorie_nom']); ?></p>
                                </div>
                            </section>
                            <?php endforeach; ?>
                        </div>
                <?php else: ?>
                    <p>Aucun projet vidéo trouvé.</p>
                <?php endif; ?>
            </div>

            <!-- Parcours -->
            <div id="parcours" class="row" style="display: flex; flex-direction: column; gap: 20px; justify-content: flex-start; margin-top: 50px;">
                <!-- Titre du Parcours -->
                <h2 class="text-3xl font-bold text-left mb-6 w-full" style="margin-left: 20px;">Mon Parcours</h2>
                
                <?php while ($parcour = mysqli_fetch_assoc($parcours)): ?>
                    <div class="col-12" style="width: 100%; display: flex; justify-content: flex-start; flex-direction: column; align-items: flex-start;">
                        <section class="box special" style="width: 60%; padding: 20px; text-align: left; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                            <!-- Titre et dates -->
                            <h3 style="font-size: 1.5rem; margin-bottom: 10px; text-align: left;">
                                <?= htmlspecialchars($parcour['occupation'] ?? 'Entreprise non spécifiée'); ?>
                            </h3>
                            <h4 style="font-size: 1.2rem; margin-bottom: 10px; text-align: left;">
                                <strong>Dates :</strong> <?= htmlspecialchars($parcour['dateD']) . ' / ' . htmlspecialchars($parcour['dateF']); ?>
                            </h4>
                            <!-- Lieu -->
                            <p style="font-size: 1.2rem; color: #555; text-align: left;">
                                <strong>Lieu :</strong> <?= htmlspecialchars($parcour['lieu'] ?? 'Description non fournie'); ?>
                            </p>
                        </section>
                    </div>
                <?php endwhile; ?>
            </div>

        </section>
    </div>

    <?php include('footer.php'); ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.dropotron.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
