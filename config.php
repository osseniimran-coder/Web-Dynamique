<?php
// connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "shop_db";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);

// verifier si ca marche
if(!$conn){
    echo "probleme de connexion";
    die();
}
?>
