<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);

    $requete_affiche = "SELECT * FROM competences WHERE id = $id";
    $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);
    $competence = mysqli_fetch_assoc($resultat_affiche);
    
    if (!$competence) {
        die("Compétence non trouvée.");
    }
} else {
    die("Aucune compétence fournie.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Supprimer'])) {
    $id_supp = mysqli_real_escape_string($connexion_bdd, $_POST['id_a_supp']);

    $supprimer = "DELETE FROM competences WHERE id = $id_supp";
    if (mysqli_query($connexion_bdd, $supprimer)) {
        header("Location: ../../index.php");
        exit;
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($connexion_bdd);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer une compétence</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.1.8/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <!-- Contenu centré sans inclure le header -->
    <div class="flex items-center justify-center min-h-screen pt-24"> <!-- pt-24 pour laisser de l’espace sous le header -->
        <div class="bg-white shadow-md rounded-lg p-8 max-w-xl w-full text-center">
            <h1 class="text-2xl font-bold mb-4">Supprimer une compétence</h1>
            <p class="mb-6">Voulez-vous vraiment supprimer la compétence <strong><?= htmlspecialchars($competence['nom']) ?></strong> ?</p>

            <form method="POST">
                <input type="hidden" name="id_a_supp" value="<?= $competence['id'] ?>">
                <div class="flex justify-center gap-4">
                    <a href="../../index.php" class="px-6 py-3 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">Non</a>
                    <button type="submit" name="Supprimer" class="px-6 py-3 bg-red-500 text-white rounded hover:bg-red-600 transition">Oui</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
