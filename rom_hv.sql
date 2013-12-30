-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 30 Décembre 2013 à 13:47
-- Version du serveur: 5.1.66
-- Version de PHP: 5.3.3-7+squeeze14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `rom_hv`
--

-- --------------------------------------------------------

--
-- Structure de la table `romhv_config`
--

DROP TABLE IF EXISTS `romhv_config`;
CREATE TABLE IF NOT EXISTS `romhv_config` (
  `ref` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  KEY `ref` (`ref`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `romhv_config`
--

INSERT INTO `romhv_config` (`ref`, `value`) VALUES
('rom_version', '6.0.4.2676\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `romhv_item`
--

DROP TABLE IF EXISTS `romhv_item`;
CREATE TABLE IF NOT EXISTS `romhv_item` (
  `id` int(10) unsigned NOT NULL,
  `tip_left` text NOT NULL,
  `tip_right` text NOT NULL,
  `text` varchar(255) NOT NULL,
  `color` varchar(6) NOT NULL,
  KEY `id` (`id`),
  KEY `title` (`text`),
  KEY `color` (`color`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `romhv_perso`
--

DROP TABLE IF EXISTS `romhv_perso`;
CREATE TABLE IF NOT EXISTS `romhv_perso` (
  `idPerso` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `po` int(11) NOT NULL,
  PRIMARY KEY (`idPerso`),
  KEY `idUser` (`idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `romhv_perso_item`
--

DROP TABLE IF EXISTS `romhv_perso_item`;
CREATE TABLE IF NOT EXISTS `romhv_perso_item` (
  `ref` varchar(255) NOT NULL,
  `idPerso` int(10) unsigned NOT NULL,
  `idUser` int(10) unsigned NOT NULL,
  `idItem` int(10) unsigned NOT NULL,
  `typeItem` enum('I','S') NOT NULL,
  `enchere` int(10) unsigned NOT NULL,
  `rachat` int(10) unsigned NOT NULL,
  `enVente` tinyint(1) unsigned NOT NULL,
  `dateDebut` datetime NOT NULL,
  `duree` tinyint(1) unsigned NOT NULL,
  `notes` text NOT NULL,
  `vendu` tinyint(1) unsigned NOT NULL,
  `typeVente` enum('rachat','enchere') NOT NULL,
  `dateVendu` datetime NOT NULL,
  `poGagne` int(10) unsigned NOT NULL,
  UNIQUE KEY `ref` (`ref`),
  KEY `idPerso` (`idPerso`,`idItem`,`vendu`,`dateVendu`,`enVente`,`idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `romhv_perso_item_stat`
--

DROP TABLE IF EXISTS `romhv_perso_item_stat`;
CREATE TABLE IF NOT EXISTS `romhv_perso_item_stat` (
  `ref` varchar(255) NOT NULL,
  `idStat` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `romhv_stat`
--

DROP TABLE IF EXISTS `romhv_stat`;
CREATE TABLE IF NOT EXISTS `romhv_stat` (
  `idStat` int(10) unsigned NOT NULL,
  `nom` varchar(255) NOT NULL,
  UNIQUE KEY `idStat` (`idStat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `romhv_users`
--

DROP TABLE IF EXISTS `romhv_users`;
CREATE TABLE IF NOT EXISTS `romhv_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `po` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`,`mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
