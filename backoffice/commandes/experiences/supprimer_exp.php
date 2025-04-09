<?php
require_once('../../connexions_et_id/connexionBDD.php');

if (!isset($_GET['id'])) {
    echo "ID manquant.";
    exit;
}

$id = intval($_GET['id']);

// Suppression
$sql = "DELETE FROM experience WHERE id = ?";
if ($stmt = mysqli_prepare($connexion_bdd, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../../index.php");
        exit;
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($connexion_bdd);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Erreur de préparation : " . mysqli_error($connexion_bdd);
}
mysqli_close($connexion_bdd);
?>
