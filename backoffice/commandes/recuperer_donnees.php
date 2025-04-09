<?php 

// Fonction pour récupérer les données de la base de données
function recupererDonnees($connexion_bdd, $requete) {
    $resultat = mysqli_query($connexion_bdd, $requete);
    if ($resultat && mysqli_num_rows($resultat) > 0) {
        return mysqli_fetch_all($resultat, MYSQLI_ASSOC);
    }
    return [];
}
?>