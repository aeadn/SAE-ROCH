<?php
require_once('../../header_backoffice.php');  // Inclure le header
require_once('../../connexions_et_id/connexionBDD.php');  // Connexion à la base de données

// Initialiser la variable $message
$message = "";

// Vérifier si l'ID est passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);  // Sécuriser l'ID passé en GET

    // Récupérer les données du réseau social à modifier
    $requete_affiche = "SELECT * FROM social_media WHERE id = $id"; // Récupérer les données de l'adhérent qu'on a cliqué
    $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);
    $entite = mysqli_fetch_assoc($resultat_affiche);

    // Vérifier si le formulaire a été soumis pour modifier les données
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);
        $icone = mysqli_real_escape_string($connexion_bdd, $_POST['icone']);
        $lien = mysqli_real_escape_string($connexion_bdd, $_POST['lien']);

        // Mise à jour des données dans la base
        $modifier = "UPDATE social_media SET nom = '$nom', icone = '$icone', lien = '$lien' WHERE id = $id";
        
        if (mysqli_query($connexion_bdd, $modifier)) {
            header("Location: ../../index.php");   // Redirection vers index.php après la mise à jour
            exit; // Terminer l'exécution du script pour éviter les bugs
        } else {
            $message = "Erreur : " . mysqli_error($connexion_bdd);
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
    <title>Modification du réseau social</title>
    <!-- Inclusion de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<?php require_once('../../header_backoffice.php'); ?>

<!-- Formulaire de modification avec design élégant -->
<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg mt-12 mx-auto">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Modifier un réseau social</h2>

    <!-- Affichage du message d'erreur ou de succès -->
    <?php if ($message): ?>
        <p class="text-red-600 text-center mb-4"><?= $message ?></p>
    <?php endif; ?>

    <!-- Formulaire de modification -->
    <form method="POST" class="space-y-4">
        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= htmlentities($entite['nom']) ?>" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="icone" class="block text-sm font-medium text-gray-700">Icône :</label>
            <input type="text" id="icone" name="icone" value="<?= htmlentities($entite['icone']) ?>" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="lien" class="block text-sm font-medium text-gray-700">Lien :</label>
            <input type="text" id="lien" name="lien" value="<?= htmlentities($entite['lien']) ?>" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-between">
            <!-- Bouton Retour -->
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Retour</a>
            <!-- Bouton Modifier -->
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Modifier</button>
        </div>
    </form>
</div>

</body>
</html>
