-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 29 mars 2026 à 22:32
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `unao_projets_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `chercheurs`
--

DROP TABLE IF EXISTS `chercheurs`;
CREATE TABLE IF NOT EXISTS `chercheurs` (
  `id_chercheur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `specialite` varchar(100) NOT NULL,
  `date_inscription` date NOT NULL,
  PRIMARY KEY (`id_chercheur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `chercheurs`
--

INSERT INTO `chercheurs` (`id_chercheur`, `nom`, `prenom`, `email`, `specialite`, `date_inscription`) VALUES
(1, 'Akobi', 'Messan', 'wekessiphilippe@gmail.com', 'Intelligence Artificielle', '2022-09-01'),
(2, 'Dossou', 'Faridath', 'faridath.dossou@unao.bj', 'Cybersécurité', '2021-10-15'),
(3, 'Houeto', 'Rodrigue', 'rodrigue.houeto@unao.bj', 'Réseaux et Systèmes', '2023-01-20'),
(4, 'Gbaguidi', 'Anais', 'anais.gbaguidi@unao.bj', 'Base de Données', '2022-03-10'),
(5, 'Kakpo', 'Wilfried', 'wilfried.kakpo@unao.bj', 'Développement Web', '2023-06-05');

-- --------------------------------------------------------

--
-- Structure de la table `participations`
--

DROP TABLE IF EXISTS `participations`;
CREATE TABLE IF NOT EXISTS `participations` (
  `id_participation` int NOT NULL AUTO_INCREMENT,
  `id_projet` int NOT NULL,
  `id_chercheur` int NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id_participation`),
  KEY `id_projet` (`id_projet`),
  KEY `id_chercheur` (`id_chercheur`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `participations`
--

INSERT INTO `participations` (`id_participation`, `id_projet`, `id_chercheur`, `role`) VALUES
(1, 1, 2, 'Co-chercheur'),
(2, 1, 3, 'Analyste données'),
(3, 2, 1, 'Consultant'),
(4, 3, 3, 'Développeur principal'),
(5, 3, 4, 'Architecte BDD'),
(6, 4, 2, 'Testeur sécurité');

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

DROP TABLE IF EXISTS `projets`;
CREATE TABLE IF NOT EXISTS `projets` (
  `id_projet` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin_prevue` date NOT NULL,
  `statut` enum('En cours','Terminé','Annulé','En attente') NOT NULL,
  `budget` decimal(15,2) NOT NULL,
  `id_chercheur_principal` int NOT NULL,
  PRIMARY KEY (`id_projet`),
  KEY `id_chercheur_principal` (`id_chercheur_principal`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id_projet`, `titre`, `description`, `date_debut`, `date_fin_prevue`, `statut`, `budget`, `id_chercheur_principal`) VALUES
(1, 'IA pour la santé', 'Système de diagnostic médical par IA', '2024-01-10', '2024-12-31', 'En cours', '5000000.00', 1),
(2, 'Sécurité des réseaux', 'Analyse des vulnérabilités réseau au Bénin', '2023-06-01', '2024-06-30', 'Terminé', '3500000.00', 2),
(3, 'Plateforme e-learning', 'Développement d une plateforme éducative', '2024-03-01', '2025-03-01', 'En attente', '7000000.00', 5),
(4, 'Gestion de données BDD', 'Optimisation des requêtes base de données', '2024-02-15', '2024-11-15', 'En cours', '2000000.00', 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
