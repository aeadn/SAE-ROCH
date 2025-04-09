<?php
// Connexion à la base de données
require_once('/home/baudnazebi/www/S4/SAE401/html5up-alpha/backoffice/connexions_et_id/connexionBDD.php');

// Traitement de la recherche
$results = [];
$search = ''; // Définir une valeur par défaut pour $search

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search = trim($_GET['query']);  // Nettoyage de la requête utilisateur
    
    // Remplace `articles` par le nom de ta table et `nom` et `titre` par les colonnes que tu veux rechercher
    // Requête SQL avec paramètres
    $stmt = $connexionBDD->prepare("SELECT * FROM articles WHERE nom LIKE ? OR titre LIKE ?");
    // Ajout de "%" pour effectuer la recherche "contains"
    $searchTerm = "%$search%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm); // Lier les paramètres (les deux sont de type string)
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupérer les résultats
    $resultSet = $stmt->get_result(); // Utilisation de get_result pour récupérer les données
    $results = $resultSet->fetch_all(MYSQLI_ASSOC); // Récupérer toutes les lignes en tant que tableau associatif
}

// Colonnes à afficher dans le tableau
$colonnes = ['Nom', 'Titre', 'Description']; // Modifie cela en fonction de ta table

// Lien pour modifier et supprimer
$modifierLien = '/backoffice/commandes/modifier.php?id=';
$supprimerLien = '/backoffice/commandes/supprimer.php?id=';

// Affichage des résultats de la recherche
if (!empty($results)) {
    afficherTableau($results, $colonnes, $modifierLien, $supprimerLien);
} else {
    echo "<p>Aucun résultat trouvé pour '<strong>" . htmlspecialchars($search) . "</strong>'.</p>";
}
?>
