<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);

    $requete_affiche = "SELECT * FROM competences WHERE id = $id";
    $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);
    $competence = mysqli_fetch_assoc($resultat_affiche);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);
        $text = mysqli_real_escape_string($connexion_bdd, $_POST['text']);
        $picto = mysqli_real_escape_string($connexion_bdd, $_POST['picto']);

        $modifier = "UPDATE competences SET nom = '$nom', text = '$text', picto = '$picto' WHERE id = $id";
        if (mysqli_query($connexion_bdd, $modifier)) {
            header("Location: ../../index.php");
            exit;
        } else {
            echo "Erreur : " . mysqli_error($connexion_bdd);
        }
    }
} else {
    die("ID manquant pour la modification.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une compétence</title>
    <!-- Intégration de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <h1 class="text-3xl font-semibold text-center mb-8">Modifier la compétence</h1>

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <form method="POST">
            <div class="mb-4">
                <label for="nom" class="block text-lg font-medium text-gray-700">Nom :</label>
                <input type="text" name="nom" value="<?= htmlentities($competence['nom']) ?>" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="text" class="block text-lg font-medium text-gray-700">Description :</label>
                <textarea name="text" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlentities($competence['text']) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="picto" class="block text-lg font-medium text-gray-700">Icône :</label>
                <input type="text" name="picto" value="<?= htmlentities($competence['picto']) ?>" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Modifier</button>
            </div>
        </form>
    </div>

</body>

</html>
