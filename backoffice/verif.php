<?php
if (session_status() === PHP_SESSION_NONE) { //si il y a oas de session il en créer une
    session_start();
}

//si il l'utilisateur est déconnecté ou que les id et mdp sont faux 
if (!isset($_SESSION['connected'])|| !$_SESSION['connected']) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URL']; //on donne pas l'acces
    header("Location: pageconnect.php");
    exit();
}