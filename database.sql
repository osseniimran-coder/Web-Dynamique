-- base de données pour mon projet
-- créé par moi

create database if not exists shop_db;
use shop_db;

create table clients(
    id int primary key auto_increment,
    nom_client varchar(100) not null,
    prenom_client varchar(100) not null,
    tel varchar(15),
    mail varchar(100)
);

create table produits(
    id int primary key auto_increment,
    nom_produit varchar(100),
    prix_produit float,
    quantite_stock int default 0
);

create table commandes(
    id int primary key auto_increment,
    id_client int,
    date_cmd date,
    montant float
);

create table details_commande(
    id int primary key auto_increment,
    id_commande int,
    id_produit int,
    qte int,
    prix float
);
