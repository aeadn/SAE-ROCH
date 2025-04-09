<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php'); // Connexion à la base
require_once('../recuperer_donnees.php'); // Pour récupérer les catégories
$categories = recupererDonnees($connexion_bdd, "SELECT * FROM categories;");

// Vérifier si un ID est présent
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID du projet invalide.");
}

$id = (int)$_GET['id']; // Sécurisation de l'ID

// Récupérer les informations actuelles du projet
$requete = "SELECT p.titre, p.texte, p.img, p.idCategorie, c.nom AS categorie, p.lien FROM projets p
            JOIN categories c ON p.idCategorie = c.id WHERE p.id = ?";
$modif = mysqli_prepare($connexion_bdd, $requete);
mysqli_stmt_bind_param($modif, "i", $id);
mysqli_stmt_execute($modif);
mysqli_stmt_bind_result($modif, $titre, $texte, $img, $idCategorie, $categorie, $lien);
mysqli_stmt_fetch($modif);
mysqli_stmt_close($modif);

// Vérifiez que toutes les variables sont bien définies
$titre = isset($titre) ? $titre : ''; 
$texte = isset($texte) ? $texte : ''; 
$img = isset($img) ? $img : ''; 
$categorie = isset($categorie) ? $categorie : '';
$lien = isset($lien) ? $lien : ''; // Initialiser $lien si non défini

// Traitement du formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre2 = $_POST['titre'];
    $texte2 = $_POST['texte'];
    $image2 = $img; // Garder l'image actuelle par défaut
    $lien2 = isset($_POST['lien']) ? mysqli_real_escape_string($connexion_bdd, $_POST['lien']) : null;
    $categorie2 = $_POST['categorie']; // Récupérer la catégorie sélectionnée

    // Vérifier si une nouvelle image est uploadée
    if (isset($_FILES["img"]) && $_FILES["img"]["error"] === 0) {
        $chemin = $_FILES["img"]["tmp_name"];
        $ext = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
        $fichier = uniqid("projet_", true) . "." . $ext;
        $destination = "../../images/" . $fichier;

        // Déplacer la nouvelle image
        if (move_uploaded_file($chemin, $destination)) {
            $image2 = "images/" . $fichier; // Chemin relatif

            // Supprimer l'ancienne image si elle existe
            if ($img && file_exists("../../" . $img)) {
                unlink("../../" . $img);
            }
        } else {
            echo "Erreur lors de l'upload de la nouvelle image.";
        }
    }

    // Mettre à jour en base (inclure la mise à jour de la catégorie)
    $requete2 = "UPDATE projets SET titre = ?, texte = ?, img = ?, lien = ?, idCategorie = (SELECT id FROM categories WHERE nom = ?) WHERE id = ?";
    $modif2 = mysqli_prepare($connexion_bdd, $requete2);
    mysqli_stmt_bind_param($modif2, "sssssi", $titre2, $texte2, $image2, $lien2, $categorie2, $id);

    if (mysqli_stmt_execute($modif2)) {
        echo "Le projet a été mis à jour.";
        header("Location: ../../index.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour : " . mysqli_error($connexion_bdd);
    }

    mysqli_stmt_close($modif2);
}
mysqli_close($connexion_bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Formulaire de modification -->
<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg mt-12 mx-auto">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Modifier le projet</h2>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="titre" class="block text-sm font-medium text-gray-700">Titre :</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre ?? ''); ?>" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="texte" class="block text-sm font-medium text-gray-700">Description :</label>
            <textarea id="texte" name="texte" required 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($texte ?? ''); ?></textarea>
        </div>

        <div>
            <label for="categorie" class="block text-sm font-medium text-gray-700 mt-4">Catégorie</label>
            <select id="categorie" name="categorie" required 
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Choisir une catégorie</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['nom']); ?>" 
                        <?= ($cat['nom'] == $categorie) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Image actuelle :</label>
            <?php if ($img): ?>
                <img src="../../<?= $img; ?>" alt="Image du projet" class="w-48 h-48 object-cover mb-4"><br>
            <?php else: ?>
                <p>Aucune image disponible.</p>
            <?php endif; ?>
        </div>

        <div>
            <label for="img" class="block text-sm font-medium text-gray-700">Nouvelle image (facultatif) :</label>
            <input type="file" id="img" name="img" accept="image/*" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Afficher le champ Lien uniquement si la catégorie est "Video" -->
        <?php if ($categorie === "Video"): ?>
            <div>
                <label for="lien" class="block text-sm font-medium text-gray-700">Lien vidéo :</label>
                <input type="text" id="lien" name="lien" value="<?= htmlspecialchars($lien ?? ''); ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        <?php endif; ?>

        <div class="flex justify-between">
            <!-- Bouton annuler -->
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Annuler</a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Mettre à jour</button>
        </div>
    </form>
</div>

</body>
</html>
