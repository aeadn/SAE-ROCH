<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');
require_once('../recuperer_donnees.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);
    $texte = mysqli_real_escape_string($connexion_bdd, $_POST['texte']);

    $creer = "INSERT INTO competences (nom, texte) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($connexion_bdd, $creer)) {
        mysqli_stmt_bind_param($stmt, "ss", $nom, $texte);
        if (mysqli_stmt_execute($stmt)) {
            // Rediriger vers l'index après l'ajout de la compétence
            header("Location: ../../index.php");
            exit;
        } else {
            echo "Erreur lors de l'ajout : " . mysqli_error($connexion_bdd);
            exit;
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une compétence</title>
    <!-- Inclure le CDN de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.1.8/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <div class="container mx-auto p-6">
    <form action="ajouter_competence.php" method="POST" class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Ajouter une compétence</h1>

        <label for="nom" class="block text-gray-700 mb-2">Nom :</label>
        <input type="text" id="nom" name="nom" required class="w-full p-3 border border-gray-300 rounded-md mb-4">

        <label for="texte" class="block text-gray-700 mb-2">Description :</label>
        <textarea id="texte" name="texte" required class="w-full p-3 border border-gray-300 rounded-md mb-4"></textarea>

        <div class="flex justify-between">
            <button type="button" onclick="window.location.href='../../index.php'" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">Retour</button>
            <input type="submit" value="Ajouter" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
        </div>
    </form>

    </div>

</body>
</html>
