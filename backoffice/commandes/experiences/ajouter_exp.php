<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $occupation = mysqli_real_escape_string($connexion_bdd, $_POST['occupation']);
    $lieu = mysqli_real_escape_string($connexion_bdd, $_POST['lieu']);
    $dateD = mysqli_real_escape_string($connexion_bdd, $_POST['dateD']);
    $dateF = mysqli_real_escape_string($connexion_bdd, $_POST['dateF']);

    $creer = "INSERT INTO experience (occupation, lieu, dateD, dateF) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($connexion_bdd, $creer)) {
        mysqli_stmt_bind_param($stmt, "ssss", $occupation, $lieu, $dateD, $dateF);
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
    <title>Ajouter une expérience</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <form action="ajouter_exp.php" method="POST" class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-center">Ajouter une expérience</h2>

        <label for="occupation" class="block mb-2 font-semibold">Occupation :</label>
        <input type="text" id="occupation" name="occupation" required class="w-full border p-2 mb-4 rounded">

        <label for="lieu" class="block mb-2 font-semibold">Lieu :</label>
        <textarea id="lieu" name="lieu" required class="w-full border p-2 mb-4 rounded"></textarea>

        <label for="dateD" class="block mb-2 font-semibold">Date de début :</label>
        <input type="date" id="dateD" name="dateD" required class="w-full border p-2 mb-4 rounded">

        <label for="dateF" class="block mb-2 font-semibold">Date de fin :</label>
        <input type="date" id="dateF" name="dateF" required class="w-full border p-2 mb-4 rounded">

        <div class="flex justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</button>
            <a href="../../index.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Retour</a>
        </div>
    </form>
</body>
</html>
