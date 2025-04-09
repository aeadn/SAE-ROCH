ajouter_image.php : <?php
require_once('../../connexions_et_id/connexionBDD.php'); // Connexion à la base

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = mysqli_real_escape_string($connexion_bdd, $_POST['titre']);
    $texte = mysqli_real_escape_string($connexion_bdd, $_POST['texte']);

    // Vérifier si une image a été téléchargée
    if (isset($_FILES["img"]) && $_FILES["img"]["error"] === 0) {
        $fileTmpPath = $_FILES["img"]["tmp_name"];
        $fileExtension = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid("projet_", true) . "." . $fileExtension;
        $chemin = __DIR__ . "../../../uploads/images_upload/" . $fileName;

        // Déplacer l'image vers le dossier final
        if (move_uploaded_file($fileTmpPath, $chemin)) {
            $cheminImage = "images_upload/" . $fileName;

            // Insérer les données en base
            $query = "INSERT INTO projets (titre, texte, img, idCategorie) VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($connexion_bdd, $query)) {
                mysqli_stmt_bind_param($stmt, "sss", $titre, $texte, $cheminImage, $idCategorie);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../../index.php"); // Redirection après ajout
                    exit;
                } else {
                    echo "Erreur lors de l'ajout : " . mysqli_error($connexion_bdd);
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            echo "Erreur lors de l'upload de l'image.";
        }
    } else {
        echo "Veuillez sélectionner une image.";
    }
}

mysqli_close($connexion_bdd);
?>
