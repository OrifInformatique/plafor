-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 02 juin 2020 à 08:05
-- Version du serveur :  10.4.6-MariaDB
-- Version de PHP :  7.3.9

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

--
-- Déchargement des données de la table `acquisition_level`
--

INSERT INTO `acquisition_level` (`id`, `name`) VALUES
(1, 'Expliqué'),
(2, 'Exercé'),
(3, 'Autonome');

--
-- Déchargement des données de la table `competence_domain`
--

INSERT INTO `competence_domain` (`id`, `fk_course_plan`, `symbol`, `name`) VALUES
(1, 1, 'A', 'Saisie, interprétation et mise en œuvre des exigences des applications'),
(2, 1, 'B', 'Développement d’applications en tenant compte des caractéristiques de qualité'),
(3, 1, 'C', 'Création et maintenance de données ainsi que de leurs structures '),
(4, 1, 'D', ' Mise en service d’appareils TIC'),
(5, 1, 'E', 'Travail sur des projets'),
(6, 2, 'A', 'Mise en service d’appareils TIC'),
(7, 2, 'B', 'Mise en service de serveurs et réseaux'),
(8, 2, 'C', 'Garantie de l’exploitation TIC '),
(9, 2, 'D', 'Assistance aux utilisateurs'),
(10, 2, 'E', 'Développement d’applications en tenant compte des caractéristiques de qualité'),
(11, 2, 'F', 'Travaux dans le cadre de projets');

--
-- Déchargement des données de la table `course_plan`
--

INSERT INTO `course_plan` (`id`, `formation_number`, `official_name`, `date_begin`) VALUES
(1, '88601', ' Informaticien/-ne CFC Développement d\'applications', '2013-11-01'),
(2, '88602', ' Informaticien/-ne CFC Informatique d\'entreprise', '2013-11-01');

--
-- Déchargement des données de la table `objective`
--

INSERT INTO `objective` (`id`, `fk_operational_competence`, `symbol`, `taxonomy`, `name`) VALUES
(1, 1, 'A.1.1', 4, 'Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences'),
(2, 1, 'A.1.2', 4, ' Confirmer les exigences en ses propres termes (traiter et en déduire, lister les questions)'),
(3, 1, 'A.1.3', 3, 'Eclaircir toutes les questions de la liste (questions sur les solutions, l’environnement, les dépendances, estimation temporelle)'),
(4, 1, 'A.1.4', 4, 'Présenter les exigences de manière structurée (par ex. avec UML), élaborer le cahier des charges et le subdiviser en types d‘exigences'),
(5, 1, 'A.1.5', 4, 'Vérifier avec le mandant la solution concernant l’exhaustivité, ainsi que la clarté, et conclure par une confirmation écrite'),
(6, 2, 'A.2.1', 4, 'Elaborer aussi loin que possibles plusieurs variantes de solutions en regard des exigences et de la satisfaction du client (par ex. dans le GUI ou sur la plateforme (PC, tablette))'),
(7, 2, 'A.2.2', 4, 'Représenter des comparaisons de variantes et d‘évaluations (y compris, produits), conseiller la clientèle dans le choix (avantages, désavantages, problèmes de la solution) sur la base de leur analyse des valeurs utiles'),
(8, 2, 'A.2.3', 4, 'Choisir une procédure de résolution des problèmes, par ex. développement de prototypes, recherche de solutions de ce qui peut être résolu avec l’informatique, ou autres moyens tels qu’organisation ou formation'),
(9, 3, 'A.3.1', 4, 'Vérifier si toutes les exigences ont été reprises et remplies avec la solution choisie'),
(10, 3, 'A.3.2', 3, 'Ecrire une offre, sur la base de leur planification, pour la réalisation et l’introduction de la nouvelle application'),
(11, 3, 'A.3.3', 3, 'Obtenir la confirmation et la distribution du mandat du client'),
(12, 4, 'B.1.1', 5, 'Elaborer un concept de tests comme base pour un développement efficace et l’assurance qualité d’une nouvelle application'),
(13, 4, 'B.1.2', 5, 'Appliquer des méthodes pour la détermination de cas de tests'),
(14, 4, 'B.1.3', 3, 'Mettre à disposition, sauvegarder et documenter les données des tests'),
(15, 4, 'B.1.4', 3, 'Elaborer et exécuter des cas de tests (Blackbox), automatiser dans les cas possible'),
(16, 4, 'B.1.5', 3, 'Saisir les résultats dans un protocole de tests en vue d’une répétition'),
(17, 4, 'B.1.6', 4, 'Evaluer les résultats des tests et, le cas échéant, en déduire des mesures'),
(18, 4, 'B.1.7', 4, 'Garantir que toutes les fonctions ont été testées et que les éventuelles erreurs ont été corrigées'),
(19, 5, 'B.2.1', 4, 'Résoudre les prescriptions d’entreprises avec des directives techniques (web, mobile, desktop, automates)'),
(20, 5, 'B.2.2', 4, 'Appliquer des modèles d’architecture dans les solutions (Multitier, Frameworks, Patterns)'),
(21, 5, 'B.2.3', 3, 'Satisfaire des exigences non-fonctionnelles telles que temps de réponse, stabilité, disponibilité'),
(22, 5, 'B.2.4', 3, 'Prise en compte de standards internationaux et spécifiques à l’entreprise dans le cadre des solutions'),
(23, 6, 'B.3.1', 4, 'Fonctionnalité conviviales, par ex. la même fonction déclenche toujours la même action, lorsque l’on feuillette, les informations introduites restent, etc '),
(24, 6, 'B.3.2', 4, 'Evaluation des modes de déroulement et des applications appropriées'),
(25, 6, 'B.3.3', 4, 'Programmer les applications en tenant compte des suites de tests, de débogage, de dépannage, de maintenance, d’efficience énergétique, de la protection des données, des règles en termes de licences, etc. et documenter de manière continue'),
(26, 6, 'B.3.4', 3, 'Utiliser des standards et processus de développement'),
(27, 6, 'B.3.5', 3, 'Appliquer des méthodes de projets (PAP, Jackson, diagramme d‘état, diagramme de classe) et les Software design-Patterns'),
(28, 6, 'B.3.6', 3, 'Respecter la convention des codes'),
(29, 6, 'B.3.7', 3, 'Editer, documenter du code source (par ex. code en ligne, ..) et documenter en vue de faciliter la maintenance'),
(30, 6, 'B.3.8', 3, 'Tester l’application et tout documenter'),
(31, 7, 'B.4.1', 4, 'Prendre en compte des exigences standards et ergonomiques, voir et toucher. Atteindre un bon effet convivial lors de l’utilisation des nouvelles applications'),
(32, 7, 'B.4.2', 3, 'Prendre en compte les CD/CI (Corporate Design/Corporate identity)'),
(33, 7, 'B.4.3', 3, 'Développer de manière conviviale, validation des champs de saisie, aide à la saisie des entrées'),
(34, 7, 'B.4.4', 3, 'Codage GUI convivial, séparation des éléments utilisateurs du code'),
(35, 7, 'B.4.5', 3, 'Prendre en compte les conditions de communication, par ex. communication asynchrone et veiller à de bonnes performances'),
(36, 7, 'B.4.6', 3, 'Tester l’application de manière exhaustive et tout documenter'),
(37, 8, 'B.5.1', 3, 'Organiser des tests systèmes, des tests de remise, des tests nonfonctionnels, des tests négatifs pour lesquels il faut préparer des données de test, documenter le tout'),
(38, 8, 'B.5.2', 3, 'Respecter les standards'),
(39, 8, 'B.5.3', 4, 'Elaborer la documentation technique pour les utilisateurs, et l’exploitation'),
(40, 8, 'B.5.4', 4, 'Organiser des révisions en phase, déroulement itératif afin de respecter la planification temporel et de qualité'),
(41, 9, 'B.6.1', 4, 'Planifier l’introduction avec la procédure définie, y compris, l’assurance, le cas échéant, d’un retour à la situation initiale en cas de besoin'),
(42, 9, 'B.6.2', 3, 'Organiser et transmettre la migration des données avec les éventuelles conversions nécessaires'),
(43, 9, 'B.6.3', 3, 'Préparer la remise de la production'),
(44, 9, 'B.6.4', 3, 'Organiser en temps voulu l’instruction et l’information des utilisateurs'),
(45, 10, 'C.1.1', 4, 'Identifier des entités et leurs relations, en élaborer un modèle en plusieurs niveaux d‘abstraction (normaliser)'),
(46, 10, 'C.1.2', 4, 'Décrire des entités et déterminer des types de données'),
(47, 10, 'C.1.3', 4, 'Convertir les exigences dans des modèles standards de notation (UML, ERD etc.)'),
(48, 10, 'C.1.4', 4, 'Formuler des données adéquates de test (tenir compte des conditions limites)'),
(49, 11, 'C.2.1', 4, 'Choisir un modèle approprié de base de données (relationnelle, hiérarchique, etc.) et déterminer le produit (DBMS)'),
(50, 11, 'C.2.2', 4, 'Elaborer le modèle physique (par ex. DDL, Referential Integrity, Constraints) et dénormaliser (Performance)'),
(51, 11, 'C.2.3', 4, 'Exécuter les tests de charge et de performance, optimiser en conséquence et assurer la possibilité de maintenance'),
(52, 11, 'C.2.4', 3, 'Assurer la sécurité des données (sauvegarde, disponibilité, etc.) et la protection des données (e.a. les droits d’accès)'),
(53, 11, 'C.2.5', 4, 'Planifier et exécuter la migration de données'),
(54, 12, 'C.3.1', 3, 'Déterminer les interfaces et technologies d‘accès (par ex. SQL statiques/dynamiques, ADO, HQL, OR-Mapper, Stored Procedures, etc.)'),
(55, 12, 'C.3.2', 4, 'Appliquer le concept de transaction et programmer l’accès aux données'),
(56, 12, 'C.3.3', 4, 'Vérifier l’accès des données en performance et exigences, le cas échéant, optimiser'),
(57, 12, 'C.3.4', 4, 'Faire le test de remise et vérifier les résultats, au besoin, entreprendre les mesures nécessaires'),
(58, 13, 'D.1.1', 3, 'Remarques: comme ces activités ne peuvent pas être effectuées dans toutes les entreprises formatrices, il n’y a pas d’objectifs évaluateurs obligatoires qui sont fixés. Toutes les actions ont lieu dans le cadre des cours interentreprises'),
(59, 14, 'E.1.1', 4, 'Analyser la quantité de travail sur la base des documents existants et élaborer une planification du travail'),
(60, 14, 'E.1.2', 3, 'Prendre les mesures de préparation en vue de la résolution, élaborer les checklist et la planification, documenter le déroulement, élaborer la liste de matériel, etc'),
(61, 14, 'E.1.3', 3, 'Procurer les droits d’accès, les licences, etc. et mettre à disposition l’environnement de travail'),
(62, 14, 'E.1.4', 4, 'Exécuter les tâches conformément à la planification, déterminer régulièrement l’état du projet et en rapporter'),
(63, 14, 'E.1.5', 3, 'Tester toutes les fonctions et installations de manière conséquente durant le travail, et les documenter selon des standards'),
(64, 14, 'E.1.6', 4, 'Instruire les utilisateurs et élaborer à cet effet une bonne documentation technique'),
(65, 14, 'E.1.7', 3, 'Assurer la remontée des données du client, des tests et systèmes, etc'),
(66, 15, 'E.2.1', 3, 'Présenter les méthodes de gestion de projets de l’entreprise'),
(67, 15, 'E.2.2', 4, 'Organiser le travail selon les méthodes usuelles de gestion de projets dans l’entreprise, et élabore une planification réaliste en temps et ressources'),
(68, 15, 'E.2.3', 3, 'Définir et distribuer des tâches partielles, respectivement prendre en charge de telles tâches et les exécuter'),
(69, 15, 'E.2.4', 3, 'Présenter et démontrer des solutions'),
(70, 15, 'E.2.5', 4, 'Elaborer le rapport final du projet (Réflexion en méthodes, déroulement, temps et ressources)'),
(71, 15, 'E.2.6', 4, 'Refléter le travail du projet et assurer le transfert des connaissances'),
(72, 16, 'E.3.1', 3, 'Communiquer dans le cadre du projet avec toutes les personnes concernées par le biais de contacts réguliers et discussions sur l’avancement des travaux, les interfaces, les nouvelles solutions, les problèmes'),
(73, 16, 'E.3.2', 5, 'Entretiens par des contacts réguliers et discussions avec les clients, respectivement le mandant, sur les souhaits, les questions et besoins, vérifier à l’aide de questions ciblées si les souhaits ont été correctement et précisément saisis'),
(74, 17, 'A.1.1', 4, 'Etre capable de recevoir, comprendre, planifier et mettre en œuvre un mandat client (organisation, méthodologie, ergonomie, optimisation de l’énergie)'),
(75, 17, 'A.1.2', 4, 'Evaluation et acquisition de matériel et logiciels appropriés, et les présenter à son supérieur. Après quoi, ils acquièrent le tout, y compris les licences nécessaires'),
(76, 17, 'A.1.3', 3, 'Pouvoir entreprendre des configurations de base en tenant compte des mesures de sécurité et de protection des données y.c. le filtrage des contenus, malware, et virus), pouvoir prendre comme aide un ouvrage de référence avec un langage standard et une langue supplémentaire (D/E ou F/I/E)'),
(77, 17, 'A.1.4', 3, 'Pouvoir insérer des composants TIC dans des réseaux selon directives et avec des connaissances sur les technologies actuelles'),
(78, 17, 'A.1.5', 3, 'Pouvoir installer, manuellement ou automatiquement, des applications selon directives du client en tenant compte des systèmes environnants et des aspects techniques des licences, ainsi que migrer des données'),
(79, 17, 'A.1.6', 3, 'Mettre hors service des composants TIC et les éliminer professionnellement en tenant compte de la protection des données, des lignes directrices et des procédures d’entreprise'),
(80, 17, 'A.1.7', 6, 'Contrôle des fonctions et remise au client (test final et protocole de remise)'),
(81, 18, 'A.2.1', 4, 'Etre capable de recevoir un mandat client, planifier la mise en œuvre (organisation, méthodologie, ergonomie, optimisation énergétique)'),
(82, 18, 'A.2.2', 4, 'Entreprendre l’évaluation et l’acquisition de matériel et logiciels appropriés en tenant compte des prescriptions et des compatibilités de l‘entreprise (y.c. licences), et les présenter à son supérieur'),
(83, 18, 'A.2.3', 3, 'Acquérir le matériel, les logiciels et les licences'),
(84, 18, 'A.2.4', 3, 'Entreprendre des configurations de base et pouvoir implémenter des services de base (par ex. accès distant, synchronisation des données, etc.) en tenant compte des mesures de sécurité et de protection des données'),
(85, 18, 'A.2.5', 3, 'Tester et documenter la configuration/disponibilité et fonctionnalité de la nouvelle installation'),
(86, 19, 'A.3.1', 4, 'Etre capable de recevoir, comprendre, planifier et mettre en œuvre un mandat client (organisation, méthodologie)'),
(87, 19, 'A.3.2', 4, 'Evaluation et acquisition des appareils appropriés (imprimante, copieur, NAS, appareils multimédia, etc.) en tenant compte des prescriptions et des compatibilités de l‘entreprise, et les présenter à son supérieur'),
(88, 19, 'A.3.3', 3, 'Acquérir les appareils et entreprendre les configurations de base (accès, droits, acomptes, rapports etc.)'),
(89, 19, 'A.3.4', 3, 'Tester et documenter la configuration/disponibilité et la fonctionnalité des nouveaux matériels et logiciels installés'),
(90, 19, 'A.3.5', 3, 'Instruire les utilisateurs sur le maniement et les caractéristiques des nouveaux appareils'),
(91, 20, 'B.1.1', 4, 'Clarifier et régler la situation et l’accès, rack, énergie électrique, besoins de climatisation, UPS, connexion au réseau, respectivement les faire installer'),
(92, 20, 'B.1.2', 3, 'acquérir le matériel et logiciels, entreprendre les configurations et services de base des serveurs (par ex. DHCP, DNS, accès distant, etc.) en tenant compte des mesures de sécurité et de protection des données, pouvoir prendre comme aide un ouvrage de référence avec un langage standard et une langue supplémentaire (D/E ou F/I/E)'),
(93, 20, 'B.1.3', 3, 'Tester et documenter la configuration/disponibilité et la fonctionnalité des nouveaux matériels et logiciels installés'),
(94, 21, 'B.2.1', 4, 'Ingénierie des besoins: reconnaître et classer les besoins du client y.c. de la sécurité, transférer sur la topologie du réseau en tenant compte des avantages et inconvénients d’une solution, possibilité d’extension, maintenance, prix, distance, etc'),
(95, 21, 'B.2.2', 4, 'Planification et concept de la structure réseau appropriée (Provider, WLAN, Switch, Router etc.) en tenant compte des besoins en largeur de bande, des médias, de la disponibilité et des services (Voice, unified Communication, Video, etc.), présenter la solution au supérieur'),
(96, 21, 'B.2.3', 3, 'Installer, mettre en réseau et configurer des composants (par ex. VLAN, Routing)'),
(97, 21, 'B.2.4', 3, 'Visualiser et documenter les réseaux et leur topologie'),
(98, 22, 'B.3.1', 4, 'Conseil à la clientèle en regard de la sécurité et l’archivage des données, recueillir et analyser les besoins du client et, au besoin, aviser sur les effets du risque'),
(99, 22, 'B.3.2', 4, 'Elaboration d’un concept en tenant compte de toutes les contraintes telles que les besoins de l’entreprise, les règles légales, sécurité et protection des données, les us et coutumes de la branche, les médias, les performances et la durée de vie'),
(100, 22, 'B.3.3', 3, 'Installation des systèmes en tenant compte des précautions nécessaires de sécurité (droits d’accès, sécurité des données, reprise après sinistre), performance, et installer la disponibilité'),
(101, 22, 'B.3.4', 3, 'Tester, valider et exécuter la restauration des données, documenter le travail'),
(102, 23, 'C.1.1', 3, 'Lire et interpréter des schémas (plan électrique, plan réseau) et pouvoir documenter les modifications exécutées'),
(103, 23, 'C.1.2', 3, 'Surveiller et administrer le réseau (monitoring: performance, flux de données, stabilité, malware, firewall, etc.)'),
(104, 23, 'C.1.3', 4, 'Poursuivre les incohérences et, le cas échéant, proposer des mesures appropriées, resp. les prendre selon les directives de l’entreprise'),
(105, 23, 'C.1.4', 3, 'Concevoir et réaliser des extensions réseau en tenant compte des coûts d’acquisition et d’exploitation et éliminer dans les règles les appareils remplacés'),
(106, 23, 'C.1.5', 3, 'Découvrir et éliminer toutes les pannes possibles de connexion (switchs, routeurs, etc.), y.c. mettre en œuvre des scénarios de secours selon checklist'),
(107, 24, 'C.2.1', 3, 'Exécuter les tâches régulières de maintenance, d’entretien et de surveillance (journalières, hebdomadaires, mensuelles, etc.), y.c. l’exécution régulière de mise à jour, contrôle de génération, ressources selon un déroulement par checklist'),
(108, 24, 'C.2.2', 3, 'Assurer la sécurité système et d’exploitation. Respecter les droits, vérifier les règles d’authentification et d’autorisation et les mettre en œuvre de manière conséquente'),
(109, 24, 'C.2.3', 3, 'Surveiller des services de serveurs (par ex. gestion des logfiles, queues d‘impression, messagerie/données, etc.) et entreprendre les mesures nécessaires'),
(110, 24, 'C.2.4', 3, 'Installation et configuration des services de communication et groupeware (par ex.sharepoint, Lotus Notes, etc.), gestion des délais, des tâches et des documents'),
(111, 24, 'C.2.5', 3, 'Tester et documenter la fonctionnalité, les performances et la sécurité des systèmes'),
(112, 25, 'C.3.1', 4, 'Accueillir, comprendre, planifier et mettre en œuvre un mandat client (organisation, méthodologie)'),
(113, 25, 'C.3.2', 4, 'Concept des droits d’accès y.c. élaborer le partage en tenant compte des exigences de la communication en réseau (applications d’impression, de téléphonie, VPN, spécifiques à l’entreprise)'),
(114, 25, 'C.3.3', 3, 'Installer, mettre en œuvre et ajuster aux spécificités du client un service d’annuaire en tenant compte de la protection et de la sécurité des données ainsi que des conditions d’accès'),
(115, 25, 'C.3.4', 3, 'Tester et documenter la fonctionnalité'),
(116, 26, 'C.4.1', 4, 'Accueillir, comprendre et planifier un mandat du client, planifier la mise en œuvre (organisation, méthodologie)'),
(117, 26, 'C.4.2', 4, 'Elaborer un concept de la performance et des interfaces en tenant compte de toutes les dépendances des services disponibles, y.c. les questions de droits d’accès et logiciels appropriés'),
(118, 26, 'C.4.3', 3, 'Installer les services de communication et groupeware (par ex. serveur de messagerie, serveur VOIP, DMS, etc.) en tenant compte des précautions nécessaires de sécurité (protection virale, filtrage des contenus et spams), de performance et de disponibilité'),
(119, 26, 'C.4.4', 3, 'Tester et documenter la configuration, la disponibilité, la fonctionnalité du matériel et logiciels nouvellement installés'),
(120, 27, 'D.1.1', 4, 'Introduction de nouveaux collaborateurs dans la structure TIC de l’entreprise, instruire les clients/collaborateurs lors de l’introduction de nouveaux matériels et logiciels, ainsi que de nouveaux outils'),
(121, 27, 'D.1.2', 3, 'Conseiller et soutenir les utilisateurs lors de la mise en œuvre d’automatisations bureautiques (par ex. mise en place de nouveaux outils, ou macros pour simplifier les tâches)'),
(122, 27, 'D.1.3', 3, 'Expliquer les particularités spécifiques à l’entreprise dans le comportement avec les données et les lignes directrices de la sécurité'),
(123, 27, 'D.1.4', 3, 'Elaborer la documentation pour les utilisateurs'),
(124, 28, 'D.2.1', 3, 'Accueillir et saisir les demandes et problèmes des clients, poser les bonnes questions, afin de cerner et résoudre rapidement le problème'),
(125, 28, 'D.2.2', 3, 'Support téléphonique ou par accès distant, si nécessaire sur place chez les utilisateurs, poser des questions ciblées en cas de problèmes techniques'),
(126, 28, 'D.2.3', 3, 'Conseiller les utilisateurs sur la manière de résoudre un problème ou comment ils peuvent faciliter leurs activités avec de nouveaux outils'),
(127, 28, 'D.2.4', 3, 'Expliquer au client le comportement avec les données et les lignes directrices de la sécurité'),
(128, 29, 'E.1.1', 3, 'Elaborer un concept de tests comme base pour un développement efficace et l’assurance qualité d’une nouvelle application'),
(129, 29, 'E.1.2', 4, 'Appliquer des méthodes pour la détermination de cas de tests'),
(130, 29, 'E.1.3', 3, 'Mettre à disposition, sauvegarder et documenter les données des tests'),
(131, 29, 'E.1.4', 3, 'Elaborer et exécuter des cas de tests (Blackbox), automatiser dans les cas possible'),
(132, 29, 'E.1.5', 3, 'Saisir les résultats dans un protocole de tests en vue d’une répétition'),
(133, 29, 'E.1.6', 3, 'Evaluer les résultats des tests et, le cas échéant, en déduire des mesures'),
(134, 29, 'E.1.7', 3, 'Garantir que toutes les fonctions ont été testées et que les éventuelles erreurs ont été corrigées'),
(135, 30, 'E.2.1', 4, 'Fonctionnalité conviviales, par ex. la même fonction déclenche toujours la même action, lorsque l’on feuillette, les informations introduites restent, etc'),
(136, 30, 'E.2.2', 4, 'Evaluation des modes de déroulement et des applications appropriées'),
(137, 30, 'E.2.3', 4, 'Programmer les applications en tenant compte des suites de tests, de débogage, de dépannage, de maintenance, etc. et documenter de manière continue'),
(138, 30, 'E.2.4', 3, 'Utiliser des standards et processus de développement'),
(139, 30, 'E.2.5', 3, 'Appliquer des méthodes de projets (PAP, Jackson, diagramme d‘état, diagramme de classe) et les Softwaredesign-Patterns'),
(140, 30, 'E.2.6', 3, 'Respecter la convention des codes'),
(141, 30, 'E.2.7', 3, 'Editer, documenter du code source (par ex. code en ligne, ..) et documenter en vue de faciliter la maintenance'),
(142, 30, 'E.2.8', 3, 'Tester l’application et tout documenter'),
(143, 31, 'E.3.1', 4, 'Prendre en compte des exigences standards et ergonomiques, voir et toucher. Atteindre un bon effet convivial lors de l’utilisation des nouvelles applications'),
(144, 31, 'E.3.2', 3, 'Prendre en compte les CD/CI (Corporate Design/Corporate identity)'),
(145, 31, 'E.3.3', 3, 'Développer de manière conviviale, validation des champs de saisie, aide à la saisie des entrées'),
(146, 31, 'E.3.4', 3, 'Codage GUI convivial, séparation des éléments utilisateurs du code'),
(147, 31, 'E.3.5', 3, 'Prendre en compte les conditions de communication, par ex. communication asynchrone et veiller à de bonnes performances'),
(148, 31, 'E.3.6', 3, 'Tester l’application de manière exhaustive et tout documenter'),
(149, 32, 'E.4.1', 4, 'Choisir un modèle approprié de base de données (relationnelle, hiérarchique, etc.) et déterminer le produit (DBMS)'),
(150, 32, 'E.4.2', 3, 'Elaborer le modèle physique (par ex. DDL, Referential Integrity, Constraints) et dénormaliser (Performance)'),
(151, 32, 'E.4.3', 3, 'Exécuter les tests de charge et de performance, optimiser en conséquence et assurer la possibilité de maintenance'),
(152, 32, 'E.4.4', 3, 'Assurer la sécurité des données (sauvegarde, disponibilité, etc.) et la protection des données (e.a. les droits d’accès)'),
(153, 32, 'E.4.5', 4, 'Planifier et exécuter la migration de données'),
(154, 33, 'E.5.1', 3, 'Déterminer les interfaces et technologies d‘accès (par ex. SQL statiques/dynamiques, ADO, HQL, OR-Mapper, Stored Procedures, etc.)'),
(155, 33, 'E.5.2', 3, 'Appliquer le concept de transaction et programmer l’accès aux données'),
(156, 33, 'E.5.3', 3, 'Vérifier l’accès des données en performance et exigences, le cas échéant, optimiser'),
(157, 33, 'E.5.4', 4, 'Faire le test de remise et vérifier les résultats, au besoin, entreprendre les mesures nécessaires'),
(158, 34, 'F.1.1', 3, 'Analyser et comprendre l’étendue de travail, élaborer une planification des travaux'),
(159, 34, 'F.1.2', 3, 'Prendre les mesures de préparation en vue de la résolution, élaborer les checklist et la planification, documenter le déroulement, élaborer la liste de matériel, etc'),
(160, 34, 'F.1.3', 3, 'Acquérir et ordonner du matériel, le préparer pour l’installation, etc. y.c. les solutions de secours'),
(161, 34, 'F.1.4', 3, 'Exécuter les tâches, conformément à la planification, efficacement de pas à pas'),
(162, 34, 'F.1.5', 3, 'Planifier et exécuter des tests, lesquels seront documentés dans l’inventaire des nouvelles installations'),
(163, 34, 'F.1.6', 3, 'Remettre l’installation et faire signer le protocole de remise au client'),
(164, 34, 'F.1.7', 3, 'Instruire les utilisateurs sur les modifications de leurs applications'),
(165, 35, 'F.2.1', 4, 'Analyser et comprendre l’étendue de travail de sa propre contribution, élaborer une planification des travaux en tenant compte des ressources disponibles'),
(166, 35, 'F.2.2', 3, 'Elaborer le mandat selon les directives en termes de délai et dans le cadre du budget, subdiviser les résultats dans le projet global'),
(167, 35, 'F.2.3', 3, 'Informer constamment la direction de projet de sa propre initiative sur les modifications et déviations'),
(168, 35, 'F.2.4', 3, 'Elaborer la documentation du projet, les rapports, la correspondance du projet, etc. selon directives'),
(169, 35, 'F.2.5', 3, 'Mettre à disposition des collègues ses propres expériences issues du projet'),
(170, 36, 'F.3.1', 3, 'Communiquer dans le cadre du projet avec toutes les personnes concernées par le biais de contacts réguliers et discussions sur l’avancement des travaux, les interfaces, les nouvelles solutions, les problèmes'),
(171, 36, 'F.3.2', 3, 'Entretiens par des contacts réguliers et discussions avec les clients, respectivement le mandant, sur les souhaits, les questions et besoins, vérifier à l’aide de questions ciblées si les souhaits ont été correctement et précisément saisis');

--
-- Déchargement des données de la table `operational_competence`
--

INSERT INTO `operational_competence` (`id`, `fk_competence_domain`, `name`, `symbol`, `methodologic`, `social`, `personal`) VALUES
(1, 1, 'Analyser, structurer et documenter les exigences ainsi que les besoins', 'A1', 'Travail structuré, documentation adéquate', 'Comprendre et sentir les problèmes du client, communication avec des partenaires', 'Fiabilité, autoréflexion, interrogation constructive du problème'),
(2, 1, 'Elaborer diverses propositions de solutions incluant les interfaces utilisateurs requises', 'A2', 'Travail structuré, documentation adéquate, appliquer des techniques de créativité, techniques de décision', 'Comprendre et sentir les problèmes du client, communication avec des partenaires, modération, travail en réseau', 'Interrogation constructive du problème, s’informer de manière autonome sur les diverses solutions'),
(3, 1, 'Vérifier l’exhaustivité des exigences et des besoins dans les propositions de solution choisies', 'A3', 'Techniques de validation, assurance qualité, techniques de présentation  et de démonstration', '', 'Précision dans le travail'),
(4, 2, 'Elaborer un concept de tests, mettre en application divers déroulements de tests et tester systématiquement les applications ', 'B1', '', 'Capacité de critique mutuelle', 'Développer préventivement, estimer les conséquences'),
(5, 2, 'Mettre en œuvre des directives d’architecture dans un projet concret', 'B2', '', '', 'Capacités d’abstraction'),
(6, 2, 'Développer et documenter des applications conformément aux besoins du client en utilisant des modèles appropriés de déroulement', 'B3', 'Travail structuré et systématique, capacités d‘abstraction, compétences de modélisation, acquisition d‘informations, développer efficacement, tenir compte de la charge du réseau', 'Travail en groupe, capacités de communication, capacités de critiques, orientation client, disponibilités pour la reprise de l‘existant', 'Penser économies d‘entreprises, persévérance, conscience de la qualité, capacité de compréhension rapide'),
(7, 2, 'Implémenter des applications et des interfaces utilisateurs en fonction des besoins du client et du projet', 'B4', 'Orientation client, développement approprié au marché, appliquer des techniques innovatrices', 'Travail en groupe, empathie', 'Capacités innovatrices, créativité'),
(8, 2, 'Garantir la qualité des applications', 'B5', 'Travail reproductible, description propres des versions de l‘application, gestion de projets', 'Capacité de critiques et de conflits, empathie', 'Vérification autocritique des résultats, méticulosité'),
(9, 2, 'Préparer et mettre en œuvre l’introduction des applications', 'B6', 'Gestion de projets', 'Capacités de communication, travail en réseau, déroulement sensible', 'Conscience de la responsabilité'),
(10, 3, 'Identifier et analyser des données, puis développer avec des modèles de données appropriés', 'C1', 'Déroulement structuré, comportement avec des outils de présentation, développement itératif', 'Communication avec des clients, travail en groupe', 'Précision, abstraction, remise en question critique'),
(11, 3, 'Mettre en œuvre un modèle de données dans une base de données', 'C2', '', '', 'Capacité d’abstraction'),
(12, 3, 'Accéder à des données à partir d’applications avec un langage approprié', 'C3', '', '', ''),
(13, 4, 'Installer et configurer, selon des directives, des postes de travail ainsi que des services de serveurs dans l’exploitation locale du réseau', 'D1', 'Considération de la valeur utile, déroulement systématique, check liste, méthode de travail durable économiquement, écologiquement, socialement', 'Orientation client, communication écrite et orale', 'Autoréflexion critique'),
(14, 5, 'Préparer, structurer et documenter des travaux et mandats de manière systématique et efficace', 'E1', 'Déroulement structuré, déroulement systématique selon check list, documentation des travaux', 'Travail en groupe, prêt à aider, intérêt global, tenir une conversation en langue étrangère, compréhension des rôles', 'Fiabilité, bon comportement, capacité élevée de charges, s’identifier à l’entreprise'),
(15, 5, 'Collaborer à des projets et travailler selon des méthodes de projets', 'E2', 'Méthodes de travail, pensée transversale, considération des variantes, analyse des grandeurs utiles, pensée en réseau, techniques de présentation et de ventes', 'Faculté de travail en groupe, développer et mettre en œuvre selon les besoins, communiquer selon le niveau et les utilisateurs, comportement respectueux et adapté avec les collaborateurs', 'Réflexion, disposé à l‘apprentissage, intérêts, capacité decritiques, capacité d’endurance jusqu’à la conclusion'),
(16, 5, 'Dans le cadre de projets, communiquer de manière ciblée et adaptée à l’interlocuteur', 'E3', 'Méthodes de travail, pensée en réseau, techniques de présentation et de ventes', 'Travail en groupe, communiquer conformément au niveau et aux utilisateurs, comportement respectueux et approprié avec toutes les personnes de contact à tous les niveaux, communication précise\r\n', 'Réflexion, prêt à apprendre, intérêt, capacité de critiques, capacité de résistance'),
(17, 6, 'Evaluer et mettre en service une place de travail utilisateur', 'A1', 'Analyse des valeurs utiles, déroulement systématique, faire de checklist, technique commerciale, méthode durable de travail (économiquement, écologiquement, socialement)', 'Orientation client, communication écrite et orale', 'Conscience de la responsabilité, fiabilité, autoréflexion critique'),
(18, 6, 'Installer et synchroniser sur le réseau interne des appareils mobiles des utilisateurs', 'A2', 'Analyse des valeurs utiles, déroulement systématique, faire de checklist, technique commerciale, méthode durable de travail (économiquement, écologiquement, socialement)', 'Orientation client, communication écrite et orale, comportement convivial avec le client', 'Conscience de la responsabilité, fiabilité, autoréflexion critique'),
(19, 6, 'Connecter et configurer des appareils périphériques', 'A3', 'Analyse des valeurs utiles, déroulement systématique, faire de checklist, technique commerciale, méthode durable de travail (économiquement, écologiquement, socialement)', 'Orientation client, communication écrite et orale, langage adapté au client', 'Conscience de la responsabilité, fiabilité, autoréflexion critique'),
(20, 7, 'Mettre en service des systèmes serveurs', 'B1', 'Analyse des valeurs utiles, déroulement systématique, faire de checklist, technique commerciale, méthode durable de travail (économiquement, écologiquement, socialement)', 'Orientation client, communication écrite et orale', 'Autoréflexion critique'),
(21, 7, 'Installer des réseaux et leurs topologies', 'B2', 'Déroulement analytique, principe de Pareto, techniques de visualisation, diagrammes, techniques de décision', 'Faire des entretiens professionnels en anglais', 'Méthode précise de travail, conscience de la responsabilité, capacités d’abstraction'),
(22, 7, 'Elaborer et mettre en œuvre des concepts de sécurité des données, de sécurité système et d’archivage', 'B3', 'Actions préventives', 'Conseil', 'Penser et travailler de manière disciplinée, comportement dans les situations de stress'),
(23, 8, 'Assurer la maintenance de réseaux et les développer', 'C1', 'déroulement systématique, faire de checklist, technique commerciale, méthode durable de travail (économiquement, écologiquement, socialement)', '', 'Précision, fiable, actions attentives'),
(24, 8, 'Assurer la maintenance et administrer des serveurs', 'C2', 'Pensée systématique et préventive, considération de l’ensemble, remise en question systématique, travail durable (économiquement, écologiquement, socialement)', 'Travail en groupe, entretien professionnel en anglais', 'Travail patient et autocritique, conscience de la qualité, autoréflexion, éthique, discrétion, discipline'),
(25, 8, 'Planifier, mettre en œuvre des services d’annuaires et des autorisations', 'C3', 'Techniques d’interrogation', 'Empathie', 'Comprendre et interpréter des documents anglais'),
(26, 8, 'Mettre en service et configurer des services de communication et de soutien des travaux de groupe (groupeware)', 'C4', 'Techniques d’entretien, pensée systématique et préventive, considération de l’ensemble, remise en question systématique', 'Travailler en groupe', 'Travail patient et auto-critique, sens de la qualité, autoreflexion'),
(27, 9, 'Instruire et aider les utilisateurs dans l’utilisation des moyens informatiques', 'D1', 'Techniques d’interrogation, déroulement structuré, travailler selon checklist, établir des documents de première aide', 'Capacité de communication, comportement avec autrui en situation de stress, comportement selon le niveau hiérarchique', 'Garder le calme, résistance au stress, maîtriser sa propre nervosité'),
(28, 9, 'Assurer des tâches de support par le biais du contact client et résoudre les problèmes sur place', 'D2', 'Techniques d’interrogation, déroulement structuré, travailler selon checklist', 'Capacité de communication, comportement avec autrui en situation de stress, comportement selon le niveau hiérarchique', 'Garder le calme, résistance au stress, maîtriser sa propre nervosité'),
(29, 10, 'Elaborer des concepts de tests, mettre en application divers déroulements de tests et tester systématiquement les applications ', 'E1', '', 'Capacité de critique mutuelle', 'Développer préventivement, estimer les conséquences'),
(30, 10, 'Développer et documenter des applications de manière conviviale en utilisant des modèles appropriés de déroulement', 'E2', 'Utiliser efficacement l’environnement logiciels, travail systématique et structuré, capacités d’abstraction, compétences en modélisation, acquisition d’informations, développer efficacement, observer la charge du réseau', 'Travail en groupe, capacités de communication, de critique, de compromis, orientation client, disponibilité, reprise de l’existant', 'Pensée économique, capacité de résistance, conscience de la qualité, capacité de saisie rapide'),
(31, 10, 'Développer et implémenter des interfaces utilisateurs pour des applications selon les besoins du client', 'E3', 'Orientation client, concept centré sur l’utilisateur, application de techniques innovantes', 'Travail en groupe, empathie', 'Capacité d’innovation, créativité'),
(32, 10, 'Mettre en œuvre des modèles de données dans une base de données', 'E4', '', '', 'Capacité d’abstraction'),
(33, 10, 'Accéder à des données à partir d’applications avec un langage approprié', 'E5', '', '', ''),
(34, 11, 'Préparer, structurer, exécuter et documenter des travaux et des mandats de manière systématique et efficace', 'F1', 'Déroulement structuré, déroulement systématique selon checklist, documentation des travaux', 'Travail en groupe, prêt à aider, intérêt global, tenir une conversation en langue étrangère, compréhension des rôles', 'Fiabilité, bon comportement, capacité élevée de charges, s’identifier à l’entreprise'),
(35, 11, 'Collaborer à des projets', 'F2', 'Déroulement structuré, déroulement systématique selon checklist, documentation des travaux', 'Travail en groupe, prêt à aider, intérêt global, tenir une conversation en langue étrangère, compréhension des rôles', 'Fiabilité, bon comportement, capacité élevée de charges, s’identifier à l’entreprise, réfléchir en commun dans le projet'),
(36, 11, 'Dans le cadre de projets, communiquer de manière ciblée et adaptée à l’interlocuteur', 'F3', 'Méthodes de travail, pensée en réseau, techniques de présentation et de ventes', 'Travail en groupe, communiquer conformément au niveau et aux utilisateurs, comportement respectueux et approprié avec toutes les personnes de contact à tous les niveaux, communication précise', 'Réflexion, prêt à apprendre, intérêt, capacité de critiques, capacité de résistance');

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `fk_user_type`, `username`, `password`, `archive`, `date_creation`) VALUES
(1, 1, 'admin', '$2y$10$tUB5R1MGgbO.zD//WArnceTY8IgnFkVVsudIdHBxIrEXJ2z3WBvcK', 0, '2020-03-30 08:33:55'),
(2, 2, 'Test', '$2y$10$m0gUwFakcEURjwTI9oXpbOCo7dgRE5wVdzmHVcEDIjXXc5Jlm2neS', 0, '2020-05-27 06:10:22'),
(3, 3, 'TestApprenti', '$2y$10$NNVxL6V70orQ.dnWOEoaauXUhXgRztKRqwRMPVHBbS5AgYnGKqNx2', 0, '2020-05-29 09:15:13'),
(4, 3, 'TestApprentiArchive', '$2y$10$99rUcKM10ipdv0XNyQKKAuObxRHn2t/l/fH3aC/aBJUi5AQ9ZZh8q', 1, '2020-05-29 11:22:37');

--
-- Déchargement des données de la table `user_course_status`
--

INSERT INTO `user_course_status` (`id`, `name`) VALUES
(1, 'En cours'),
(2, 'Réussi'),
(3, 'Échouée'),
(4, 'Suspendue'),
(5, 'Abandonnée');

--
-- Déchargement des données de la table `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `access_level`) VALUES
(1, 'Administrateur', 4),
(2, 'Formateur', 2),
(3, 'Apprenti', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
