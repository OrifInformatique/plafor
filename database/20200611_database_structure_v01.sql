-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 11 juin 2020 à 09:31
-- Version du serveur :  10.4.6-MariaDB
-- Version de PHP :  7.3.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `plafor`
--
CREATE DATABASE IF NOT EXISTS `plafor` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `plafor`;

-- --------------------------------------------------------

--
-- Structure de la table `acquisition_level`
--

CREATE TABLE `acquisition_level` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `fk_trainer` int(11) NOT NULL,
  `fk_acquisition_status` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `competence_domain`
--

CREATE TABLE `competence_domain` (
  `id` int(11) NOT NULL,
  `fk_course_plan` int(11) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `course_plan`
--

CREATE TABLE `course_plan` (
  `id` int(11) NOT NULL,
  `formation_number` varchar(5) NOT NULL,
  `official_name` varchar(100) NOT NULL,
  `date_begin` date NOT NULL,
  `archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `objective`
--

CREATE TABLE `objective` (
  `id` int(11) NOT NULL,
  `fk_operational_competence` int(11) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `taxonomy` int(5) NOT NULL,
  `name` varchar(350) NOT NULL,
  `archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `operational_competence`
--

CREATE TABLE `operational_competence` (
  `id` int(11) NOT NULL,
  `fk_competence_domain` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `symbol` varchar(10) NOT NULL,
  `methodologic` text DEFAULT NULL,
  `social` text DEFAULT NULL,
  `personal` text DEFAULT NULL,
  `archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fk_user_type` int(11) NOT NULL,
  `username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `archive` tinyint(4) NOT NULL DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_course_status`
--

CREATE TABLE `user_course_status` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `access_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `acquisition_level`
--
ALTER TABLE `acquisition_level`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_acquisition_status` (`fk_acquisition_status`),
  ADD KEY `fk_trainer` (`fk_trainer`);

--
-- Index pour la table `competence_domain`
--
ALTER TABLE `competence_domain`
  ADD PRIMARY KEY (`id`),
  ADD KEY `constraint_competence_domain_course_plan` (`fk_course_plan`);

--
-- Index pour la table `course_plan`
--
ALTER TABLE `course_plan`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `objective`
--
ALTER TABLE `objective`
  ADD PRIMARY KEY (`id`),
  ADD KEY `constraint_operational_competence` (`fk_operational_competence`);

--
-- Index pour la table `operational_competence`
--
ALTER TABLE `operational_competence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `constraint_domain_operational` (`fk_competence_domain`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_user_type1_idx` (`fk_user_type`);

--
-- Index pour la table `user_course_status`
--
ALTER TABLE `user_course_status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `competence_domain`
--
ALTER TABLE `competence_domain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `course_plan`
--
ALTER TABLE `course_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `objective`
--
ALTER TABLE `objective`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `operational_competence`
--
ALTER TABLE `operational_competence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_course_status`
--
ALTER TABLE `user_course_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`fk_acquisition_status`) REFERENCES `acquisition_status` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`fk_trainer`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `competence_domain`
--
ALTER TABLE `competence_domain`
  ADD CONSTRAINT `constraint_competence_domain_course_plan` FOREIGN KEY (`fk_course_plan`) REFERENCES `course_plan` (`id`);

--
-- Contraintes pour la table `objective`
--
ALTER TABLE `objective`
  ADD CONSTRAINT `constraint_operational_competence` FOREIGN KEY (`fk_operational_competence`) REFERENCES `operational_competence` (`id`);

--
-- Contraintes pour la table `operational_competence`
--
ALTER TABLE `operational_competence`
  ADD CONSTRAINT `constraint_domain_operational` FOREIGN KEY (`fk_competence_domain`) REFERENCES `competence_domain` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_user_type1` FOREIGN KEY (`fk_user_type`) REFERENCES `user_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
