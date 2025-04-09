<?php
// Connexion à la base de données
require_once(__DIR__ . "/../connexions_et_id/connexionBDD.php");

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_cv = $_GET['id']; // ID du CV

    // Récupérer le chemin du fichier depuis la base de données
    $sql = "SELECT fichier, nom_fichier FROM cv WHERE id = $id_cv";
    $result = mysqli_query($connexion_bdd, $sql);

    if (mysqli_stmt_fetch($stmt)) {
        // Vérifier que le fichier existe
        $file_path = __DIR__ . "/../" . $chemin_fichier; // Concaténer avec le chemin de base
        
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
        }
    } else {
        echo "<p>CV non trouvé.</p>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<p>ID manquant dans l'URL.</p>";
}

mysqli_close($connexion_bdd);
?>
