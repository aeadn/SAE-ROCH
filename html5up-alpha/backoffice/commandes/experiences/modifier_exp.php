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
    $occupation = mysqli_real_escape_string($connexion_bdd, $_POST['occupation']);
    $lieu = mysqli_real_escape_string($connexion_bdd, $_POST['lieu']);
    $dateD = mysqli_real_escape_string($connexion_bdd, $_POST['dateD']);
    $dateF = mysqli_real_escape_string($connexion_bdd, $_POST['dateF']);

    $update = "UPDATE experience SET occupation = ?, lieu = ?, dateD = ?, dateF = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($connexion_bdd, $update)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $occupation, $lieu, $dateD, $dateF, $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../../index.php");
            exit;
        } else {
            echo "Erreur lors de la modification : " . mysqli_error($connexion_bdd);
        }
        mysqli_stmt_close($stmt);
    }
}

// Récupérer les données actuelles
$req = "SELECT * FROM experience WHERE id = $id";
$res = mysqli_query($connexion_bdd, $req);
$exp = mysqli_fetch_assoc($res);
if (!$exp) {
    echo "Expérience introuvable.";
    exit;
}
mysqli_close($connexion_bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une expérience</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <form method="POST" class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-center">Modifier l'expérience</h2>

        <label for="occupation" class="block mb-2 font-semibold">Occupation :</label>
        <input type="text" id="occupation" name="occupation" value="<?= htmlspecialchars($exp['occupation']) ?>" required class="w-full border p-2 mb-4 rounded">

        <label for="lieu" class="block mb-2 font-semibold">Lieu :</label>
        <textarea id="lieu" name="lieu" required class="w-full border p-2 mb-4 rounded"><?= htmlspecialchars($exp['lieu']) ?></textarea>

        <label for="dateD" class="block mb-2 font-semibold">Date de début :</label>
        <input type="date" id="dateD" name="dateD" value="<?= $exp['dateD'] ?>" required class="w-full border p-2 mb-4 rounded">

        <label for="dateF" class="block mb-2 font-semibold">Date de fin :</label>
        <input type="date" id="dateF" name="dateF" value="<?= $exp['dateF'] ?>" required class="w-full border p-2 mb-4 rounded">

        <div class="flex justify-between">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</body>
</html>
