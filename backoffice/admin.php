<?php  
session_start(); // Démarrer la session immédiatement  

if (isset($_POST['logout'])) {     
    session_unset(); // Supprime toutes les variables de session     
    session_destroy(); // Détruit la session     
    header("Location: ../index.php"); // Redirige vers index.php (un dossier au-dessus)     
    exit; 
}  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Import Tailwind -->
</head>
<body class="bg-gray-100 font-sans">

<?php  
require_once('header_backoffice.php'); // insertion du header
require_once('commandes/afficher_tableaux.php'); // afficher les tableaux
require_once('connexions_et_id/connexionBDD.php');
require_once('commandes/recuperer_donnees.php'); // insérer la connexion avec les données

// Récupération des données
$social_media = recupererDonnees($connexion_bdd, "SELECT id, nom, icone, lien FROM `social_media`;");
$projets = recupererDonnees($connexion_bdd, "SELECT id, titre, texte, img FROM `projets`;");
$competences = recupererDonnees($connexion_bdd, "SELECT id, nom, texte, picto FROM `competences`;");
$info = recupererDonnees($connexion_bdd, "SELECT id, nom, prenom, intro, photo FROM `info`;");
?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Tableau de Bord</h1>

    <!-- Section Infos -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Informations</h2>
        <?php afficherTableau($info, ['Nom', 'Prenom', 'Introduction', 'Photo'], 'commandes/info/modifier_info.php?id=', 'commandes/info/supprimer_info.php?id='); ?>
        <a href="commandes/info/ajouter_info.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
    </div>

    <!-- Section Réseaux sociaux -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Réseaux Sociaux</h2>
        <?php afficherTableau($social_media, ['Nom', 'Icône', 'Lien'], 'commandes/modifier.php?id=', 'commandes/supprimer.php?id='); ?>
        <a href="commandes/ajouter.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
    </div>

    <!-- Section Projets -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Projets</h2>
        <?php afficherTableau($projets, ['Titre', 'Description', 'Image'], 'commandes/projets/modifier_projet.php?id=', 'commandes/projets/supprimer_projet.php?id='); ?>
        <a href="commandes/projets/ajouter_projet.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
    </div>

    <!-- Section Compétences -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Compétences</h2>
        <?php afficherTableau($competences, ['Nom', 'Texte', 'Picto'], 'commandes/competences/modifier_competence.php?id=', 'commandes/competences/supprimer_competence.php?id='); ?>
        <a href="commandes/competences/ajouter_competence.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
    </div>

    <!-- Bouton pour le CV -->
    <?php
        require_once("commandes/upload_cv.php"); // On inclut la fonction d'upload
        // Vérifier si le formulaire a été soumis
        if (isset($_POST['upload_cv'])) {
            echo uploadCV($_FILES['cv']);
        }
        ?>
        

        <!-- Bouton de Déconnexion -->
        <div class="text-center mt-8">
        <form method="POST" action="">
            <button type="submit" name="logout" class="px-6 py-3 bg-red-500 text-white font-bold rounded-lg shadow-md hover:bg-red-600 transition">Déconnexion</button>
        </form>
    </div>
</div>

</body>
</html>

