<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification</title>
</head>
<?php
    require_once('../header_backoffice.php');// insertion du header
    require_once('../connexions_et_id/connexionBDD.php');// inserer la connexion
// Vérifier si l'ID est passé en paramètre dans l'URL
    $message = "";
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);  // Sécuriser l'ID passé en GET

        $requete_affiche = "SELECT * FROM social_media WHERE id = $id"; // Récupérer les données de l'adhérent qu'on a cliqué
        $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);
        $entite = mysqli_fetch_assoc($resultat_affiche);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = mysqli_real_escape_string($connexion_bdd, $_POST['nom']);
            $icone = mysqli_real_escape_string($connexion_bdd, $_POST['icone']);
            $lien = mysqli_real_escape_string($connexion_bdd, $_POST['lien']);
            $modifier = "UPDATE social_media SET nom = '$nom', icone = '$icone', lien = '$lien' WHERE id = $id"; // Mise à jour
            if (mysqli_query($connexion_bdd, $modifier)) {
                header("Location: ../admin.php");   // Redirection vers index.php après la mise à jour
                exit; // Terminer l'exécution du script ça évite les bugs
            } else {
                $message = "Erreur : " . mysqli_error($connexion_bdd);
            }
        }
    } else {
        die("ID manquant pour la modification.");
    }
?>
<body>
    <h1>Modifier un adhérent</h1>
    <form method="POST">
        <label>Nom : <input type="text" name="nom" value="<?= htmlentities($entite['nom']) ?>" required></label><br>
        <label>Icone : <input type="text" name="icone" value="<?= htmlentities($entite['icone']) ?>" required></label><br>
        <label>Lien : <input type="text" name="lien" value="<?= htmlentities($entite['lien']) ?>" required></label><br>
        <button type="submit">Modifier</button>
    </form>
    <p><?= $message ?></p>
</body>
</html>
