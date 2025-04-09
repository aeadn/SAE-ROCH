<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression</title>
</head>
<?php
    require_once('../header_backoffice.php');// insertion du header
    require_once('../connexions_et_id/connexionBDD.php');// inserer la connexion
// Vérifier si l'ID est passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion_bdd, $_GET['id']);  // Sécuriser l'ID passé en GET

    // Récupérer les informations de l'adhérent
    $requete_affiche = "SELECT * FROM social_media WHERE id = $id";
    $resultat_affiche = mysqli_query($connexion_bdd, $requete_affiche);

    // Si l'adhérent existe, on l'affiche
    if ($resultat_affiche) {
        $entite = mysqli_fetch_assoc($resultat_affiche);
    } else {
        die("Erreur : ce réseau social n'existe pas.");
    }
} else {
    die("Aucun réseau social fourni.");
}

?>

<p>Voulez-vous vraiment supprimer <?php echo $entite["nom"]; ?> ?</p>

<form method="POST" action="">
    <input type="hidden" name="id_a_supp" value="<?php echo $entite['id']; ?>">  <!-- Champ caché avec l'ID de l'adhérent -->
    
    <a href="../admin.php">Non, retourner au tableau</a><!-- Retour à l'index -->
    <button type="submit" name="Supprimer">Oui</button>
</form>

<?php
// Traitement de la suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Supprimer'])) {
    // Récupérer l'ID de l'adhérent à supprimer depuis le formulaire
    $id_supp = mysqli_real_escape_string($connexion_bdd, $_POST['id_a_supp']);
    
    // Préparer la requête SQL pour supprimer l'adhérent
    $supprimer = "DELETE FROM social_media WHERE id = $id_supp";

    // Exécuter la requête SQL
    if (mysqli_query($connexion_bdd, $supprimer)) {
        // Si la suppression réussit, rediriger vers index.php
        header("Location: ../admin.php");
        exit;
    } else {
        // Si une erreur survient lors de l'exécution
        echo "Erreur lors de la suppression : " . mysqli_error($connexion_bdd);
    }
}
