<?php
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mon Projet Ventes</title>
    <style>
        body { font-family: verdana; background-color: #f0f0f0; margin: 0; padding: 0; }
        .menu { background-color: orange; padding: 10px; }
        .menu a { color: white; margin-right: 15px; text-decoration: none; font-weight: bold; }
        .menu a:hover { text-decoration: underline; }
        .contenu { padding: 20px; }
        h2 { color: orange; }
        .stats { display: flex; gap: 20px; margin-top: 20px; }
        .stat-box { background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-box h3 { color: orange; margin-top: 0; }
        .stat-box b { font-size: 24px; color: orange; }
    </style>
</head>
<body>

<div class="menu">
    &nbsp;🛒 <b>MonProjet</b> &nbsp;&nbsp;
    <a href="accueil.php">Accueil</a>
    <a href="clients.php">Clients</a>
    <a href="users.php">Utilisateurs</a>
    <a href="article.php">Articles</a>
    <a href="vente.php">Ventes</a>
    <a href="effectuer_vente.php">Effectuer une vente</a>
</div>

<div class="contenu">
    <h2>Bienvenue</h2>
    <p>Utilisez le menu en haut pour naviguer.</p>

    <?php
    // quelques statistiques simples
    $nb_clients  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM clients"));
    $nb_produits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produits"));
    $nb_ventes   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM commandes"));
    ?>

    <div class="stats">
        <div class="stat-box">
            <h3>Clients</h3>
            <p>Nombre de clients : <b><?php echo $nb_clients; ?></b></p>
        </div>
        <div class="stat-box">
            <h3>Articles</h3>
            <p>Nombre de produits : <b><?php echo $nb_produits; ?></b></p>
        </div>
        <div class="stat-box">
            <h3>Ventes</h3>
            <p>Nombre de ventes : <b><?php echo $nb_ventes; ?></b></p>
        </div>
    </div>
</div>

</body>
</html>
