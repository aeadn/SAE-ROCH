<?php require_once('idlogin.php'); //récupération des identifiants de connexion dans un fichier extérieur
$connexion_bdd = mysqli_connect($host, $nomUtilisateur, $mdp, $bdd);
if (!$connexion_bdd) {
    die("Connexion échouée : " . mysqli_connect_error());
    exit(); //stop l'éxecution du service si la connexion échoue
}
?>