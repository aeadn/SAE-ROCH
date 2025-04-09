<?php
require_once('../../header_backoffice.php');  // Inclure le header
require_once('../../connexions_et_id/connexionBDD.php');  // Connexion à la base de données

// Vérifier si l'ID est passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);  // Sécuriser l'ID passé en GET

    // Récupérer les informations du réseau social
    $requete_affiche = "SELECT * FROM social_media WHERE id = $id";
    $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);

    // Si le réseau social existe, on l'affiche
    if ($resultat_affiche && mysqli_num_rows($resultat_affiche) > 0) {
        $social_media = mysqli_fetch_assoc($resultat_affiche);
    } else {
        die("Erreur : ce réseau social n'existe pas.");
    }
} else {
    die("Aucun réseau social fourni.");
}

// Traitement de la suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Supprimer'])) {
    // Récupérer l'ID du réseau social à supprimer depuis le formulaire
    $id_supp = mysqli_real_escape_string($connexion_bdd, $_POST['id_a_supp']);
    
    // Préparer la requête SQL pour supprimer le réseau social
    $supprimer = "DELETE FROM social_media WHERE id = $id_supp";

    // Exécuter la requête SQL
    if (mysqli_query($connexion_bdd, $supprimer)) {
        // Si la suppression réussit, rediriger vers la liste des réseaux sociaux (ou page d'accueil)
        header("Location: ../../index.php");  // Redirigez vers la page souhaitée
        exit();
    } else {
        // Si une erreur survient lors de l'exécution
        echo "Erreur lors de la suppression : " . mysqli_error($connexion_bdd);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression du réseau social</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<?php require_once('../../header_backoffice.php'); ?>

<!-- Formulaire de suppression avec un espacement au-dessus pour ne pas être pris dans le header -->
<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg mt-12 mx-auto">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Suppression du réseau social</h2>

    <p class="text-center text-lg text-gray-700 mb-6">Voulez-vous vraiment supprimer le réseau social : <?php echo htmlspecialchars($social_media["nom"]); ?> ?</p>

    <form method="POST" action="" class="space-y-4">
        <input type="hidden" name="id_a_supp" value="<?php echo $social_media['id']; ?>">  <!-- Champ caché avec l'ID du réseau social -->

        <div class="flex justify-between">
            <!-- Bouton Retour -->
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Non, retourner au tableau</a>
            <!-- Bouton Ajouter -->
            <button type="submit" name="Supprimer" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Oui</button>
        </div>
    </form>
</div>

</body>
</html>
