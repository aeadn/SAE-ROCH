<?php
$requete = "SELECT * FROM social_media;";
$reseaux_sociaux = mysqli_query($connect, $requete);
// Vérification et affichage des résultats

if ($reseaux_sociaux && mysqli_num_rows($reseaux_sociaux) > 0) {
    while ($row = mysqli_fetch_assoc($reseaux_sociaux)) { ?>
        <li>
            <a class="icon brands <?php echo htmlspecialchars($row['icone']);?>" 
            target = "_blank"
               href="<?php echo htmlspecialchars($row['lien']);?>">
            </a>
        </li>
    <?php }
} else {
    echo "Aucun réseau social trouvé.";
}

// Fermer la connexion après usage
mysqli_close($connect);
?>