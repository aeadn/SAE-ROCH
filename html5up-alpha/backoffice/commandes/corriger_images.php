<?php
require_once('../../connexions_et_id/connexionBDD.php'); // Adapte le chemin si besoin

$sql = "SELECT id, img FROM projets";
$result = mysqli_query($connexion_bdd, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $img = $row['img'];
        $filename = basename($img); // enlève tout chemin

        if ($img !== $filename) {
            $update = "UPDATE projets SET img = ? WHERE id = ?";
            $stmt = mysqli_prepare($connexion_bdd, $update);
            mysqli_stmt_bind_param($stmt, "si", $filename, $id);
            mysqli_stmt_execute($stmt);

            echo "Corrigé pour ID {$id} : {$img} => {$filename}<br>";
        }
    }
    echo "<br>✅ Correction terminée.";
} else {
    echo "Erreur de requête : " . mysqli_error($connexion_bdd);
}

mysqli_close($connexion_bdd);
?>
