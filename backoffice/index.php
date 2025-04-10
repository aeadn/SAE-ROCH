<?php  
session_start();



if (isset($_POST['logout'])) {     
    session_unset();     
    session_destroy();     
    header("Location: ../index.php");     
    exit; 
}  

function rechercher($connexion_bdd, $table, $columns, $searchTerm) {
    $searchTerm = "%$searchTerm%"; // Encapsule le terme de recherche avec des jokers pour une recherche PARTIELLE.
    $columnString = implode(", ", $columns); // Crée une chaîne avec toutes les colonnes pour la requête SQL

    // Requête SQL dynamique pour chercher dans les colonnes spécifiées
    $sql = "SELECT * FROM $table WHERE ";
    $sqlParts = [];
    foreach ($columns as $column) {
        $sqlParts[] = "$column LIKE ?";
    }
    $sql .= implode(" OR ", $sqlParts); // Permet de chercher dans plusieurs colonnes
    
    $stmt = $connexion_bdd->prepare($sql);
    
    // Lier les paramètres de la requête
    $types = str_repeat('s', count($columns));  // Crée une chaîne de types ('s' pour string) pour la méthode bind_param
    $stmt->bind_param($types, ...array_fill(0, count($columns), $searchTerm));

    $stmt->execute();

    // Récupérer les résultats
    $result = $stmt->get_result();
    
    // Récupérer toutes les lignes sous forme de tableau associatif
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data; // Renvoyer les données récupérées
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<form method="POST" class="absolute top-4 right-6">
    <button type="submit" name="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        Déconnexion
    </button>
</form>

<?php  
require_once('connexions_et_id/connexionBDD.php');
require_once('header_backoffice.php');
require_once('commandes/afficher_tableaux.php');
require_once('commandes/recuperer_donnees.php');

// Récupérer les données pour chaque section
$social_media = recupererDonnees($connexion_bdd, "SELECT id, nom, icone, lien FROM `social_media`;"); 
$projets = recupererDonnees($connexion_bdd, "SELECT projets.id, projets.titre, projets.texte, categories.nom AS nomCategorie, projets.img, projets.lien FROM projets LEFT JOIN categories ON projets.idCategorie = categories.id");
$competences = recupererDonnees($connexion_bdd, "SELECT id, nom, texte FROM `competences`;"); 
$info = recupererDonnees($connexion_bdd, "SELECT id, nom, prenom, intro, photo FROM `info`;"); 
$categories = recupererDonnees($connexion_bdd, "SELECT id, nom, `description` FROM categories");
$experiences = recupererDonnees($connexion_bdd, "SELECT * FROM experience");

// Fonction de recherche dans chaque tableau
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $social_media = rechercher($connexion_bdd, 'social_media', ['nom', 'icone', 'lien'], $searchTerm);
    $projets = rechercher($connexion_bdd, 'projets', ['titre', 'texte', 'idCategorie', 'img', 'lien'], $searchTerm);
    $competences = rechercher($connexion_bdd, 'competences', ['nom', 'texte'], $searchTerm);
    $info = rechercher($connexion_bdd, 'info', ['nom', 'prenom', 'intro', 'photo'], $searchTerm);
    $categories = rechercher($connexion_bdd, 'categories', ['nom', 'description'], $searchTerm);
    $experiences = rechercher($connexion_bdd, 'experience', ['occupation', 'lieu', 'dateD', 'dateF'], $searchTerm);
}
?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Backoffice</h1>

    <!-- Section Infos -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Informations</h2>
        <?php afficherTableau(
            $info,
            ['nom', 'prenom', 'intro', 'photo'],
            ['Nom', 'Prénom', 'Introduction', 'Photo'],
            'commandes/info/modifier_info.php?id=',
            ''
        );
        ?> 
    </div>

    <!-- Section Réseaux sociaux -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Réseaux Sociaux</h2>

    <!-- Formulaire de recherche pour les réseaux sociaux -->
    <form method="POST" class="mb-4 flex justify-end">
        <input type="text" name="search_social_media" placeholder="Rechercher..." class="px-4 py-2 border border-gray-300 rounded-md w-1/4">
        <button type="submit" name="search_button_social_media" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-2">Rechercher</button>
    </form>

    <?php
        $searchTerm = isset($_POST['search_social_media']) ? $_POST['search_social_media'] : '';
        $filteredSocialMedia = rechercher($connexion_bdd, 'social_media', ['nom', 'icone'], $searchTerm);
        afficherTableau(
            $filteredSocialMedia,
            ['nom', 'icone', 'lien'],
            ['Nom', 'Icône', 'Lien'],
            'commandes/social_media/modifier_social_media.php?id=',
            'commandes/social_media/supprimer_social_media.php?id='
        );
    ?>
    <a href="commandes/social_media/ajouter_social_media.php" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
</div>


<!-- Section Projets -->
<div class="bg-white shadow-lg rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Projets</h2>

    <!-- Formulaire de recherche pour les projets -->
    <form method="POST" class="mb-4 flex justify-end">
        <input type="text" name="search_projets" placeholder="Rechercher..." class="px-4 py-2 border border-gray-300 rounded-md w-1/4">
        <button type="submit" name="search_button_projets" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-2">Rechercher</button>
    </form>

    <?php
        $searchTerm = isset($_POST['search_projets']) ? $_POST['search_projets'] : '';
        $filteredProjets = rechercher($connexion_bdd, 'projets', ['titre', 'texte'], $searchTerm);
        afficherTableau(
            $filteredProjets,
            ['titre', 'texte', 'nomCategorie', 'img', 'lien'],
            ['Titre', 'Texte', 'Catégorie', 'Image', 'Lien'],
            'commandes/projets/modifier_projet.php?id=',
            'commandes/projets/supprimer_projet.php?id='
        );
    ?>
    <a href="commandes/projets/ajouter_projet.php" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
</div>



    <!-- Section Compétences -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Compétences</h2>

    <!-- Formulaire de recherche pour les compétences -->
    <form method="POST" class="mb-4 flex justify-end">
        <input type="text" name="search_competences" placeholder="Rechercher..." class="px-4 py-2 border border-gray-300 rounded-md w-1/4">
        <button type="submit" name="search_button_competences" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-2">Rechercher</button>
    </form>

    <?php
        $searchTerm = isset($_POST['search_competences']) ? $_POST['search_competences'] : '';
        $filteredCompetences = rechercher($connexion_bdd, 'competences', ['nom', 'texte'], $searchTerm);
        afficherTableau(
            $filteredCompetences,
            ['nom', 'texte'],
            ['Nom', 'Texte'],
            'commandes/competences/modifier_competence.php?id=',
            'commandes/competences/supprimer_competence.php?id='
        );
    ?>
    <a href="commandes/competences/ajouter_competence.php" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
</div>

    <!-- Section Categories -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Catégories</h2>

    <!-- Formulaire de recherche pour les catégories -->
    <form method="POST" class="mb-4 flex justify-end">
        <input type="text" name="search_categories" placeholder="Rechercher..." class="px-4 py-2 border border-gray-300 rounded-md w-1/4">
        <button type="submit" name="search_button_categories" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-2">Rechercher</button>
    </form>

    <?php
        $searchTerm = isset($_POST['search_categories']) ? $_POST['search_categories'] : '';
        $filteredCategories = rechercher($connexion_bdd, 'categories', ['nom', 'description'], $searchTerm);
        afficherTableau(
            $filteredCategories,
            ['nom'],
            ['Nom'],
            'commandes/categories/modifier_categorie.php?id=',
            'commandes/categories/supprimer_categorie.php?id='
        );
    ?>
    </div>

    <!-- Section Parcours -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Parcours</h2>

    <!-- Formulaire de recherche pour les expériences -->
    <form method="POST" class="mb-4 flex justify-end">
        <input type="text" name="search_experiences" placeholder="Rechercher..." class="px-4 py-2 border border-gray-300 rounded-md w-1/4">
        <button type="submit" name="search_button_experiences" class="bg-blue-500 text-white font-bold py-2 px-4 rounded ml-2">Rechercher</button>
    </form>

    <?php
        $searchTerm = isset($_POST['search_experiences']) ? $_POST['search_experiences'] : '';
        $filteredExperiences = rechercher($connexion_bdd, 'experience', ['occupation', 'lieu'], $searchTerm);
        afficherTableau(
            $filteredExperiences,
            ['occupation', 'lieu', 'dateD', 'dateF'],
            ['Occupation', 'Lieu', 'Date début', 'Date fin'],
            'commandes/experiences/modifier_exp.php?id=',
            'commandes/experiences/supprimer_exp.php?id='
        );
    ?>
    <a href="commandes/experiences/ajouter_exp.php" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
</div>

<!-- Section Upload CV -->
<div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Ajouter / Modifier le CV</h2>
        <?php
        require_once("commandes/upload_cv.php");
        if (isset($_POST['upload_cv'])) {
            echo uploadCV($_FILES['cv']);
        }
        ?>


    
</div>

</body>
</html>
