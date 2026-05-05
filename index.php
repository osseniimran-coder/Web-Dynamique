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
        table { border-collapse: collapse; width: 90%; }
        td, th { border: 1px solid #ccc; padding: 6px 10px; }
        th { background-color: orange; color: white; }
        tr:nth-child(even) { background-color: #fff8f0; }
        input[type=text], input[type=number], input[type=email], select {
            padding: 5px; margin: 4px 0; width: 250px;
        }
        input[type=submit] {
            background: orange; color: white; border: none;
            padding: 8px 20px; cursor: pointer; margin-top: 10px;
        }
        .msg_ok  { color: green; font-weight: bold; }
        .msg_err { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="menu">
    &nbsp;🛒 <b>MonProjet</b> &nbsp;&nbsp;
    <a href="index.php">Accueil</a>
    <a href="index.php?page=clients">Clients</a>
    <a href="index.php?page=produits">Produits</a>
    <a href="index.php?page=vente">Nouvelle Vente</a>
    <a href="index.php?page=historique">Historique</a>
</div>

<div class="contenu">
<?php
include 'config.php';

// je recupere la page demandee dans l'url
$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

// ===================== ACCUEIL =====================
if($page == 'accueil'){
    echo "<h2>Bienvenue</h2>";
    echo "<p>Utilisez le menu en haut pour naviguer.</p>";

    // quelques statistiques simples
    $nb_clients  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM clients"));
    $nb_produits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produits"));
    $nb_ventes   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM commandes"));

    echo "<p>Nombre de clients : <b>$nb_clients</b></p>";
    echo "<p>Nombre de produits : <b>$nb_produits</b></p>";
    echo "<p>Nombre de ventes : <b>$nb_ventes</b></p>";
}

// ===================== CLIENTS =====================
elseif($page == 'clients'){
    echo "<h2>Clients</h2>";

    // ajouter un client si le formulaire est soumis
    if(isset($_POST['btn_ajouter_client'])){
        $nom    = $_POST['nom_client'];
        $prenom = $_POST['prenom_client'];
        $tel    = $_POST['tel'];
        $mail   = $_POST['mail'];

        if($nom == '' || $prenom == ''){
            echo "<p class='msg_err'>Veuillez remplir le nom et le prénom !</p>";
        } else {
            mysqli_query($conn, "INSERT INTO clients (nom_client, prenom_client, tel, mail)
                                 VALUES ('$nom','$prenom','$tel','$mail')");
            echo "<p class='msg_ok'>✔ Client ajouté avec succès !</p>";
        }
    }

    // formulaire ajout client
    echo "
    <h3>Ajouter un client</h3>
    <form method='POST'>
        Nom : <input type='text' name='nom_client'><br>
        Prénom : <input type='text' name='prenom_client'><br>
        Téléphone : <input type='text' name='tel'><br>
        Email : <input type='email' name='mail'><br>
        <input type='submit' name='btn_ajouter_client' value='Ajouter'>
    </form><br>
    ";

    // liste des clients
    echo "<h3>Liste des clients</h3>";
    echo "<table>
            <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Téléphone</th><th>Email</th></tr>";
    $res = mysqli_query($conn, "SELECT * FROM clients ORDER BY nom_client");
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nom_client']}</td>
                <td>{$row['prenom_client']}</td>
                <td>{$row['tel']}</td>
                <td>{$row['mail']}</td>
              </tr>";
    }
    echo "</table>";
}

// ===================== PRODUITS =====================
elseif($page == 'produits'){
    echo "<h2>Produits</h2>";

    if(isset($_POST['btn_ajouter_produit'])){
        $nom   = $_POST['nom_produit'];
        $prix  = $_POST['prix_produit'];
        $stock = $_POST['stock'];

        mysqli_query($conn, "INSERT INTO produits (nom_produit, prix_produit, quantite_stock)
                             VALUES ('$nom', $prix, $stock)");
        echo "<p class='msg_ok'>✔ Produit ajouté !</p>";
    }

    echo "
    <h3>Ajouter un produit</h3>
    <form method='POST'>
        Nom du produit : <input type='text' name='nom_produit'><br>
        Prix (FCFA) : <input type='number' name='prix_produit' step='0.01'><br>
        Stock : <input type='number' name='stock'><br>
        <input type='submit' name='btn_ajouter_produit' value='Ajouter'>
    </form><br>
    ";

    echo "<h3>Liste des produits</h3>";
    echo "<table>
            <tr><th>ID</th><th>Produit</th><th>Prix</th><th>Stock</th></tr>";
    $res = mysqli_query($conn, "SELECT * FROM produits ORDER BY nom_produit");
    while($r = mysqli_fetch_assoc($res)){
        echo "<tr>
                <td>{$r['id']}</td>
                <td>{$r['nom_produit']}</td>
                <td>{$r['prix_produit']} FCFA</td>
                <td>{$r['quantite_stock']}</td>
              </tr>";
    }
    echo "</table>";
}

// ===================== NOUVELLE VENTE =====================
elseif($page == 'vente'){
    echo "<h2>Nouvelle Vente</h2>";

    if(isset($_POST['btn_vendre'])){
        $id_client  = $_POST['id_client'];
        $id_produit = $_POST['id_produit'];
        $qte        = $_POST['qte'];
        $date       = date('Y-m-d');

        // recuperer le prix du produit
        $rp   = mysqli_query($conn, "SELECT prix_produit FROM produits WHERE id=$id_produit");
        $prod = mysqli_fetch_assoc($rp);
        $prix = $prod['prix_produit'];
        $montant = $prix * $qte;

        // enregistrer la commande
        mysqli_query($conn, "INSERT INTO commandes (id_client, date_cmd, montant)
                             VALUES ($id_client, '$date', $montant)");
        $id_cmd = mysqli_insert_id($conn);

        // enregistrer le detail
        mysqli_query($conn, "INSERT INTO details_commande (id_commande, id_produit, qte, prix)
                             VALUES ($id_cmd, $id_produit, $qte, $prix)");

        // mettre a jour le stock
        mysqli_query($conn, "UPDATE produits SET quantite_stock = quantite_stock - $qte WHERE id = $id_produit");

        echo "<p class='msg_ok'>✔ Vente enregistrée ! Montant : $montant FCFA</p>";
    }

    // liste des clients pour le select
    $clients = mysqli_query($conn, "SELECT * FROM clients");
    // liste des produits pour le select
    $produits = mysqli_query($conn, "SELECT * FROM produits");

    echo "
    <form method='POST'>
        <p>Client :<br>
        <select name='id_client' required>
            <option value=''>-- Choisir un client --</option>";
    while($c = mysqli_fetch_assoc($clients)){
        echo "<option value='{$c['id']}'>{$c['nom_client']} {$c['prenom_client']}</option>";
    }
    echo "</select></p>

        <p>Produit :<br>
        <select name='id_produit' required>
            <option value=''>-- Choisir un produit --</option>";
    while($p = mysqli_fetch_assoc($produits)){
        echo "<option value='{$p['id']}'>{$p['nom_produit']} ({$p['prix_produit']} FCFA)</option>";
    }
    echo "</select></p>

        <p>Quantité :<br>
        <input type='number' name='qte' value='1' min='1'></p>

        <input type='submit' name='btn_vendre' value='Valider la vente'>
    </form>";
}

// ===================== HISTORIQUE =====================
elseif($page == 'historique'){
    echo "<h2>Historique des ventes</h2>";

    // jointure pour afficher tout
    $sql = "SELECT commandes.id, commandes.date_cmd, commandes.montant,
                   clients.nom_client, clients.prenom_client,
                   produits.nom_produit, details_commande.qte
            FROM commandes
            JOIN clients          ON commandes.id_client        = clients.id
            JOIN details_commande ON details_commande.id_commande = commandes.id
            JOIN produits         ON details_commande.id_produit  = produits.id
            ORDER BY commandes.date_cmd DESC";

    $res = mysqli_query($conn, $sql);

    echo "<table>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Client</th>
                <th>Produit</th>
                <th>Qté</th>
                <th>Montant (FCFA)</th>
            </tr>";

    while($r = mysqli_fetch_assoc($res)){
        echo "<tr>
                <td>{$r['id']}</td>
                <td>{$r['date_cmd']}</td>
                <td>{$r['nom_client']} {$r['prenom_client']}</td>
                <td>{$r['nom_produit']}</td>
                <td>{$r['qte']}</td>
                <td>{$r['montant']}</td>
              </tr>";
    }
    echo "</table>";
}
?>
</div>
</body>
</html>
