<?php

require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');

if (!isset($_GET['id'])) {
    echo "ID manquant.";
    exit;
}

$id = intval($_GET['id']);

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);
    $prenom = mysqli_real_escape_string($connexion_bdd, $_POST['prenom']);
    $statut = mysqli_real_escape_string($connexion_bdd, $_POST['statut']);
    $intro = mysqli_real_escape_string($connexion_bdd, $_POST['intro']);
    
    // Gestion de l'image (si une nouvelle image est téléchargée)
    if ($_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo'];
        $photo_name = basename($photo['name']);
        $photo_tmp_name = $photo['tmp_name'];
        $photo_target_dir = '../../uploads/images_upload/';
        $photo_target_file = $photo_target_dir . $photo_name;

        // Vérifier si l'image est valide
        if (move_uploaded_file($photo_tmp_name, $photo_target_file)) {
            $update = "UPDATE info SET nom = ?, prenom = ?, statut = ?, intro = ?, photo = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($connexion_bdd, $update)) {
                mysqli_stmt_bind_param($stmt, "sssssi", $nom, $prenom, $statut, $intro, $photo_name, $id);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../../index.php");
                    exit;
                } else {
                    echo "Erreur lors de la modification : " . mysqli_error($connexion_bdd);
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            echo "Erreur lors de l'upload de l'image.";
        }
    } else {
        // Si aucune nouvelle image n'est téléchargée, mettre à jour sans l'image
        $update = "UPDATE info SET nom = ?, prenom = ?, statut = ?, intro = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($connexion_bdd, $update)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $nom, $prenom, $statut, $intro, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../../index.php");
                exit;
            } else {
                echo "Erreur lors de la modification : " . mysqli_error($connexion_bdd);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Récupérer les données actuelles
$req = "SELECT * FROM info WHERE id = $id";
$res = mysqli_query($connexion_bdd, $req);
$info = mysqli_fetch_assoc($res);
if (!$info) {
    echo "Informations introuvables.";
    exit;
}
mysqli_close($connexion_bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier les informations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    header {
        margin: 0;
        padding: 0;
    }
</style>

</head>
<body class="bg-gray-100 p-6">
    <form method="POST" enctype="multipart/form-data" class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-center">Modifier les informations</h2>

        <label for="nom" class="block mb-2 font-semibold">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($info['nom']) ?>" required class="w-full border p-2 mb-4 rounded">

        <label for="prenom" class="block mb-2 font-semibold">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($info['prenom']) ?>" required class="w-full border p-2 mb-4 rounded">

        <label for="statut" class="block mb-2 font-semibold">Statut :</label>
        <input type="text" id="statut" name="statut" value="<?= htmlspecialchars($info['statut']) ?>" required class="w-full border p-2 mb-4 rounded">

        <label for="intro" class="block mb-2 font-semibold">Introduction :</label>
        <textarea id="intro" name="intro" required class="w-full border p-2 mb-4 rounded"><?= htmlspecialchars($info['intro']) ?></textarea>

        <label for="photo" class="block mb-2 font-semibold">Photo (laisser vide pour ne pas changer) :</label>
        <input type="file" id="photo" name="photo" class="w-full border p-2 mb-4 rounded">
        <?php if ($info['photo']): ?>
            <p class="text-sm text-gray-500">Photo actuelle :</p>
            <img src="../../uploads/images_upload/<?= htmlspecialchars($info['photo']) ?>" alt="photo" class="w-20 h-auto rounded shadow mb-4">
        <?php endif; ?>

        <div class="flex justify-between">
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Annuler</a>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
        </div>
    </form>
</body>
</html> 
