<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);

    $creer = "INSERT INTO categories (nom) VALUES (?)";
    if ($stmt = mysqli_prepare($connexion_bdd, $creer)) {
        mysqli_stmt_bind_param($stmt, "s", $nom);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../../index.php"); // Redirection après ajout
            exit;
        } else {
            echo "Erreur lors de l'ajout : " . mysqli_error($connexion_bdd);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur de préparation : " . mysqli_error($connexion_bdd);
    }
}
mysqli_close($connexion_bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une catégorie</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<form action="ajouter_categorie.php" method="POST" class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">Ajouter une catégorie</h2>

    <label for="nom" class="block mb-2 font-semibold">Nom de la catégorie :</label>
    <input type="text" id="nom" name="nom" required class="w-full border p-2 mb-4 rounded">

    <!-- Champ vidéo conditionnel -->
    <div id="video-link-container" class="hidden">
        <label for="video-link" class="block mb-2 font-semibold">Lien de la vidéo (YouTube) :</label>
        <input type="url" id="video-link" name="video-link" class="w-full border p-2 mb-4 rounded" placeholder="https://www.youtube.com/watch?v=XXXXX">
    </div>

    <div class="flex justify-between">
        <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Retour</a>
        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Ajouter</button>
    </div>
</form>

<script>
    // Affiche ou cache le champ de vidéo selon la catégorie choisie
    document.getElementById('nom').addEventListener('change', function() {
        var categoryName = this.value.toLowerCase();
        var videoLinkContainer = document.getElementById('video-link-container');
        
        if (categoryName.includes('vidéo')) {  // Si la catégorie contient "vidéo"
            videoLinkContainer.classList.remove('hidden');
        } else {
            videoLinkContainer.classList.add('hidden');
        }
    });
</script>
</body>
</html>

