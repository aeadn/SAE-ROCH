<?php
require_once(__DIR__ . "/../connexions_et_id/connexionBDD.php");

// Récupérer les informations du CV actuel
$sql = "SELECT fichier, nom_fichier FROM cv WHERE id = 1";
$result = mysqli_query($connexion_bdd, $sql);

if (mysqli_num_rows($result) > 0) {
    // Si le CV existe
    $row = mysqli_fetch_assoc($result);
    $current_file = $row['fichier'];
    $current_filename = $row['nom_fichier'];

} else {
    echo "<p>Aucun CV trouvé.</p>";
}

?>

<!-- Formulaire pour uploader un nouveau CV -->
    <div class="text-center mt-8">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <label for="cv">Choisissez un nouveau CV à télécharger :</label>
                    <input type="file" name="cv" id="cv" required>
                    <button type="submit" name="submit" class="px-6 py-3 bg-red-500 text-white font-bold rounded-lg shadow-md hover:bg-red-600 transition">Uploader</button>
                </form>
    </div>
<?php
// Traitement de l'upload
if (isset($_POST['submit'])) {
    $file = $_FILES['cv'];
    uploadCV($file); // Appel de la fonction uploadCV
}

function uploadCV($file)
{
    // Vérifier si un fichier a été soumis
    if (!isset($file) || $file['error'] != 0) {
        return "<p>Veuillez sélectionner un fichier valide.</p>";
    }

    // Définir le dossier où les CV seront stockés
    $dossierCV = __DIR__ . "/../uploads/cv/"; // Crée un dossier "uploads/cv" à la racine
    if (!is_dir($dossierCV)) {
        mkdir($dossierCV, 0777, true); // Si le dossier n'existe pas, on le crée
    }

    // Récupérer les informations sur le fichier
    $fileTmpPath = $file['tmp_name'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); // Récupère l'extension du fichier
    $fileName = uniqid("cv_", true) . "." . $fileExtension; // Génère un nom unique pour le fichier

    // Déplacer le fichier vers le dossier de destination
    $fileDestination = $dossierCV . $fileName;
    if (!move_uploaded_file($fileTmpPath, $fileDestination)) {
        return "<p style='color: red;'>Erreur lors de l'upload du CV.</p>";
    }

    // Connexion à la base de données
    global $connexion_bdd;

    if (!$connexion_bdd) {
        return "<p style='color: red;'>Erreur de connexion : " . mysqli_connect_error() . "</p>";
    }

    // Récupérer les informations à enregistrer
    $nom_fichier = mysqli_real_escape_string($connexion_bdd, $file['name']);
    $chemin_fichier = "backoffice/uploads/cv/" . $fileName; // Chemin relatif pour la base de données

    // Mise à jour du CV existant (id=1)
    $sql = "UPDATE cv SET fichier=?, nom_fichier=? WHERE id=1";

    $stmt = mysqli_prepare($connexion_bdd, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $chemin_fichier, $nom_fichier); // Lier les paramètres

    if (mysqli_stmt_execute($stmt)) {
        $message = "<p style='color: green;'>CV uploadé avec succès !</p>";
    } else {
        $message = "<p style='color: red;'>Erreur lors de l'upload.</p>";
    }

    // Fermeture des connexions
    mysqli_stmt_close($stmt);
    mysqli_close($connexion_bdd);

    return $message;
}
?>
