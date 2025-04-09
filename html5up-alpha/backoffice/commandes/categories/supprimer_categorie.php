<?php
require_once('../../connexions_et_id/connexionBDD.php');

if (!isset($_GET['id'])) {
    echo "ID manquant.";
    exit;
}

$id = intval($_GET['id']);

// Supprimer d'abord tous les projets associés à cette catégorie
$sql_projets = "DELETE FROM projets WHERE idCategorie = ?";
if ($stmt_projets = mysqli_prepare($connexion_bdd, $sql_projets)) {
    mysqli_stmt_bind_param($stmt_projets, "i", $id);
    mysqli_stmt_execute($stmt_projets);
    mysqli_stmt_close($stmt_projets);
}

// Maintenant, supprimer la catégorie
$sql = "DELETE FROM categories WHERE id = ?";
if ($stmt = mysqli_prepare($connexion_bdd, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../../index.php");
        exit;
    } else {
        echo "Erreur lors de la suppression de la catégorie : " . mysqli_error($connexion_bdd);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Erreur de préparation : " . mysqli_error($connexion_bdd);
}

mysqli_close($connexion_bdd);
?>
