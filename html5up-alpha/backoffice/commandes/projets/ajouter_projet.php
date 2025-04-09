<?php
require_once('../../header_backoffice.php');
require_once('../../connexions_et_id/connexionBDD.php');
require_once('../recuperer_donnees.php');

// Récupérer les catégories de la table 'categories'
$categories = recupererDonnees($connexion_bdd, "SELECT * FROM categories;");

// Fonction pour ajouter un projet
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $texte = $_POST['texte'];
    $categorieNom = $_POST['categorie'];
    
    // Récupérer l'ID de la catégorie basée sur le nom
    $stmt = $connexion_bdd->prepare("SELECT id FROM categories WHERE nom = ?");
    $stmt->bind_param("s", $categorieNom);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $categorie = $result->fetch_assoc()['id'];  // Récupérer l'ID de la catégorie
    } else {
        die("Catégorie non trouvée.");
    }

    
    
    // Si la catégorie est "Vidéo", on prend le lien, sinon on laisse le champ vide.
    $lien = ($categorieNom == 'Video') ? mysqli_real_escape_string($connexion_bdd, $_POST['lien']) : '';

    $image = ''; // Initialisation de la variable image

    // Gestion de l'image (si une nouvelle image est téléchargée)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $photo = $_FILES['image'];
        $photo_tmp_name = $photo['tmp_name'];
        
        // Nettoyer le nom du fichier (remplace les espaces, supprime caractères spéciaux)
        $photo_name_original = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($photo['name']));
        
        $photo_target_dir = '../../../uploads/images_upload/';
        $photo_target_file = $photo_target_dir . $photo_name_original;
    
        // Créer le dossier s'il n'existe pas
        if (!is_dir($photo_target_dir)) {
            mkdir($photo_target_dir, 0755, true);
        }
    
        // Gérer collision de fichiers (ajoute un suffixe si le fichier existe déjà)
        $i = 1;
        $photo_base = pathinfo($photo_name_original, PATHINFO_FILENAME);
        $photo_ext = pathinfo($photo_name_original, PATHINFO_EXTENSION);
    
        while (file_exists($photo_target_file)) {
            $photo_name_original = $photo_base . '_' . $i . '.' . $photo_ext;
            $photo_target_file = $photo_target_dir . $photo_name_original;
            $i++;
        }
    
        if (move_uploaded_file($photo_tmp_name, $photo_target_file)) {
            $image = 'uploads/images_upload/' . $photo_name_original;
        } else {
            echo "Erreur lors de l'upload. Code erreur : " . $_FILES['image']['error'];
        }
    } else {
        echo "Aucune image téléchargée ou une erreur est survenue.";
    }
    
    

    // Insérer le projet dans la base de données avec l'ID de la catégorie
    $stmt = $connexion_bdd->prepare("INSERT INTO projets (titre, texte, idCategorie, img, lien) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $titre, $texte, $categorie, $image, $lien); // Utilisation de l'ID de la catégorie
    $stmt->execute();
    
    header('Location: ../../index.php'); // Rediriger vers la page d'administration après l'ajout
}
?>

<!-- Formulaire pour ajouter un projet -->
<form method="POST" action="ajouter_projet.php" class="w-full max-w-lg mx-auto p-8 bg-white shadow-md rounded-lg" enctype="multipart/form-data">
    <h2 class="text-2xl font-semibold mb-6 text-center">Ajouter un projet</h2>

    <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
    <input type="text" id="titre" name="titre" required class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">

    <label for="texte" class="block text-sm font-medium text-gray-700 mt-4">Description</label>
    <textarea id="texte" name="texte" required class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>

    <label for="categorie" class="block text-sm font-medium text-gray-700 mt-4">Catégorie</label>
    <select id="categorie" name="categorie" onchange="toggleVideoLink()" required class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="">Choisir une catégorie</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category['nom']); ?>"><?php echo htmlspecialchars($category['nom']); ?></option>
        <?php endforeach; ?>
    </select>


    <!-- Affichage du lien YouTube uniquement si la catégorie est Vidéo -->
    <div id="lien_container" style="display: none;" class="mt-4">
        <label for="lien" class="block text-sm font-medium text-gray-700">Lien YouTube</label>
        <input type="url" id="lien" name="lien" placeholder="https://www.youtube.com/watch?v=..." class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <!-- Bouton pour choisir l'image -->
    <div class="mt-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" name="image" id="image" class="mt-2 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" accept="image/*" required />
        <span id="file-name" class="ml-4 text-gray-500">Aucun fichier choisi</span>
    </div>

    <div class="flex justify-between mt-4">
        <!-- Bouton Retour -->
        <button type="button" onclick="window.location.href='../../index.php'" class="w-full md:w-auto px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Retour</button>

        <!-- Bouton Ajouter le projet -->
        <button type="submit" class="w-full md:w-auto mt-4 md:mt-0 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Ajouter le projet</button>
    </div>
</form>

<script>
// Fonction pour afficher le nom du fichier sélectionné
document.getElementById('image').addEventListener('change', function(event) {
    var fileName = event.target.files.length > 0 ? event.target.files[0].name : 'Aucun fichier choisi';
    document.getElementById('file-name').textContent = fileName;
});

// Fonction pour afficher/masquer le champ lien si la catégorie est Vidéo
function toggleVideoLink() {
    var categorie = document.getElementById('categorie').value;
    var videoLinkContainer = document.getElementById('lien_container');

    // Si la catégorie est "Vidéo", afficher le lien YouTube
    if (categorie == 'Video') {  // Remplacez 'Vidéo' par le nom réel de la catégorie "Vidéo"
        videoLinkContainer.style.display = 'block';
    } else {
        videoLinkContainer.style.display = 'none';
    }
}
</script>
