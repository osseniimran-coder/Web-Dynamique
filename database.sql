SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `shop_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shop_db`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(100) NOT NULL,
  `prenom_client` varchar(100) NOT NULL,
  `age` int(2) DEFAULT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `date_inscription` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_nom` (`nom_client`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_produit` varchar(100) NOT NULL,
  `design` varchar(50) DEFAULT NULL,
  `prix_produit` decimal(10, 2) NOT NULL,
  `categorie` varchar(50) DEFAULT NULL,
  `quantite_stock` int(11) DEFAULT 0,
  `date_ajout` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_categorie` (`categorie`),
  INDEX `idx_nom` (`nom_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `date_cmd` date NOT NULL,
  `montant` decimal(12, 2) NOT NULL,
  `statut` varchar(30) DEFAULT 'En attente',
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_client` (`id_client`),
  CONSTRAINT `fk_commandes_client` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `details_commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `qte` int(11) NOT NULL,
  `prix` decimal(10, 2) NOT NULL,
  `montant_ligne` decimal(12, 2) GENERATED ALWAYS AS (qte * prix) STORED,
  PRIMARY KEY (`id`),
  KEY `fk_commande` (`id_commande`),
  KEY `fk_produit` (`id_produit`),
  CONSTRAINT `fk_details_commande` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_details_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`nom`, `prenom`, `login`, `password`, `contact`) VALUES
('Admin', 'Système', 'admin', 'admin123', '0000000000'),
('Imran', 'Osseni', 'imran', 'password123', '0123456789');

INSERT INTO `clients` (`nom_client`, `prenom_client`, `age`, `adresse`, `ville`, `tel`, `mail`) VALUES
('Diallo', 'Mohamed', 28, '123 Rue du Commerce', 'Dakar', '+221771234567', 'mohamed@email.com'),
('Sow', 'Aïssatou', 35, '456 Avenue Nationale', 'Thiès', '+221772345678', 'aissatou@email.com'),
('Ba', 'Mamadou', 42, '789 Boulevard Central', 'Kaolack', '+221773456789', 'mamadou@email.com');

INSERT INTO `produits` (`nom_produit`, `design`, `prix_produit`, `categorie`, `quantite_stock`) VALUES
('Téléphone Samsung A12', 'Smartphone noir', 150000.00, 'Électronique', 50),
('Casque Audio JBL', 'Casque sans fil', 45000.00, 'Accessoires', 30),
('Batterie Externe 20000mAh', 'Batterie portable', 25000.00, 'Accessoires', 100),
('Écran Moniteur 24"', 'Écran LED Full HD', 120000.00, 'Informatique', 15),
('Clavier Mécanique RGB', 'Clavier gaming', 35000.00, 'Informatique', 25);

INSERT INTO `commandes` (`id_client`, `date_cmd`, `montant`, `statut`) VALUES
(1, '2026-04-28', 195000.00, 'Confirmée'),
(2, '2026-04-29', 45000.00, 'Livrée'),
(1, '2026-05-01', 160000.00, 'En attente');

INSERT INTO `details_commande` (`id_commande`, `id_produit`, `qte`, `prix`) VALUES
(1, 1, 1, 150000.00),
(1, 3, 1, 25000.00),
(2, 2, 1, 45000.00),
(3, 4, 1, 120000.00),
(3, 5, 1, 40000.00);

COMMIT;
