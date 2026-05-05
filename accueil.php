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
        .stats { display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap; }
        .stat-box { background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex: 1; min-width: 200px; }
        .stat-box h3 { color: orange; margin-top: 0; }
        .stat-box b { font-size: 24px; color: orange; }
        
        /* Démos Section */
        .demos-section { margin-top: 30px; }
        .demo-card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .demo-card h3 { color: orange; border-bottom: 2px solid orange; padding-bottom: 10px; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background-color: orange; color: white; padding: 10px; text-align: left; }
        table td { padding: 10px; border-bottom: 1px solid #ddd; }
        table tr:hover { background-color: #f9f9f9; }
        
        /* Buttons */
        .btn { display: inline-block; background-color: orange; color: white; padding: 10px 15px; border-radius: 3px; text-decoration: none; margin-top: 10px; border: none; cursor: pointer; }
        .btn:hover { background-color: darkorange; }
        
        /* Top Products */
        .product-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 15px; }
        .product-item { background: #f9f9f9; padding: 15px; border-radius: 3px; border-left: 4px solid orange; }
        .product-item h4 { margin: 0 0 10px 0; color: orange; }
        .product-item p { margin: 5px 0; }
        .price { font-size: 18px; color: green; font-weight: bold; }
        
        /* Orders Status */
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #cfe2ff; color: #084298; }
        .status-delivered { background-color: #d1e7dd; color: #0f5132; }
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
    
    // Statistiques additionnelles
    $result_montant = mysqli_query($conn, "SELECT SUM(montant) as total FROM commandes");
    $row_montant = mysqli_fetch_assoc($result_montant);
    $montant_total = $row_montant['total'] ?? 0;
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
        <div class="stat-box">
            <h3>Chiffre d'affaires</h3>
            <p>Montant total : <b><?php echo number_format($montant_total, 0, ',', ' '); ?> FCFA</b></p>
        </div>
    </div>

    <!-- SECTION DÉMOS -->
    <div class="demos-section">
        
        <!-- DÉMO 1: Top Produits -->
        <div class="demo-card">
            <h3>📦 Top Produits en Stock</h3>
            <p>Voici les articles les plus disponibles :</p>
            <div class="product-list">
                <?php
                $query = "SELECT id, nom_produit, prix_produit, quantite_stock, categorie FROM produits ORDER BY quantite_stock DESC LIMIT 3";
                $result = mysqli_query($conn, $query);
                
                while ($produit = mysqli_fetch_assoc($result)) {
                    echo "
                    <div class='product-item'>
                        <h4>{$produit['nom_produit']}</h4>
                        <p><strong>Catégorie:</strong> {$produit['categorie']}</p>
                        <p class='price'>" . number_format($produit['prix_produit'], 0, ',', ' ') . " FCFA</p>
                        <p><strong>Stock:</strong> {$produit['quantite_stock']} unités</p>
                    </div>
                    ";
                }
                ?>
            </div>
        </div>

        <!-- DÉMO 2: Dernières Commandes -->
        <div class="demo-card">
            <h3>📋 Dernières Commandes</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                    SELECT c.id, CONCAT(cl.prenom_client, ' ', cl.nom_client) as client_name, 
                           c.date_cmd, c.montant, c.statut
                    FROM commandes c
                    JOIN clients cl ON c.id_client = cl.id
                    ORDER BY c.date_cmd DESC LIMIT 5
                    ";
                    $result = mysqli_query($conn, $query);
                    
                    while ($commande = mysqli_fetch_assoc($result)) {
                        $status_class = 'status-pending';
                        if ($commande['statut'] == 'Confirmée') $status_class = 'status-confirmed';
                        if ($commande['statut'] == 'Livrée') $status_class = 'status-delivered';
                        
                        echo "
                        <tr>
                            <td>#" . $commande['id'] . "</td>
                            <td>{$commande['client_name']}</td>
                            <td>{$commande['date_cmd']}</td>
                            <td>" . number_format($commande['montant'], 0, ',', ' ') . " FCFA</td>
                            <td><span class='status-badge $status_class'>{$commande['statut']}</span></td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- DÉMO 3: Clients Récents -->
        <div class="demo-card">
            <h3>👥 Clients Récents</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Ville</th>
                        <th>Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT prenom_client, nom_client, mail, tel, ville, date_inscription FROM clients ORDER BY date_inscription DESC LIMIT 5";
                    $result = mysqli_query($conn, $query);
                    
                    while ($client = mysqli_fetch_assoc($result)) {
                        echo "
                        <tr>
                            <td>{$client['prenom_client']} {$client['nom_client']}</td>
                            <td>{$client['mail']}</td>
                            <td>{$client['tel']}</td>
                            <td>{$client['ville']}</td>
                            <td>" . date('d/m/Y', strtotime($client['date_inscription'])) . "</td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- DÉMO 4: Résumé par Statut -->
        <div class="demo-card">
            <h3>📊 Résumé des Commandes par Statut</h3>
            <table>
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Nombre</th>
                        <th>Montant Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                    SELECT statut, COUNT(*) as nombre, SUM(montant) as montant_total
                    FROM commandes
                    GROUP BY statut
                    ";
                    $result = mysqli_query($conn, $query);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status_class = 'status-pending';
                        if ($row['statut'] == 'Confirmée') $status_class = 'status-confirmed';
                        if ($row['statut'] == 'Livrée') $status_class = 'status-delivered';
                        
                        echo "
                        <tr>
                            <td><span class='status-badge $status_class'>{$row['statut']}</span></td>
                            <td>{$row['nombre']}</td>
                            <td>" . number_format($row['montant_total'], 0, ',', ' ') . " FCFA</td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- DÉMO 5: Commandes par Client -->
        <div class="demo-card">
            <h3>💰 Commandes par Client (Top 5)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Nombre de Commandes</th>
                        <th>Montant Total Dépensé</th>
                        <th>Moyenne par Commande</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                    SELECT cl.id, CONCAT(cl.prenom_client, ' ', cl.nom_client) as client_name,
                           COUNT(c.id) as nb_commandes, 
                           SUM(c.montant) as montant_total,
                           AVG(c.montant) as montant_moyen
                    FROM clients cl
                    LEFT JOIN commandes c ON cl.id = c.id_client
                    GROUP BY cl.id, cl.prenom_client, cl.nom_client
                    HAVING nb_commandes > 0
                    ORDER BY montant_total DESC LIMIT 5
                    ";
                    $result = mysqli_query($conn, $query);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
                        <tr>
                            <td>{$row['client_name']}</td>
                            <td>{$row['nb_commandes']}</td>
                            <td>" . number_format($row['montant_total'], 0, ',', ' ') . " FCFA</td>
                            <td>" . number_format($row['montant_moyen'], 0, ',', ' ') . " FCFA</td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <!-- FIN SECTION DÉMOS -->

</div>

</body>
</html>