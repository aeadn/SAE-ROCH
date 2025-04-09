<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un réseau social</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Ajouter un réseau social</h2>
    
    <form action="ajouter.php" method="POST" class="space-y-4">
        <!-- Champ Nom -->
        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom :</label>
            <input type="text" id="nom" name="nom" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Champ Icône -->
        <div>
            <label for="icone" class="block text-sm font-medium text-gray-700">Icône :</label>
            <input type="text" id="icone" name="icone" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Champ Lien -->
        <div>
            <label for="lien" class="block text-sm font-medium text-gray-700">Lien :</label>
            <input type="text" id="lien" name="lien" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex justify-between">
            <!-- Bouton Retour -->
            <button type="button" onclick="window.location.href='../admin.php'" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Retour</button>
            <!-- Bouton Ajouter -->
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Ajouter</button>
        </div>
    </form>
</div>

</body>
</html>

