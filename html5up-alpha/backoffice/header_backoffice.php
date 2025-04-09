<?php 
require_once('verif.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Administration</title>

  <!-- imports -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  
  <style>
    /* Rendre le header sticky */
    #header {
      position: sticky;
      top: 0;
      z-index: 10; /* S'assure que le header reste au-dessus des autres éléments */
    }
  </style>
  
</head>
<body>

  <!-- Header -->
  <header id="header" class="alt bg-black text-white px-6 py-4 flex items-center justify-between">
    <!-- Titre -->
    <h1 class="text-white text-2xl font-bold">Administration</h1>

    <!-- Navigation + Formulaire de recherche -->
    <nav id="nav" class="flex items-center space-x-6">


      <!-- Bouton de déconnexion -->
      <form method="POST" action="">
        <button type="submit" name="logout" title="Déconnexion" class="text-white text-xl hover:text-red-500 transition">
          <i class="fa-solid fa-right-from-bracket"></i>
        </button>
      </form>

    </nav>
  </header>

</body>
</html>
