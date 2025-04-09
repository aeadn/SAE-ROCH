<?php
// Connexion à la base de données
require_once("backoffice/connexions_et_id/connexionBDD.php");

// Vérifier si un ID est passé dans l'URL, sinon utiliser 1 par défaut
$id_cv = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : 1;

// Récupérer le chemin du fichier depuis la base de données
$sql = "SELECT fichier FROM cv WHERE id = ?";
$stmt = mysqli_prepare($connexion_bdd, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_cv);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $chemin_fichier);

if (mysqli_stmt_fetch($stmt)) {
    // Vérifier si le chemin contient déjà "uploads/cv/" pour éviter une duplication
    if (strpos($chemin_fichier, 'uploads/cv/') === 0) {
        $chemin_fichier = substr($chemin_fichier, strlen('uploads/cv/'));
    }

    // Spécifier le chemin absolu complet du fichier
    $file_path = "/home/baudnazebi/www/S4/SAE401/html5up-alpha/backoffice/" . $chemin_fichier;

    // Vérifier que le fichier existe
    if (file_exists($file_path)) {
        // Définir les en-têtes pour forcer le téléchargement du fichier
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Pragma: no-cache');
        header('Expires: 0');
        flush(); // Nettoyer la sortie du tampon

        // Lire le fichier et l'envoyer au navigateur
        readfile($file_path);
        exit; // Arrêter l'exécution après le téléchargement
    } else {
        echo "<p>Le fichier n'existe pas.</p>";
        echo "<p>Chemin recherché : $file_path</p>";
        exit;
    }
} else {
    echo "<p>CV non trouvé.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($connexion_bdd);
?>
