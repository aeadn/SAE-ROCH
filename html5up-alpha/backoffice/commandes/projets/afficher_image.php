<?php
require_once('../../connexions_et_id/connexionBDD.php');

$id = $_GET['id']; // Récupérer l'ID du projet

$query = "SELECT img FROM projets WHERE id = ?";
$stmt = mysqli_prepare($connexion_bdd, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $cheminImage);
mysqli_stmt_fetch();
mysqli_stmt_close($stmt);
mysqli_close($connexion_bdd);

// Vérifier si l'image existe
$imagePath = "../../uploads/images_upload/" . $cheminImage;
if (!empty($cheminImage) && file_exists($imagePath)) {
    // Vérifier le type MIME de l'image
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE); 
    $mimeType = finfo_file($fileInfo, $imagePath);
    finfo_close($fileInfo);

    if (strpos($mimeType, 'image/') === 0) {  // Vérifie que c'est bien une image
        header("Content-Type: $mimeType"); // Envoie le bon type MIME
        readfile($imagePath);
    } else {
        echo "Ce n'est pas une image valide.";
    }
} else {
    echo "Image non trouvée.";
}
?>
