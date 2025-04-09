<?php
require_once('../../header_backoffice.php');  // Inclure le header
require_once('../../connexions_et_id/connexionBDD.php');  // Connexion à la base de données

// Vérifier si l'ID est passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);  // Sécuriser l'ID passé en GET

    // Récupérer les informations du projet
    $requete_affiche = "SELECT * FROM projets WHERE id = $id";
    $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);

    // Si le projet existe, on l'affiche
    if ($resultat_affiche) {
        $projet = mysqli_fetch_assoc($resultat_affiche);
    } else {
        die("Erreur : ce projet n'existe pas.");
    }
} else {
    die("Aucun projet fourni.");
}

// Traitement de la suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Supprimer'])) {
    // Récupérer l'ID du projet à supprimer depuis le formulaire
    $id_supp = mysqli_real_escape_string($connexion_bdd, $_POST['id_a_supp']);
    
    // Préparer la requête SQL pour supprimer le projet
    $supprimer = "DELETE FROM projets WHERE id = $id_supp";

    // Exécuter la requête SQL
    if (mysqli_query($connexion_bdd, $supprimer)) {
        // Si la suppression réussit, rediriger vers la liste des projets (ou page d'accueil)
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
    <title>Suppression du projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Formulaire de suppression -->
<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg mt-12 mx-auto">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Suppression du projet</h2>

    <p class="text-lg text-gray-700 mb-4">Voulez-vous vraiment supprimer le projet : <?php echo htmlspecialchars($projet["titre"]); ?> ?</p>

    <form method="POST" action="">
        <input type="hidden" name="id_a_supp" value="<?php echo $projet['id']; ?>">

        <div class="flex justify-between">
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Non, retourner au tableau</a>
            <button type="submit" name="Supprimer" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Oui, supprimer</button>
        </div>
    </form>
</div>

</body>
</html>
