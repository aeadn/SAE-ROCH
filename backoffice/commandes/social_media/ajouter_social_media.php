<?php
// Inclusion de la connexion à la base de données
require_once('../../connexions_et_id/connexionBDD.php');
require_once('../../header_backoffice.php'); // insertion du header

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);
    $icone = mysqli_real_escape_string($connexion_bdd, $_POST['icone']);
    $lien = mysqli_real_escape_string($connexion_bdd, $_POST['lien']);
    
    // Préparer la requête d'insertion dans la base de données
    $requete = "INSERT INTO social_media (nom, icone, lien) VALUES ('$nom', '$icone', '$lien')";
    
    // Exécuter la requête
    if (mysqli_query($connexion_bdd, $requete)) {
        // Redirection vers la page d'administration (ou autre page après succès)
        header("Location: ../../index.php");  // Redirige vers la page d'accueil
        exit;
    } else {
        // En cas d'erreur lors de l'insertion
        echo "Erreur lors de l'ajout du réseau social : " . mysqli_error($connexion_bdd);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un réseau social</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Formulaire d'ajout, centré avec une largeur fixe -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-lg">
            <form method="POST" action="ajouter_social_media.php" class="space-y-6">
                <h3 class="text-2xl font-semibold text-center text-gray-800 mb-6">Ajouter un réseau social</h3>

                <!-- Champ Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom :</label>
                    <input type="text" id="nom" name="nom" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Champ Icône -->
                <div>
                    <label for="icone" class="block text-sm font-medium text-gray-700">Icône :</label>
                    <input type="text" id="icone" name="icone" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Champ Lien -->
                <div>
                    <label for="lien" class="block text-sm font-medium text-gray-700">Lien :</label>
                    <input type="text" id="lien" name="lien" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Boutons -->
                <div class="flex justify-between">
                    <!-- Bouton Retour -->
                    <button type="button" onclick="window.location.href='../../index.php'" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Retour</button>
                    <!-- Bouton Ajouter -->
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
