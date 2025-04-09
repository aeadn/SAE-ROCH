<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4 text-center">Identifiez-vous</h2>

        <?php
require_once('connexions_et_id/idlogin.php');
require_once('connexions_et_id/connexionBDD.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['identifiant']) && !empty($_POST['mdp'])) {
        $id = mysqli_real_escape_string($connexion_bdd, $_POST['identifiant']);
        $requete = "SELECT `password` FROM user WHERE `login` = '$id'";
        $resultat = mysqli_query($connexion_bdd, $requete);

        if (!$resultat) {
            die("Erreur SQL : " . mysqli_error($connexion_bdd));
        }

        $mdp = mysqli_fetch_assoc($resultat);

        // Debug temporaire
        if (!$mdp) {
            echo "<p class='text-red-500 text-center'>Aucun utilisateur trouvé avec cet identifiant.</p>";
        } else {
            // Affiche les infos pour débugger
            // (à désactiver une fois que ça fonctionne)
            echo "<pre>";
            echo "Hash récupéré : " . $mdp['password'] . "\n";
            echo "Mot de passe saisi : " . $_POST['mdp'] . "\n";
            echo "Mot de passe saisi haché : " . password_hash($_POST['mdp'], PASSWORD_DEFAULT) . "\n";
            echo "</pre>";

            if (password_verify($_POST['mdp'], $mdp['password'])) {
                $_SESSION['connected'] = true;
                $redirectTo = $_SESSION['redirect_to'] ?? 'index.php';
                unset($_SESSION['redirect_to']);
                header("Location: $redirectTo");
                exit();
            } else {
                echo "<p class='text-red-500 text-center'>Mot de passe incorrect.</p>";
            }
        }
    } else {
        echo "<p class='text-red-500 text-center'>Veuillez remplir tous les champs.</p>";
    }
}
?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="identifiant" class="block text-gray-600 font-medium">Identifiant</label>
                <input name="identifiant" id="identifiant" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
            </div>
            <div>
                <label for="mdp" class="block text-gray-600 font-medium">Mot de passe</label>
                <input type="password" name="mdp" id="mdp" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
            </div>
            <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Valider</button>
            </div>
        </form>

        <!--Retour -->
        <div class="flex justify-center mt-4">
            <a href="../index.php"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Retour à l'accueil
            </a>
        </div>

    </div>
</body>

</html>
