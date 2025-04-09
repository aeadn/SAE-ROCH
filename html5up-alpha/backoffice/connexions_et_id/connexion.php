<?php require_once("idlogin.php"); 
$connect = mysqli_connect($host, $nomUtilisateur, $mdp, $bdd);

if ($connect -> connect_error) { //test pour vérifier la réussite de la connexion
    die("Connexion échouée : ". mysqli_connect_error());
    exit(); //ne fait pas les instructions plus bas si la connexion a échouée. 
}
?>