<?php
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clients - Mon Projet Ventes</title>
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
        input[type=submit], .btn { background: orange; color: white; border: none;
            padding: 8px 20px; cursor: pointer; margin-top: 10px; margin-right: 5px; }
        input[type=submit]:hover, .btn:hover { background: darkorange; }
        .msg_ok  { color: green; font-weight: bold; }
        .msg_err { color: red; font-weight: bold; }
        .btn-container { margin-top: 15px; }
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
    <h2>Gestion des Clients</h2>

    <?php
    // ajouter un client si le formulaire est soumis
    if(isset($_POST['btn_ajouter_client'])){
        $nom    = $_POST['nom_client'];
        $prenom = $_POST['prenom_client'];
        $tel    = $_POST['tel'];
        $mail   = $_POST['mail'];

        if($nom == '' || $prenom == ''){
            echo "<p class='msg_err'>❌ Veuillez remplir le nom et le prénom !</p>";
        } else {
            mysqli_query($conn, "INSERT INTO clients (nom_client, prenom_client, tel, mail)
                                 VALUES ('$nom','$prenom','$tel','$mail')");
            echo "<p class='msg_ok'>✔ Client ajouté avec succès !</p>";
        }
    }
    ?>

    <h3>Ajouter un client</h3>
    <form method='POST'>
        Nom : <input type='text' name='nom_client' required><br>
        Prénom : <input type='text' name='prenom_client' required><br>
        Téléphone : <input type='text' name='tel'><br>
        Email : <input type='email' name='mail'><br>
        <input type='submit' name='btn_ajouter_client' value='➕ Ajouter'>
    </form><br>

    <h3>Liste des clients</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Email</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM clients ORDER BY nom_client");
        if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nom_client']}</td>
                        <td>{$row['prenom_client']}</td>
                        <td>{$row['tel']}</td>
                        <td>{$row['mail']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>Aucun client</td></tr>";
        }
        ?>
    </table>

    <div class="btn-container">
        <a href="accueil.php" class="btn">🏠 Quitter</a>
    </div>
</div>

</body>
</html>
