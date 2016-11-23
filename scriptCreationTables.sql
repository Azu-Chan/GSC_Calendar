-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 15 Juillet 2016 à 20:23
-- Version du serveur :  5.5.46-0+deb8u1
-- Version de PHP :  5.6.14-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `The Galactic Shrewd Calendar`
--
DROP DATABASE `The Galactic Shrewd Calendar`;
CREATE DATABASE IF NOT EXISTS `The Galactic Shrewd Calendar` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `The Galactic Shrewd Calendar`;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
`id` int(11) NOT NULL,
  `utilisateur` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `nom` varchar(20) COLLATE utf8_bin NOT NULL,
  `couleur` varchar(7) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Structure de la table `connexion`
--

CREATE TABLE IF NOT EXISTS `connexion` (
  `utilisateur` varchar(255) COLLATE utf8_bin NOT NULL,
  `ip` varchar(39) COLLATE utf8_bin NOT NULL DEFAULT '',
  `dateConnexion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Structure de la table `evenement`
--

CREATE TABLE IF NOT EXISTS `evenement` (
`id` int(11) NOT NULL,
  `utilisateur` varchar(255) COLLATE utf8_bin NOT NULL,
  `categorie` int(11) NOT NULL,
  `dateDebut` datetime NOT NULL,
  `dateFin` datetime NOT NULL,
  `nom` varchar(80) COLLATE utf8_bin NOT NULL,
  `resume` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `frequence` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Structure de la table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `evenement` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `prenom` varchar(109) COLLATE utf8_bin NOT NULL,
  `nom` varchar(109) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `mdp` varchar(32) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_u` (`utilisateur`);

--
-- Index pour la table `connexion`
--
ALTER TABLE `connexion`
 ADD PRIMARY KEY (`utilisateur`,`dateConnexion`,`ip`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_ue` (`utilisateur`), ADD KEY `fk_c` (`categorie`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
 ADD PRIMARY KEY (`evenement`,`url`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
 ADD PRIMARY KEY (`email`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `categorie`
--
ALTER TABLE `categorie`
ADD CONSTRAINT `fk_u` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateur` (`email`);

--
-- Contraintes pour la table `connexion`
--
ALTER TABLE `connexion`
ADD CONSTRAINT `fk_u_c` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateur` (`email`);

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
ADD CONSTRAINT `fk_c` FOREIGN KEY (`categorie`) REFERENCES `categorie` (`id`),
ADD CONSTRAINT `fk_ue` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateur` (`email`);

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
ADD CONSTRAINT `fk_e` FOREIGN KEY (`evenement`) REFERENCES `evenement` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
