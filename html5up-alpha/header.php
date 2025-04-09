<!DOCTYPE HTML>
<html>
	<head>
        <?php 
            require_once("backoffice/connexions_et_id/connexionBDD.php"); 
            require_once("backoffice/commandes/recuperer_donnees.php"); 
            $requete = "SELECT * FROM cv;";
            $row = recupererDonnees($connexion_bdd, $requete);
        ?>
		<title>Portfolio</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
    <body>
        <header id="header" class="alt">
            <h1><a href="index.php">Alpha by HTML5 UP</a></h1>
            <nav id="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="contact.php">Contact</a></li>
                        <li><a href="<?php echo $row[0]['fichier'];?>" download="CV_Abby">Télécharger le CV (PDF)</a></li>
                    <li>
                        <a href="#" class="icon solid fa-angle-down">Navigation</a>
                        <ul>
                            <li><a href="#banner">Accueil</a></li>
                            <li><a href="#main">Présentation</a></li>
                            <li><a href="#competences">Compétences</a></li>
                            <li><a href="#projets">Projets</a></li>
                            <li><a href="#projets-videos">Vidéos</a></li>
                            <li><a href="#parcours">Parcours</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
    </body>
</html>
