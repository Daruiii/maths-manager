-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Hôte : dm2blc.myd.infomaniak.com
-- Généré le :  mar. 16 avr. 2024 à 00:21
-- Version du serveur :  10.6.17-MariaDB-1:10.6.17+maria~deb11-log
-- Version de PHP :  7.4.33

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
-- Base de données :  `dm2blc_mathsManager`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('migmigl@gmail.com|37.165.196.81', 'i:2;', 1713202981),
('migmigl@gmail.com|37.165.196.81:timer', 'i:1713202981;', 1713202981);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
CREATE TABLE `chapters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chapters`
--

INSERT INTO `chapters` (`id`, `class_id`, `title`, `description`, `created_at`, `updated_at`, `theme`) VALUES
(5, 2, 'Raisonnement par récurrence', NULL, '2024-04-04 19:04:55', '2024-04-04 19:04:55', '#7CE6D0'),
(6, 2, 'Généralités sur les suites', 'Ce chapitre est vu en partie en première. Vous trouverez ici des exercices de perfectionnement et d\'approfondissement.', '2024-04-06 12:32:27', '2024-04-06 15:47:30', '#7CE6D0'),
(7, 4, 'tedt', NULL, '2024-04-06 18:38:13', '2024-04-06 18:38:13', '#87CBEA'),
(8, 2, 'Limites de suites', NULL, '2024-04-07 06:32:29', '2024-04-07 06:32:29', '#7CE6D0'),
(9, 2, 'Dérivation', NULL, '2024-04-09 18:18:22', '2024-04-09 18:18:22', '#87CBEA'),
(10, 2, 'Convexité', NULL, '2024-04-09 18:18:29', '2024-04-09 18:18:29', '#87CBEA'),
(11, 2, 'Limites de fonctions', NULL, '2024-04-09 18:18:38', '2024-04-09 18:18:38', '#87CBEA'),
(12, 2, 'Continuité', NULL, '2024-04-09 18:18:44', '2024-04-09 18:18:44', '#87CBEA'),
(13, 2, 'Logarithme Népérien', NULL, '2024-04-09 18:18:56', '2024-04-09 18:18:56', '#6CAAEE'),
(14, 2, 'Fonctions trigonométriques', NULL, '2024-04-09 18:19:23', '2024-04-09 18:19:23', '#E6AA74'),
(15, 2, 'Vecteurs dans l\'espace', NULL, '2024-04-09 18:19:35', '2024-04-09 18:19:35', '#E6D07C'),
(16, 2, 'Orthogonalité dans l\'espace', NULL, '2024-04-09 18:19:45', '2024-04-09 18:19:45', '#E6D07C'),
(17, 2, 'Dénombrement', NULL, '2024-04-09 18:19:53', '2024-04-09 18:19:53', '#E67C7C'),
(18, 2, 'Probabilités et Loi Binomiale', NULL, '2024-04-09 18:20:07', '2024-04-09 18:20:07', '#E67C7C'),
(19, 2, 'Loi des grands nombres', NULL, '2024-04-09 18:20:16', '2024-04-09 18:20:16', '#E67C7C'),
(20, 2, 'Primitives, équations différentielles', NULL, '2024-04-09 18:20:37', '2024-04-09 18:20:37', '#6CAAEE'),
(21, 2, 'Intégrales', NULL, '2024-04-09 18:20:47', '2024-04-09 18:20:47', '#6CAAEE');

-- --------------------------------------------------------

--
-- Structure de la table `chapters_exercises_ds`
--

DROP TABLE IF EXISTS `chapters_exercises_ds`;
CREATE TABLE `chapters_exercises_ds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_ds_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chapters_exercises_ds`
--

INSERT INTO `chapters_exercises_ds` (`id`, `chapter_id`, `exercise_ds_id`, `created_at`, `updated_at`) VALUES
(51, 6, 23, NULL, NULL),
(52, 8, 23, NULL, NULL),
(53, 6, 24, NULL, NULL),
(54, 9, 25, NULL, NULL),
(55, 6, 26, NULL, NULL),
(56, 6, 27, NULL, NULL),
(57, 6, 28, NULL, NULL),
(58, 8, 28, NULL, NULL),
(59, 11, 28, NULL, NULL),
(60, 12, 28, NULL, NULL),
(61, 6, 29, NULL, NULL),
(62, 8, 29, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `name`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Première Spé', '1spe', '2024-03-25 04:11:49', '2024-03-25 04:11:49'),
(2, 'Terminale Spé', 'termSpe', '2024-03-25 15:48:29', '2024-03-25 15:54:11'),
(3, 'Maths Expertes', 'mathsExp', '2024-03-25 15:54:33', '2024-03-25 15:54:33'),
(4, 'Tests', 'tests', '2024-03-25 15:54:33', '2024-04-06 14:05:34');

-- --------------------------------------------------------

--
-- Structure de la table `DS`
--

DROP TABLE IF EXISTS `DS`;
CREATE TABLE `DS` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_bac` tinyint(1) NOT NULL DEFAULT 0,
  `exercises_number` int(11) NOT NULL,
  `harder_exercises` tinyint(1) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL,
  `timer` int(11) NOT NULL,
  `chrono` int(11) NOT NULL,
  `status` enum('not_started','ongoing','finished','sent','corrected') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `DS`
--

INSERT INTO `DS` (`id`, `type_bac`, `exercises_number`, `harder_exercises`, `time`, `timer`, `chrono`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(2, 0, 2, 1, 55, 0, 0, 'sent', '2024-04-12 17:59:10', '2024-04-13 00:58:52', NULL),
(25, 1, 4, 0, 115, 0, 0, 'ongoing', '2024-04-13 00:57:27', '2024-04-13 03:00:59', NULL),
(26, 1, 4, 0, 115, 0, 0, 'finished', '2024-04-13 01:25:47', '2024-04-13 01:25:47', NULL),
(27, 0, 2, 0, 55, 0, 0, 'not_started', '2024-04-13 02:41:38', '2024-04-13 02:41:38', NULL),
(36, 1, 4, 0, 117, 117, 0, 'ongoing', '2024-04-13 03:01:18', '2024-04-13 03:01:18', NULL),
(40, 1, 4, 0, 117, 117, 0, 'not_started', '2024-04-15 18:51:26', '2024-04-15 18:51:26', 30),
(54, 0, 1, 0, 30, 0, 0, 'ongoing', '2024-04-15 19:35:54', '2024-04-15 19:37:59', 27);

-- --------------------------------------------------------

--
-- Structure de la table `ds_chapter`
--

DROP TABLE IF EXISTS `ds_chapter`;
CREATE TABLE `ds_chapter` (
  `ds_id` bigint(20) UNSIGNED NOT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ds_chapter`
--

INSERT INTO `ds_chapter` (`ds_id`, `chapter_id`, `created_at`, `updated_at`) VALUES
(2, 5, NULL, NULL),
(2, 9, NULL, NULL),
(25, 5, NULL, NULL),
(25, 6, NULL, NULL),
(25, 7, NULL, NULL),
(25, 8, NULL, NULL),
(25, 9, NULL, NULL),
(25, 10, NULL, NULL),
(25, 11, NULL, NULL),
(25, 12, NULL, NULL),
(25, 13, NULL, NULL),
(25, 14, NULL, NULL),
(25, 15, NULL, NULL),
(25, 16, NULL, NULL),
(25, 17, NULL, NULL),
(25, 18, NULL, NULL),
(25, 19, NULL, NULL),
(25, 20, NULL, NULL),
(25, 21, NULL, NULL),
(26, 5, NULL, NULL),
(26, 6, NULL, NULL),
(26, 7, NULL, NULL),
(26, 8, NULL, NULL),
(26, 9, NULL, NULL),
(26, 10, NULL, NULL),
(26, 11, NULL, NULL),
(26, 12, NULL, NULL),
(26, 13, NULL, NULL),
(26, 14, NULL, NULL),
(26, 15, NULL, NULL),
(26, 16, NULL, NULL),
(26, 17, NULL, NULL),
(26, 18, NULL, NULL),
(26, 19, NULL, NULL),
(26, 20, NULL, NULL),
(26, 21, NULL, NULL),
(27, 5, NULL, NULL),
(27, 7, NULL, NULL),
(27, 20, NULL, NULL),
(36, 5, NULL, NULL),
(36, 6, NULL, NULL),
(36, 7, NULL, NULL),
(36, 8, NULL, NULL),
(36, 9, NULL, NULL),
(36, 10, NULL, NULL),
(36, 11, NULL, NULL),
(36, 12, NULL, NULL),
(36, 13, NULL, NULL),
(36, 14, NULL, NULL),
(36, 15, NULL, NULL),
(36, 16, NULL, NULL),
(36, 17, NULL, NULL),
(36, 18, NULL, NULL),
(36, 19, NULL, NULL),
(36, 20, NULL, NULL),
(36, 21, NULL, NULL),
(40, 5, NULL, NULL),
(40, 6, NULL, NULL),
(40, 7, NULL, NULL),
(40, 8, NULL, NULL),
(40, 9, NULL, NULL),
(40, 10, NULL, NULL),
(40, 11, NULL, NULL),
(40, 12, NULL, NULL),
(40, 13, NULL, NULL),
(40, 14, NULL, NULL),
(40, 15, NULL, NULL),
(40, 16, NULL, NULL),
(40, 17, NULL, NULL),
(40, 18, NULL, NULL),
(40, 19, NULL, NULL),
(40, 20, NULL, NULL),
(40, 21, NULL, NULL),
(54, 11, NULL, NULL),
(54, 6, NULL, NULL),
(54, 8, NULL, NULL),
(54, 9, NULL, NULL),
(54, 10, NULL, NULL),
(54, 12, NULL, NULL),
(54, 13, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ds_exercises`
--

DROP TABLE IF EXISTS `ds_exercises`;
CREATE TABLE `ds_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `header` varchar(255) DEFAULT NULL,
  `multiple_chapter_id` bigint(20) UNSIGNED NOT NULL,
  `harder_exercise` tinyint(1) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 30,
  `name` varchar(255) DEFAULT NULL,
  `statement` text NOT NULL,
  `latex_statement` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ds_exercises`
--

INSERT INTO `ds_exercises` (`id`, `header`, `multiple_chapter_id`, `harder_exercise`, `time`, `name`, `statement`, `latex_statement`, `created_at`, `updated_at`) VALUES
(23, NULL, 14, 0, 100, 'beuche', 'beuche', 'beuche', '2024-04-15 19:09:26', '2024-04-15 19:13:55'),
(24, NULL, 13, 0, 30, 'beuch', 'mldsfk', 'mldsfk', '2024-04-15 19:19:56', '2024-04-15 19:19:56'),
(25, NULL, 1, 0, 30, 'putain', 'putain', 'putain', '2024-04-15 19:29:07', '2024-04-15 19:29:07'),
(26, NULL, 13, 0, 30, 'beuch', 'beuch', 'beuch', '2024-04-15 19:33:19', '2024-04-15 19:33:19'),
(27, NULL, 13, 0, 30, 'beiuch', 'beuchefd', 'beuchefd', '2024-04-15 19:33:34', '2024-04-15 19:33:34'),
(28, NULL, 15, 0, 30, 'fdfsd', 'fsdfsdf', 'fsdfsdf', '2024-04-15 19:34:42', '2024-04-15 19:34:42'),
(29, NULL, 14, 0, 30, 'fdfsdf', 'sdfsdfsd', 'sdfsdfsd', '2024-04-15 19:35:11', '2024-04-15 19:35:11');

-- --------------------------------------------------------

--
-- Structure de la table `ds_exercises_ds`
--

DROP TABLE IF EXISTS `ds_exercises_ds`;
CREATE TABLE `ds_exercises_ds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ds_id` bigint(20) UNSIGNED NOT NULL,
  `ds_exercise_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ds_exercises_ds`
--

INSERT INTO `ds_exercises_ds` (`id`, `ds_id`, `ds_exercise_id`, `created_at`, `updated_at`) VALUES
(174, 54, 27, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `exercises`
--

DROP TABLE IF EXISTS `exercises`;
CREATE TABLE `exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subchapter_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `statement` text NOT NULL,
  `solution` text DEFAULT NULL,
  `clue` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latex_statement` text DEFAULT NULL,
  `latex_solution` text DEFAULT NULL,
  `latex_clue` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exercises`
--

INSERT INTO `exercises` (`id`, `subchapter_id`, `name`, `statement`, `solution`, `clue`, `created_at`, `updated_at`, `latex_statement`, `latex_solution`, `latex_clue`) VALUES
(1, 6, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et \\[u_{n+1} = 2u_n+1\\] \\\\\r\nDémontrer que pour tout entier naturel $n$, $u_n = 2^{n+1}-1$.', 'On pose $\\mathcal{P}(n)$ la propriété : \"$u_n = 2^{n+1}-1$\". \\\\\r\n<ul class=\'point\'>\r\n<li> <span class=\'textbf\'>Initialisation</span> : pour $n=0$ : \\\\\r\n$u_0 = 1$ et \\\\\r\n$2^{0+1}-1 = 2^1-1 = 2-1 = 1$. \\\\\r\nOn a donc bien $u_0 = 2^{0+1}-1$, c\'est à dire <span class=\'latex latex-boxed\'> $\\mathcal{P}(0)$ $\\text{ est vraie.}$</span> \\\\\r\n<li> <span class=\'textbf\'>Hérédité</span> : On suppose $\\mathcal{P}(n)$ vraie pour un $n \\in \\N$ fixé.\r\n\\begin{align*} u_{n+1} &= 2u_n+1 &\\text{par définition} \\\\ &= 2(2^{n+1}-1)+1 &\\text{ par H.R. } \\\\ &= 2\\times 2^{n+1} - 2 + 1 \\\\ &= 2^{n+2} -1 \\end{align*}\r\nOn vient de montrer que $u_{n+1} = 2^{(n+1)+1}-1$ ce qui prouve que <span class=\'latex latex-boxed\'> $\\mathcal{P}(n+1)$ est vraie. </span>\r\n</ul>\r\nLe principe de récurrence conclut. \\\\\r\nAinsi, <span class=\'latex latex-boxed\'> $\\forall n \\in \\N, u_n = 2^{n+1}-1$ </span>', 'Utiliser le fait que $2\\times2^{n+1} = 2^{n+2}$.', '2024-04-04 19:10:46', '2024-04-13 12:18:01', 'Soit $\\un$ la suite définie par $u_0 = 1$ et \\[u_{n+1} = 2u_n+1\\] \\\\\r\nDémontrer que pour tout entier naturel $n$, $u_n = 2^{n+1}-1$.', 'On pose $\\mathcal{P}(n)$ la propriété : \"$u_n = 2^{n+1}-1$\". \\\\\r\n\\itm\r\n\\item \\textbf{Initialisation} : pour $n=0$ : \\\\\r\n$u_0 = 1$ et \\\\\r\n$2^{0+1}-1 = 2^1-1 = 2-1 = 1$. \\\\\r\nOn a donc bien $u_0 = 2^{0+1}-1$, c\'est à dire \\begin{boxed} $\\mathcal{P}(0)$ $\\text{ est vraie.}$\\end{boxed} \\\\\r\n\\item \\textbf{Hérédité} : On suppose $\\mathcal{P}(n)$ vraie pour un $n \\in \\N$ fixé.\r\n\\begin{align*} u_{n+1} &= 2u_n+1 &\\text{par définition} \\\\ &= 2(2^{n+1}-1)+1 &\\text{ par H.R. } \\\\ &= 2\\times 2^{n+1} - 2 + 1 \\\\ &= 2^{n+2} -1 \\end{align*}\r\nOn vient de montrer que $u_{n+1} = 2^{(n+1)+1}-1$ ce qui prouve que \\begin{boxed} $\\mathcal{P}(n+1)$ est vraie. \\end{boxed}\r\n\\fitm\r\nLe principe de récurrence conclut. \\\\\r\nAinsi, \\begin{boxed} $\\forall n \\in \\N, u_n = 2^{n+1}-1$ \\end{boxed}', 'Utiliser le fait que $2\\times2^{n+1} = 2^{n+2}$.'),
(2, 6, NULL, 'Soit $(u_n)$ la suite définie par $u_0 = 1$ et \\[u_{n+1} = 1-2u_n\\]\r\nMontrer que, pour tout entier naturel $n$, $\\;u_n = \\Frac{1}{3}+\\Frac{2}{3}\\times(-2)^n$.', '', '', '2024-04-04 19:23:10', '2024-04-12 11:26:45', 'Soit $(u_n)$ la suite définie par $u_0 = 1$ et \\[u_{n+1} = 1-2u_n\\]\r\nMontrer que, pour tout entier naturel $n$, $\\;u_n = \\Frac{1}{3}+\\Frac{2}{3}\\times(-2)^n$.', NULL, NULL),
(3, 6, NULL, 'Soit $(u_n)$ la suite définie par $u_0 = 2$ et pour tout $n \\in \\N$, \\[u_{n+1} = \\frac{2}{3}u_n + \\frac{1}{3}n+1\\]\r\nMontrer que pour tout entier naturel $n$, $u_n = 2\\parenthese{\\frac{2}{3}}^{n}+n$.', '', '', '2024-04-04 19:32:51', '2024-04-12 21:34:37', 'Soit $(u_n)$ la suite définie par $u_0 = 2$ et pour tout $n \\in \\N$, \\[u_{n+1} = \\frac{2}{3}u_n + \\frac{1}{3}n+1\\]\r\nMontrer que pour tout entier naturel $n$, $u_n = 2\\parenthese{\\frac{2}{3}}^{n}+n$.', NULL, NULL),
(4, 6, 'Suite homographique', 'Soit $(u_n)$ la suite définie par $u_0 = \\frac{1}{2}$ et pour tout entier naturel $n$, \\[u_{n+1} = \\Frac{3u_n}{1+2u_n}\\]\r\nMontrer que pour tout entier naturel $n$ non nul, $u_{n} = \\Frac{3^n}{3^n+1}$.', '', '', '2024-04-04 19:36:40', '2024-04-12 21:35:47', 'Soit $(u_n)$ la suite définie par $u_0 = \\frac{1}{2}$ et pour tout entier naturel $n$, \\[u_{n+1} = \\Frac{3u_n}{1+2u_n}\\]\r\nMontrer que pour tout entier naturel $n$ non nul, $u_{n} = \\Frac{3^n}{3^n+1}$.', NULL, NULL),
(5, 6, NULL, 'Soit $(u_n)$ la suite définie par $u_1 = 2$ et pour tout entier $n \\geqslant 1$, \\[u_{n+1} = 2 - \\Frac{1}{u_n}\\]\r\nMontrer que pour tout $n$ non nul, $u_n = \\Frac{n+1}{n}$.', '', '', '2024-04-04 19:37:51', '2024-04-12 21:34:59', 'Soit $(u_n)$ la suite définie par $u_1 = 2$ et pour tout entier $n \\geqslant 1$, \\[u_{n+1} = 2 - \\Frac{1}{u_n}\\]\r\nMontrer que pour tout $n$ non nul, $u_n = \\Frac{n+1}{n}$.', NULL, NULL),
(6, 6, NULL, 'Soit $\\un$ la suite définie par $u_2 = 3$ et \\[u_{n+1} = \\Frac{3u_n+1}{u_n+3}\\]\r\nMontrer que pour tout entier naturel $n \\geqslant 2$, $u_n = \\Frac{2^n+2}{2^n-2}$.', '', '', '2024-04-04 19:38:18', '2024-04-12 21:35:35', 'Soit $\\un$ la suite définie par $u_2 = 3$ et \\[u_{n+1} = \\Frac{3u_n+1}{u_n+3}\\]\r\nMontrer que pour tout entier naturel $n \\geqslant 2$, $u_n = \\Frac{2^n+2}{2^n-2}$.', NULL, NULL),
(7, 6, NULL, 'Soit $\\un$ la suite définie sur $\\N$ par $u_0 = 4$ et $\\forall n \\in \\N$, $u_{n+1} = \\Frac{4u_n-2}{u_n+1}$. \\\\On admet que pour tout $n \\in \\N$, $u_n > 1$. \\\\\r\nMontrer par récurrence sur $n$ que $\\forall n \\in \\N$, $u_n \\neq 2$.', '', '', '2024-04-04 19:41:59', '2024-04-11 20:20:23', 'Soit $\\un$ la suite définie sur $\\N$ par $u_0 = 4$ et $\\forall n \\in \\N$, $u_{n+1} = \\Frac{4u_n-2}{u_n+1}$. \\\\On admet que pour tout $n \\in \\N$, $u_n > 1$. \\\\\r\nMontrer par récurrence sur $n$ que $\\forall n \\in \\N$, $u_n \\neq 2$.', NULL, NULL),
(8, 6, NULL, 'Soit $(u_n)$ la suite définie par $u_0 = 1$ et pour tout entier naturel $n$, $u_{n+1} = \\sqrt{2u_n}$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_n = 2e^{-\\ln(2)\\times\\parenthese{\\frac{1}{2}}^n}$.', '', '', '2024-04-04 19:42:30', '2024-04-11 20:20:43', 'Soit $(u_n)$ la suite définie par $u_0 = 1$ et pour tout entier naturel $n$, $u_{n+1} = \\sqrt{2u_n}$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_n = 2e^{-\\ln(2)\\times\\parenthese{\\frac{1}{2}}^n}$.', NULL, NULL),
(9, 6, NULL, 'Soit $\\un$ la suite définie par $u_1 = e^{-1}$ et pour tout entier $n \\geqslant 1$, \\\\\r\n$u_{n+1} = e^{-1}\\parenthese{1+\\Frac{1}{n}}u_n$. \\\\\r\nMontrer que pour tout $n \\geqslant 1$, $u_n = \\Frac{n}{e^n}$.', '', '', '2024-04-04 19:43:31', '2024-04-11 20:20:53', 'Soit $\\un$ la suite définie par $u_1 = e^{-1}$ et pour tout entier $n \\geqslant 1$, \\\\\r\n$u_{n+1} = e^{-1}\\parenthese{1+\\Frac{1}{n}}u_n$. \\\\\r\nMontrer que pour tout $n \\geqslant 1$, $u_n = \\Frac{n}{e^n}$.', NULL, NULL),
(10, 6, NULL, 'Soit $(u_n)$ la suite définie par $u_1 = \\frac{3}{4}$ et pour tout entier naturel $n$ non nul,\\\\\r\n$u_{n+1} = u_n \\times \\Frac{(n+1)(n+3)}{(n+2)^2}$. \\\\\r\nMontrer que pour tout entier naturel $n$ non nul, $u_n = \\Frac{n+2}{2(n+1)}$.', '', '', '2024-04-04 19:44:27', '2024-04-11 20:21:04', 'Soit $(u_n)$ la suite définie par $u_1 = \\frac{3}{4}$ et pour tout entier naturel $n$ non nul,\\\\\r\n$u_{n+1} = u_n \\times \\Frac{(n+1)(n+3)}{(n+2)^2}$. \\\\\r\nMontrer que pour tout entier naturel $n$ non nul, $u_n = \\Frac{n+2}{2(n+1)}$.', NULL, NULL),
(11, 6, NULL, 'Soit $\\un$ la suite définie par $u_1 = 1$ et pour tout $n \\geqslant 1$, $u_{n+1} = \\Frac{n+2}{n}u_n$. \\\\\r\nMontrer que pour tout entier naturel $n$ non nul, $u_n = \\Frac{n(n+1)}{2}$.', '', '', '2024-04-04 19:44:46', '2024-04-11 20:21:15', 'Soit $\\un$ la suite définie par $u_1 = 1$ et pour tout $n \\geqslant 1$, $u_{n+1} = \\Frac{n+2}{n}u_n$. \\\\\r\nMontrer que pour tout entier naturel $n$ non nul, $u_n = \\Frac{n(n+1)}{2}$.', NULL, NULL),
(12, 6, NULL, 'Soit $(u_n)$ la suite définie par $u_0 \\in \\R$ et $u_{n+1} = au_n+b$ avec $a$ et $b$ deux éels non nuls tels que $a \\neq 1$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_n = a^n(u_0-\\alpha) + \\alpha$ avec $\\alpha = \\frac{b}{1-a}$.', '', '', '2024-04-04 19:45:31', '2024-04-11 20:22:11', 'Soit $(u_n)$ la suite définie par $u_0 \\in \\R$ et $u_{n+1} = au_n+b$ avec $a$ et $b$ deux éels non nuls tels que $a \\neq 1$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_n = a^n(u_0-\\alpha) + \\alpha$ avec $\\alpha = \\frac{b}{1-a}$.', NULL, NULL),
(13, 6, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour tout $n \\in \\N$, $u_{n+1} = \\Frac{au_n}{u_n+a}$ avec $a \\in \\Rpe$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_n = \\Frac{a}{a+n}$.', '', '', '2024-04-04 19:45:56', '2024-04-11 20:22:01', 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour tout $n \\in \\N$, $u_{n+1} = \\Frac{au_n}{u_n+a}$ avec $a \\in \\Rpe$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_n = \\Frac{a}{a+n}$.', NULL, NULL),
(14, 6, NULL, 'On appelle dérivée $n$-ième d\'une fonction $f$, la fonction notée $f^{(n)}$ obtenue en dérivant $n$ fois $f$. \\\\\r\nSoit la fonction $f$ définie sur $\\R^*$ par \\[f(x) = \\Frac{1}{x}\\]\r\nMontrer que $f^{(n)}(x) = \\Frac{(-1)^n\\cdot n!}{x^{n+1}}$.', '', '', '2024-04-04 19:46:59', '2024-04-12 21:35:24', 'On appelle dérivée $n$-ième d\'une fonction $f$, la fonction notée $f^{(n)}$ obtenue en dérivant $n$ fois $f$. \\\\\r\nSoit la fonction $f$ définie sur $\\R^*$ par \\[f(x) = \\Frac{1}{x}\\]\r\nMontrer que $f^{(n)}(x) = \\Frac{(-1)^n\\cdot n!}{x^{n+1}}$.', NULL, NULL),
(15, 6, NULL, 'Soit $f$ la fonction définie pour tout $x \\neq 1$ par $f(x) = \\Frac{1}{1-x}$. \\\\\r\nDémontrer que pour tout entier naturel $n \\geqslant1$, $f^{(n)}(x) = \\Frac{ n!}{(1-x)^{n+1}}$\r\noù $f^{(n)}$ désigne la dérivée $n$-ième de $f$.', '', '', '2024-04-04 19:48:02', '2024-04-12 11:27:00', 'Soit $f$ la fonction définie pour tout $x \\neq 1$ par $f(x) = \\Frac{1}{1-x}$. \\\\\r\nDémontrer que pour tout entier naturel $n \\geqslant1$, $f^{(n)}(x) = \\Frac{ n!}{(1-x)^{n+1}}$\r\noù $f^{(n)}$ désigne la dérivée $n$-ième de $f$.', NULL, NULL),
(16, 6, NULL, 'Soient $n \\in \\N^*$, $f$ une fonction $n$ fois dérivable de $\\R$ dans $\\R$, $(a,b) \\in \\R^2$, $g$ la fonction définie sur $\\R$ par \\[g(x) = f(ax+b)\\]\r\nPour $0 \\leqslant k \\leqslant n$ et $x \\in \\R$, donner une expression de la dérivée $k$-ème de $g$ notée $g^{(k)}(x)$.', '', '', '2024-04-04 19:48:36', '2024-04-12 11:28:13', 'Soient $n \\in \\N^*$, $f$ une fonction $n$ fois dérivable de $\\R$ dans $\\R$, $(a,b) \\in \\R^2$, $g$ la fonction définie sur $\\R$ par \\[g(x) = f(ax+b)\\]\r\nPour $0 \\leqslant k \\leqslant n$ et $x \\in \\R$, donner une expression de la dérivée $k$-ème de $g$ notée $g^{(k)}(x)$.', NULL, NULL),
(17, 6, NULL, '<span class=\'textit\'>Prérequis : Pour tout</span> $a,b \\in \\R^*_+$, $\\ln(ab) = \\ln(a)+\\ln(b)$. \\\\ Montrer par récurrence que pour tout $n \\in \\N$, $\\forall x \\in \\R^*_+$, $\\ln(x^n) =n\\ln{x}$.', '', '', '2024-04-04 19:48:58', '2024-04-12 11:27:00', '\\textit{Prérequis : Pour tout} $a,b \\in \\R^*_+$, $\\ln(ab) = \\ln(a)+\\ln(b)$. \\\\ Montrer par récurrence que pour tout $n \\in \\N$, $\\forall x \\in \\R^*_+$, $\\ln(x^n) =n\\ln{x}$.', NULL, NULL),
(18, 6, NULL, '<span class=\'textit\'>Prérequis : pour tout</span> $x,y \\in \\R$, $\\abs{x \\times y} = \\abs{x} \\times \\abs{y}$. \\\\ Montrer par récurrence que pour tout $n \\in \\N$, $\\abs{x^n} = \\abs{x}^n$.', '', '', '2024-04-04 19:50:01', '2024-04-12 11:27:00', '\\textit{Prérequis : pour tout} $x,y \\in \\R$, $\\abs{x \\times y} = \\abs{x} \\times \\abs{y}$. \\\\ Montrer par récurrence que pour tout $n \\in \\N$, $\\abs{x^n} = \\abs{x}^n$.', NULL, NULL),
(19, 6, 'Nombres de Fermat', 'Un nombre de Pierre de Fermat, noté $F_n$, est défini par $\\forall n \\in \\N, F_n = 2^{2^n}+1$.\r\n<ol class=\'enumb\'> <li> Justifier que $\\forall n \\in \\N, F_{n+1} = (F_n-1)^2+1$.\r\n<li> Montrer que $\\forall n \\in \\N^*, F_n-2 = \\displaystyle \\prod_{k=0}^{n-1} F_k$.\r\n</ol>', '<ol class=\'enumb\'> <li> On a $\\forall n \\in \\N$, \\begin{align*} (F_{n}-1)^2+1 &= F_n^2-2F_n+1+1 \\\\ &= (2^{2^n}+1)^2-2\\times(2^{2^n}+1)+2 \\\\ &= (2^{2^n})^2+2\\times2^{2^n}+1-2\\times2^{2^n}-2+2 \\\\ &= 2^{2^n\\times2} + 2^{2^n+1}+1-2^{2^n+1} \\\\ &= 2^{2^{n+1}}+1 \\\\ &= F_{n+1} \\end{align*}\r\n<li> On note $\\mathcal{P}(n)$ la propriété : \"$\\forall n \\in \\N^*, F_n-2 = \\displaystyle \\prod_{k=0}^{n-1} F_k$\".\r\n<ul class=\'point\'> <li> <span class=\'textbf\'>Initialisation</span> : pour $n=1$ : \\\\\r\nd\'une part, $F_{1}-2 = 2^{2^1}+1-2 = 2^{2}+1-2 = 3$ puis, \\\\ de l\'autre part, $\\displaystyle \\prod_{k=0}^{1-1} F_k = \\displaystyle F_0 = 2^{2^0}+1 = 2^{1}+1 = 2+1 = 3$. \\\\\r\nOn a donc bien <span class=\'latex latex-boxed\'> $F_1 = \\displaystyle \\prod_{k=0}^{1-1} F_k$.\r\n<li> <span class=\'textbf\'>Hérédité</span> : Supposons que $\\mathcal{P}(n)$ soit vraie pour un entier $n$ fixé non nul, \\\\\r\non a : \\begin{align*} \\displaystyle \\prod_{k=0}^{n} F_k &= \\displaystyle \\prod_{k=0}^{n-1} F_k \\times F_n \\\\ &= (F_n-2)\\times F_n \\text{ par H.R} \\\\ &= F_n^2 - 2F_n \\\\ &= F_n^2-2F_n +1 - 1 \\\\ &= (F_n-1)^2-1 \\\\ &= (F_n-1)^2+1-2 \\\\ &= F_{n+1} - 2 \\text{ d\'après question précédente} \\end{align*}\r\nOn vient de démontrer que $\\mathcal{P}(n+1)$ est vraie.\r\n</ul>\r\nLe principe de récurrence conclut.\r\n<span class=\'latex latex-boxed\'> $\\forall n \\in \\N^*, F_n-2 = \\displaystyle \\prod_{k=0}^{n-1} F_k$ </span>\r\n</ol>', '1. Pour montrer une égalité, on peut partir d\'un sens pour arriver à l\'autre. Le sens le plus simple est de partir de l\'expression de droite : $(F_n-1)^2+1$. \\\\\r\n2. Une récurrence serait intéressante ici en partant du terme de droite pour l\'hérédité. On se servira du résultat précédent, et on fera apparaître des termes manquant en les soustrayant après.. Exemple : $F_n = F_n + 1 - 1$ pour faire apparaître \"$F_{n}+1$\"...', '2024-04-04 19:51:57', '2024-04-15 18:40:09', 'Un nombre de Pierre de Fermat, noté $F_n$, est défini par $\\forall n \\in \\N, F_n = 2^{2^n}+1$.\r\n\\enmb \\item Justifier que $\\forall n \\in \\N, F_{n+1} = (F_n-1)^2+1$.\r\n\\item Montrer que $\\forall n \\in \\N^*, F_n-2 = \\displaystyle \\prod_{k=0}^{n-1} F_k$.\r\n\\fenmb', '\\enmb \\item On a $\\forall n \\in \\N$, \\begin{align*} (F_{n}-1)^2+1 &= F_n^2-2F_n+1+1 \\\\ &= (2^{2^n}+1)^2-2\\times(2^{2^n}+1)+2 \\\\ &= (2^{2^n})^2+2\\times2^{2^n}+1-2\\times2^{2^n}-2+2 \\\\ &= 2^{2^n\\times2} + 2^{2^n+1}+1-2^{2^n+1} \\\\ &= 2^{2^{n+1}}+1 \\\\ &= F_{n+1} \\end{align*}\r\n\\item On note $\\mathcal{P}(n)$ la propriété : \"$\\forall n \\in \\N^*, F_n-2 = \\displaystyle \\prod_{k=0}^{n-1} F_k$\".\r\n\\itm \\item \\textbf{Initialisation} : pour $n=1$ : \\\\\r\nd\'une part, $F_{1}-2 = 2^{2^1}+1-2 = 2^{2}+1-2 = 3$ puis, \\\\ de l\'autre part, $\\displaystyle \\prod_{k=0}^{1-1} F_k = \\displaystyle F_0 = 2^{2^0}+1 = 2^{1}+1 = 2+1 = 3$. \\\\\r\nOn a donc bien \\begin{boxed} $F_1 = \\displaystyle \\prod_{k=0}^{1-1} F_k$.\r\n\\item \\textbf{Hérédité} : Supposons que $\\mathcal{P}(n)$ soit vraie pour un entier $n$ fixé non nul, \\\\\r\non a : \\begin{align*} \\displaystyle \\prod_{k=0}^{n} F_k &= \\displaystyle \\prod_{k=0}^{n-1} F_k \\times F_n \\\\ &= (F_n-2)\\times F_n \\text{ par H.R} \\\\ &= F_n^2 - 2F_n \\\\ &= F_n^2-2F_n +1 - 1 \\\\ &= (F_n-1)^2-1 \\\\ &= (F_n-1)^2+1-2 \\\\ &= F_{n+1} - 2 \\text{ d\'après question précédente} \\end{align*}\r\nOn vient de démontrer que $\\mathcal{P}(n+1)$ est vraie.\r\n\\fitm\r\nLe principe de récurrence conclut.\r\n\\begin{boxed} $\\forall n \\in \\N^*, F_n-2 = \\displaystyle \\prod_{k=0}^{n-1} F_k$ \\end{boxed}\r\n\\fenmb', '1. Pour montrer une égalité, on peut partir d\'un sens pour arriver à l\'autre. Le sens le plus simple est de partir de l\'expression de droite : $(F_n-1)^2+1$. \\\\\r\n2. Une récurrence serait intéressante ici en partant du terme de droite pour l\'hérédité. On se servira du résultat précédent, et on fera apparaître des termes manquant en les soustrayant après.. Exemple : $F_n = F_n + 1 - 1$ pour faire apparaître \"$F_{n}+1$\"...'),
(20, 7, NULL, 'Soit <span class=\'latex\'>(u_n)</span> la suite définie par <span class=\'latex\'>u_0=1</span> et <span class=\'latex\'>u_{n+1} = 0,8u_n + 0,05</span>. <br>\r\nMontrer que <span class=\'latex\'>\\ptn</span>, <span class=\'latex\'>u_n > 0,25</span>.', NULL, NULL, '2024-04-04 20:23:35', '2024-04-12 11:27:00', NULL, NULL, NULL),
(21, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0=2</span> et <span class=\'latex\'>u_{n+1} = u_n+2n+5</span>. <br>\r\nMontrer que <span class=\'latex\'>\\ptn</span>, <span class=\'latex\'>u_n > n^2</span>.', NULL, NULL, '2024-04-04 20:23:50', '2024-04-12 11:27:00', NULL, NULL, NULL),
(22, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0=2</span> et <span class=\'latex\'>u_{n+1} = \\Frac{3u_n}{1+2u_n}</span>. <br>\r\nMontrer que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>u_n > 0</span>.', NULL, NULL, '2024-04-04 20:24:00', '2024-04-12 11:27:00', NULL, NULL, NULL),
(23, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = 1</span> et pour tout <span class=\'latex\'>n \\in \\N</span>, <span class=\'latex\'>u_{n+1} = \\frac{1}{3}u_n+n-2</span>. <br>\r\nMontrer que pour tout <span class=\'latex\'>n \\geqslant 4</span>, <span class=\'latex\'>u_n \\geqslant 0</span> puis en déduire que pour tout <span class=\'latex\'>n \\geqslant 5</span>, <span class=\'latex\'>u_{n} \\geqslant n-3</span>.', NULL, NULL, '2024-04-04 20:24:43', '2024-04-12 11:27:00', NULL, NULL, NULL),
(24, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = 2</span> et <span class=\'latex\'>u_{n+1} = \\Frac{1}{5}u_n + 3 \\times 0,5^n</span>. <br>\r\nMontrer que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>u_n \\geqslant \\Frac{15}{4}\\times 0,5^n</span>.', NULL, NULL, '2024-04-04 20:25:03', '2024-04-12 11:27:00', NULL, NULL, NULL),
(25, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie sur <span class=\'latex\'>\\N</span> par <span class=\'latex\'>u_0 = 4</span> et <span class=\'latex\'>\\forall n \\in \\N</span>, <span class=\'latex\'>u_{n+1} = \\Frac{4u_n-2}{u_n+1}</span>.\r\nMontrer par récurrence : <span class=\'latex\'>\\forall n \\in \\N</span>, la propriété <span class=\'latex\'>\\mathcal{P}(n)</span> : \"<span class=\'latex\'>u_n</span> existe et <span class=\'latex\'>u_n \\geqslant 1</span>\".', NULL, NULL, '2024-04-04 20:25:12', '2024-04-12 11:27:00', NULL, NULL, NULL),
(26, 7, NULL, 'Soit <span class=\'latex\'>(u_n)</span> définie par <span class=\'latex\'> u_0 = 1 </span> et  <span class=\'latex\'>u_{n+1} = \\sqrt{ 2 + u_n } </span>.<br>\r\nDémontrer que <span class=\'latex\'>\\forall n \\in \\mathbb{N}, \\: 0 < u_n < 2</span>', NULL, NULL, '2024-04-04 20:25:44', '2024-04-12 11:27:00', NULL, NULL, NULL),
(27, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = 2</span> et <span class=\'latex\'>u_{n+1} = 1 + \\Frac{1}{u_n}</span>.<br>\r\nMontrer que <span class=\'latex\'>\\ptn</span>, <span class=\'latex\'>\\Frac{3}{2} \\leqslant u_n \\leqslant 2</span>.', NULL, NULL, '2024-04-04 20:26:34', '2024-04-12 11:27:00', NULL, NULL, NULL),
(28, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = -1</span> et pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>u_{n+1} = -\\frac{1}{2}u_n^2</span>. <br>\r\nMontrer que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>-1 \\leqslant u_n \\leqslant 0</span>.', NULL, NULL, '2024-04-04 20:26:51', '2024-04-12 11:27:00', NULL, NULL, NULL),
(29, 7, NULL, 'Soient les suites <span class=\'latex\'>\\un</span> et <span class=\'latex\'>\\vn</span> définies telles que pour tout <span class=\'latex\'>n \\in \\N</span>, <span class=\'latex\'>u_{n+1} - v_{n+1} \\leqslant \\Frac{u_n-v_n}{2}</span> et <span class=\'latex\'>u_0 = a</span>, <span class=\'latex\'>v_0 =b</span> avec <span class=\'latex\'>0 < b \\leqslant a</span>. <br>\r\nMontrer que pour tout <span class=\'latex\'>n \\in \\N</span>, <span class=\'latex\'>u_n - v_n \\leqslant \\parenthese{\\Frac{1}{2}}^n(u_0-v_0)</span>.', NULL, NULL, '2024-04-04 20:27:09', '2024-04-12 11:27:00', NULL, NULL, NULL),
(30, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = 1</span> et pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>u_{n+1} = \\Frac{2+3u_n}{4+u_n}</span>.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>1-u_{n+1} = \\parenthese{\\Frac{2}{4+u_n}}(1-u_n)</span>.\r\n<li> Montrer par récurrence que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>0 \\leqslant 1-u_n \\leqslant \\parenthese{\\Frac{1}{2}}^n</span>.\r\n</ol>', NULL, NULL, '2024-04-04 20:27:31', '2024-04-12 11:27:00', NULL, NULL, NULL),
(31, 7, NULL, 'Soit <span class=\'latex\'>(u_n)</span> définie par <span class=\'latex\'>u_0 =1</span> et <span class=\'latex\'>u_{n+1} =\\sqrt{1+u_n}</span>. <br>\r\nDémontrer que la suite <span class=\'latex\'>(u_n)</span> est croissante.', NULL, NULL, '2024-04-04 20:27:44', '2024-04-12 11:27:00', NULL, NULL, NULL),
(32, 7, NULL, 'Soit <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0 = 1</span> et <span class=\'latex\'>u_{n+1} = e \\sqrt{u_n}</span>. \r\n<ol class=\'enumb\'> <li> Démontrer que <span class=\'latex\'>\\ptn</span>, <span class=\'latex\'>1 \\leqslant u_n \\leqslant e^2</span>. \r\n<li> Démontrer que <span class=\'latex\'>\\un</span> est croissante.', NULL, NULL, '2024-04-04 20:27:52', '2024-04-12 11:27:00', NULL, NULL, NULL),
(33, 7, NULL, '<span class=\'latex\'>\\un</span> est la suite définie par <span class=\'latex\'>u_0=2</span> et <span class=\'latex\'>u_{n+1} = \\sqrt{7 u_n}</span>. \r\n<ol class=\'enumb\'> <li> Démontrer que <span class=\'latex\'>\\ptn, 0 \\leqslant u_n \\leqslant u_{n+1} \\leqslant 7</span>.\r\n<li> En déduire le sens de variation de la suite <span class=\'latex\'>\\un</span>.\r\n</ol>', NULL, NULL, '2024-04-04 20:28:06', '2024-04-12 11:27:00', NULL, NULL, NULL),
(34, 7, NULL, 'Soit <span class=\'latex\'>f(x) = \\Frac{2x+1}{x+1}</span> et la suite <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0 = 2</span> et <span class=\'latex\'>u_{n+1} = f(u_n)</span>.\r\n<ol class=\'enumb\'> <li> Etudier les variations de <span class=\'latex\'>f</span> sur <span class=\'latex\'>[0,2]</span>.\r\n<li> Montrer par récurrence que <span class=\'latex\'>\\forall n \\in \\N</span>, <span class=\'latex\'>1 \\leqslant v_{n+1} \\leqslant v_n \\leqslant 2</span>.\r\n</ol>', NULL, NULL, '2024-04-04 20:28:26', '2024-04-12 11:27:00', NULL, NULL, NULL),
(35, 7, NULL, 'Soit <span class=\'latex\'>(u_n)</span> définie par <span class=\'latex\'>u_0 \\in ]0,1[</span> et <span class=\'latex\'>u_{n+1} = u_n(2-u_n)</span>. \r\n<ol class=\'enumb\'>\r\n<li> Montrer que <span class=\'latex\'>f : x \\mapsto x(2-x)</span> est croissante sur <span class=\'latex\'>[0,1]</span>. \r\n<li> Montrer que <span class=\'latex\'>\\forall n \\in \\N</span>, <span class=\'latex\'>0 < u_n < 1</span>. \r\n<li> En déduire que la suite <span class=\'latex\'>(u_n)</span> est croissante. \r\n</ol>', NULL, NULL, '2024-04-04 20:28:45', '2024-04-12 11:27:00', NULL, NULL, NULL),
(36, 7, NULL, 'Soit <span class=\'latex\'>(u_n)</span> définie par <span class=\'latex\'>u_0 = 0</span> et <span class=\'latex\'>u_{n+1} = \\Frac{-u_n-4}{u_n+3}</span>. \r\n<ol class=\'enumb\'>\r\n<li> Etudier les variations de <span class=\'latex\'>f : x \\mapsto \\Frac{-x-4}{x+3}</span> sur <span class=\'latex\'>]-3,+\\infty[</span>.\r\n<li> Montrer par récurrence que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>-2 < u_{n+1} \\leqslant u_n</span>.\r\n</ol>', NULL, NULL, '2024-04-04 20:28:56', '2024-04-12 11:27:00', NULL, NULL, NULL),
(37, 7, NULL, 'Montrer que <span class=\'latex\'>\\forall n \\in \\N^*, \\, \\, n! \\geqslant 2^{n-1}</span>.', NULL, NULL, '2024-04-04 20:30:06', '2024-04-12 11:27:00', NULL, NULL, NULL),
(38, 7, NULL, 'Montrer que pour tout entier naturel <span class=\'latex\'>n \\geqslant 3</span>, <span class=\'latex\'>\\; 2^n > 2n</span>.', NULL, NULL, '2024-04-04 20:30:15', '2024-04-12 11:27:00', NULL, NULL, NULL),
(39, 7, NULL, 'Démontrer que pour tout entier <span class=\'latex\'>n \\geqslant 2</span>, <span class=\'latex\'>5^n \\geqslant 4^n+3^n</span>.', NULL, NULL, '2024-04-04 20:30:23', '2024-04-12 11:27:00', NULL, NULL, NULL),
(40, 7, 'Inégalité de Bernoulli', 'Soit <span class=\'latex\'>a \\in \\R_+</span>. Montrer que <span class=\'latex\'>\\ptn, (1+a)^n \\geqslant 1+na</span>.', '', NULL, '2024-04-04 20:30:30', '2024-04-12 11:27:00', NULL, NULL, NULL),
(41, 7, NULL, 'Soit <span class=\'latex\'>a \\in \\R_+</span>.\r\n<ol class=\'enumb\'> <li> Montrer que <span class=\'latex\'>\\ptn</span>, <span class=\'latex\'>(1+a)^n \\geqslant 1+na +\\Frac{n(n-1)}{2}a^2</span>.\r\n<li> On considère la suite <span class=\'latex\'>\\un</span> définie sur <span class=\'latex\'>\\N^*</span> par <div class=\'latex\'>u_n = \\Frac{3n}{3^n}</div>\r\nJustifier que pour tout <span class=\'latex\'>n</span> non nul, on a <span class=\'latex\'>0 < u_n \\leqslant \\Frac{3n}{2n^2+1}</span>.\r\n</ol>', '', NULL, '2024-04-04 20:32:34', '2024-04-12 11:27:00', NULL, NULL, NULL),
(42, 7, NULL, 'Montrer que, pour tout réel <span class=\'latex\'>x</span> et pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>\\abs{\\sin(nx)}\\leqslant n \\abs{\\sin{x}}</span>.', NULL, NULL, '2024-04-04 20:33:39', '2024-04-12 11:27:00', NULL, NULL, NULL),
(43, 8, NULL, 'On considère la suite <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_1=1</span> et, pour tout entier <span class=\'latex\'>n</span>, <span class=\'latex\'>u_{n+1} = \\Frac{ u_n}{\\sqrt{u_{n}^2 + 1} }</span>.<br>\r\nConjecturer une expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:41:48', '2024-04-12 11:27:00', NULL, NULL, NULL),
(44, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = 1</span> \\text{ et } <span class=\'latex\'>u_{n+1} = \\Frac{u_n}{1+2u_n}</span>. <br>\r\nConjecturer l\'expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:41:58', '2024-04-12 11:27:00', NULL, NULL, NULL),
(45, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_1 = \\frac{1}{3}</span> puis pour tout entier <span class=\'latex\'>n > 0</span>, <span class=\'latex\'>u_{n+1} = \\Frac{n+1}{3n}u_n</span>. <br>\r\nConjecturer l\'expression du terme général <span class=\'latex\'>u_n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:42:07', '2024-04-12 11:27:00', NULL, NULL, NULL),
(46, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0=1</span> et pour tout <span class=\'latex\'>n \\in \\N</span>, <span class=\'latex\'>u_{n+1} = \\Frac{(n+1)^2}{(n+2)^2}u_n</span>. <br>\r\nConjecturer une expression explicite de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:42:17', '2024-04-12 11:27:00', NULL, NULL, NULL),
(47, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0=1</span> et pour tout <span class=\'latex\'>n \\in \\N</span>, <span class=\'latex\'>u_{n+1} = u_n + 2n+1</span>. <br>\r\nConjecturer l\'expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:42:24', '2024-04-12 11:27:00', NULL, NULL, NULL),
(48, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0=0</span> et <span class=\'latex\'>u_{n+1} = \\Frac{1}{2-u_n}</span>. <br>\r\nConjecturer l\'expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:42:32', '2024-04-12 11:27:00', NULL, NULL, NULL),
(49, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0=0</span> et pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>u_{n+1} = \\sqrt{1+u_{n}^2}</span>. <br>\r\nConjecturer l\'expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:42:38', '2024-04-12 11:27:00', NULL, NULL, NULL),
(50, 8, NULL, 'Soit <span class=\'latex\'>k \\geqslant 0</span>. Soit <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0=0</span> et pour tout entier <span class=\'latex\'>n \\in \\N</span>, <span class=\'latex\'>u_{n+1} = \\sqrt{u_n^2 + k^2 }</span>. <br>\r\nConjecturer l\'expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> et démontrer cette conjecture par récurrence.', NULL, NULL, '2024-04-04 20:42:47', '2024-04-12 11:27:00', NULL, NULL, NULL),
(51, 8, NULL, 'Soit <span class=\'latex\'>\\un</span> la suite définie par <span class=\'latex\'>u_0 = 1</span> et <span class=\'latex\'>u_{n+1} = u_n + 2n+3</span>. <br>\r\nConjecturer une expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 20:42:54', '2024-04-12 11:27:00', NULL, NULL, NULL),
(52, 8, NULL, 'Soit <span class=\'latex\'>c \\in \\R^{*}_+</span> Pour <span class=\'latex\'>x \\in \\R</span>, on considère <span class=\'latex\'>f(x) = \\Frac{x}{\\sqrt{1+cx^2}}</span>. Calculer <span class=\'latex\'>f(f(x))</span>, <span class=\'latex\'>f(f(f(x)))</span> et généraliser.', NULL, NULL, '2024-04-04 20:43:04', '2024-04-12 11:27:00', NULL, NULL, NULL),
(53, 8, NULL, 'On considère la suite <span class=\'latex\'>(u_n)</span> définie par <span class=\'latex\'>u_0 =3</span> et pour tout <span class=\'latex\'>n \\in \\N</span> par <span class=\'latex\'>u_{n+1} = \\Frac{1-u_n}{1+u_n}</span>.\r\n<ol class=\'enumb\'> <li> Déterminer à la calculatrice les premiers termes de cette suite et formuler une conjecture.\r\n<li> Démontrer votre conjecture.\r\n<li> Combien vaut <span class=\'latex\'>u_{423}</span> ? \r\n</ol>', '', NULL, '2024-04-04 20:43:12', '2024-04-12 11:27:00', NULL, NULL, NULL),
(54, 9, NULL, 'Montrer que <span class=\'latex\'>\\forall n \\in \\N</span>, <span class=\'latex\'>\\;7 \\times 3^{5n}+4</span> est divisible par 11.', NULL, NULL, '2024-04-04 20:54:17', '2024-04-12 11:27:00', NULL, NULL, NULL),
(55, 9, NULL, 'Montrer que <span class=\'latex\'>\\forall n \\in \\N</span>, 3 divise <span class=\'latex\'>5^n-2^n</span>.', NULL, NULL, '2024-04-04 20:54:29', '2024-04-12 11:27:00', NULL, NULL, NULL),
(56, 9, NULL, '<ol class=\'enumb\'> <li> Montrer que <span class=\'latex\'>\\forall n\\in \\mathbb{N}</span>, <span class=\'latex\'>\\;10^n-1</span> est un multiple de 9. \r\n<li> Montrer que la propriété <span class=\'latex\'>\\mathcal{P}(n)</span> : \"<span class=\'latex\'>10^{n}+1</span> est un multiple de 9\" est héréditaire mais non initialisée.\r\n</ol>', NULL, NULL, '2024-04-04 20:54:45', '2024-04-12 11:27:00', NULL, NULL, NULL),
(57, 9, NULL, 'Montrer que la propriété <span class=\'latex\'>\\mathscr{P}(n)</span> : \"<span class=\'latex\'>4^n+1</span> est divisible par 3\" est héréditaire. <br>\r\nMontrer qu\'elle n\'est pas initialisée et conclure.', NULL, NULL, '2024-04-04 20:55:03', '2024-04-12 11:27:00', NULL, NULL, NULL),
(58, 9, NULL, 'Montrer que tout entier <span class=\'latex\'>n \\geqslant 24</span> peut s\'écrire <span class=\'latex\'>n=5a+7b</span> avec <span class=\'latex\'>(a,b) \\in \\Z^2</span>.', NULL, NULL, '2024-04-04 20:55:12', '2024-04-12 11:27:00', NULL, NULL, NULL),
(59, 10, 'Un incontournable', 'Démontrer par récurrence que, pour tout <span class=\'latex\'>n \\in \\mathbb{N}</span>,  <span class=\'latex\'>\\displaystyle\\sum_{k=0}^{n}k = \\Frac{ n(n+1)}{2}</span>.', '', NULL, '2024-04-04 20:56:32', '2024-04-12 11:27:00', NULL, NULL, NULL),
(60, 10, 'Un incontournable n°2', 'Démontrer par récurrence que, pour tout <span class=\'latex\'>n \\in \\mathbb{N}</span>, <span class=\'latex\'>\\displaystyle\\sum_{k=0}^{n}k^2 = \\Frac{ n(n+1)(2n+1)}{6}</span>.', NULL, NULL, '2024-04-04 20:56:50', '2024-04-12 11:27:00', NULL, NULL, NULL),
(61, 10, 'Un incontournable n°3', 'Démontrer par récurrence que pour tout <span class=\'latex\'>n \\geqslant 1</span>,  <span class=\'latex\'>\\displaystyle \\sum_{k=0}^{n} k^3 = \\parenthese{ \\sum_{k=0}^{n} k }^2</span>.', NULL, NULL, '2024-04-04 20:57:18', '2024-04-12 11:27:00', NULL, NULL, NULL),
(62, 10, 'Somme télescopique', '<ol class=\'enumb\'> <li> Montrer par récurrence que, pour tout entier <span class=\'latex\'>n</span>, <span class=\'latex\'>\\;\\displaystyle \\sum_{k=1}^{n} k \\times k! = (n+1)!-1</span>. \r\n<li> En utilisant le fait que <span class=\'latex\'>k = (k+1)-1</span>, établir le résultat précédent sans passer par une récurrence.\r\n</ol>', '', NULL, '2024-04-04 20:57:42', '2024-04-12 11:27:00', NULL, NULL, NULL),
(63, 10, 'Somme télescopique n°2', 'Soit <span class=\'latex\'>n</span> un entier non nul et <span class=\'latex\'>S_n</span> la somme <span class=\'latex\'>S_n = \\displaystyle \\sum_{p=1}^{n} \\Frac{1}{p(p+1)}</span>.\r\n<ol class=\'enumb\'> \r\n<li> Montrer par récurrence que pour tout entier <span class=\'latex\'>n</span>, <div class=\'latex\'>S_n = \\Frac{n}{n+1}</div>\r\n<li> <ol class=\'enumb\'> <li> Vérifier que pour tout entier <span class=\'latex\'>p</span> non nul, <div class=\'latex\'>\\frac{1}{p(p+1)} = \\frac{1}{p} - \\frac{1}{p+1}</div>\r\n <li> Retrouver alors le résultat du 1. par une autre méthode. \r\n</ol>\r\n</ol>', '', '', '2024-04-04 20:57:58', '2024-04-12 11:27:00', 'Soit $n$ un entier non nul et $S_n$ la somme $S_n = \\displaystyle \\sum_{p=1}^{n} \\Frac{1}{p(p+1)}$.\r\n\\enmb \r\n\\item Montrer par récurrence que pour tout entier $n$, \\[S_n = \\Frac{n}{n+1}\\]\r\n\\item \\enmb \\item Vérifier que pour tout entier $p$ non nul, \\[\\frac{1}{p(p+1)} = \\frac{1}{p} - \\frac{1}{p+1}\\]\r\n \\item Retrouver alors le résultat du 1. par une autre méthode. \r\n\\fenmb\r\n\\fenmb', NULL, NULL),
(64, 10, NULL, 'Montrer par récurrence que pour tout entier naturel <span class=\'latex\'>n \\geqslant 1</span>, <div class=\'latex\'>S_n = \\Frac{1}{1 \\times 2} + \\Frac{1}{2\\times 3} + \\Frac{1}{3 \\times 4} + \\hdots + \\Frac{1}{n(n+1)} = 1 - \\Frac{1}{n+1} </div>', NULL, NULL, '2024-04-04 20:58:18', '2024-04-12 11:27:00', NULL, NULL, NULL),
(65, 10, NULL, 'Montrer que pour tout entier naturel <span class=\'latex\'>n \\geqslant 1</span>, <span class=\'latex\'>1+3+5+\\hdots+(2n-1) = n^2</span>.', NULL, NULL, '2024-04-04 20:58:33', '2024-04-12 11:27:00', NULL, NULL, NULL),
(66, 10, NULL, 'Montrer que pour tout entier naturel <span class=\'latex\'>n \\geqslant 1</span>, <span class=\'latex\'>1^2 + 3^2 + 5^2 + \\hdots + (2n-1)^2 = \\Frac{1}{3}n(4n^2-1)</span>.', NULL, NULL, '2024-04-04 20:58:40', '2024-04-12 11:27:00', NULL, NULL, NULL),
(67, 10, 'Somme d\'une suite géométrique', 'Soit <span class=\'latex\'>q</span> un réel différent de <span class=\'latex\'>1</span>. <br>\r\nMontrer par récurrence que pour tout entier naturel <span class=\'latex\'>n</span>, <div class=\'latex\'> \\sum_{k=0}^{n}q^k = \\Frac{1-q^{n+1}}{1-q} </div>En déduire l\'expression de <span class=\'latex\'>S_n = \\displaystyle \\sum_{k=0}^{n} u_k</span> avec <span class=\'latex\'>\\un</span> une suite géométrique de premier terme <span class=\'latex\'>u_0 \\in \\R</span> et de raison <span class=\'latex\'>a \\neq 1</span>.', '', NULL, '2024-04-04 20:58:55', '2024-04-12 11:27:00', NULL, NULL, NULL),
(68, 10, NULL, 'Pour tout <span class=\'latex\'>n \\in \\N^*</span>, on considère <div class=\'latex\'>S_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{2}{k(k+1)(k+2)}</div>\r\nMontrer que pour tout entier naturel <span class=\'latex\'>n</span> non nul, <span class=\'latex\'>S_n = \\Frac{n(n+3)}{2(n+1)(n+2)}</span>.', '', '', '2024-04-04 21:00:30', '2024-04-12 11:27:00', 'Pour tout $n \\in \\N^*$, on considère \\[S_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{2}{k(k+1)(k+2)}\\]\r\nMontrer que pour tout entier naturel $n$ non nul, $S_n = \\Frac{n(n+3)}{2(n+1)(n+2)}$.', NULL, NULL),
(69, 10, NULL, 'Pour tout <span class=\'latex\'>n \\in \\N^*</span>, on considère <span class=\'latex\'>S_n = \\displaystyle \\sum_{k=1}^{n}(-1)^kk^2</span>. <br>\r\nMontrer que pour tout entier naturel <span class=\'latex\'>n</span> non nul, <span class=\'latex\'>S_n = (-1)^n\\Frac{n(n+1)}{2}</span>.', NULL, NULL, '2024-04-04 21:00:43', '2024-04-12 11:27:00', NULL, NULL, NULL),
(70, 10, NULL, 'Pour tout <span class=\'latex\'>n \\geqslant 1</span>, on considère <div class=\'latex\'>S_n = \\displaystyle \\sum_{k=1}^{n}(2k-1)^2</div>\r\nMontrer que pour tout <span class=\'latex\'>n \\geqslant 1</span>, on a <span class=\'latex\'>S_n = \\Frac{n(2n-1)(2n+1)}{3}</span>.', '', '', '2024-04-04 21:00:51', '2024-04-12 11:27:00', 'Pour tout $n \\geqslant 1$, on considère \\[S_n = \\displaystyle \\sum_{k=1}^{n}(2k-1)^2\\]\r\nMontrer que pour tout $n \\geqslant 1$, on a $S_n = \\Frac{n(2n-1)(2n+1)}{3}$.', NULL, NULL),
(71, 10, NULL, 'Montrer que pour tout <span class=\'latex\'>n \\in \\N^*</span>, <span class=\'latex\'>\\displaystyle \\sum_{k=1}^{n} \\Frac{k}{2^k} = 2 -  \\Frac{n+2}{2^n}</span>.', NULL, NULL, '2024-04-04 21:00:58', '2024-04-12 11:27:00', NULL, NULL, NULL),
(72, 10, 'Somme harmonique', 'Pour tout <span class=\'latex\'>n \\geqslant 2</span>, on note <div class=\'latex\'>H_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{1}{k}</div>\r\nMontrer que pour tout <span class=\'latex\'>n \\geqslant 2</span>, <span class=\'latex\'>\\displaystyle \\sum_{p=1}^{n-1}H_p = nH_n - n</span>.', '', '', '2024-04-04 21:01:13', '2024-04-12 11:27:00', 'Pour tout $n \\geqslant 2$, on note \\[H_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{1}{k}\\]\r\nMontrer que pour tout $n \\geqslant 2$, $\\displaystyle \\sum_{p=1}^{n-1}H_p = nH_n - n$.', NULL, NULL),
(73, 11, NULL, 'On considère la suite définie sur <span class=\'latex\'>\\N</span> par <span class=\'latex\'>u_0 =-3</span>, <span class=\'latex\'>u_1 = -4</span> et <span class=\'latex\'>\\forall n \\in \\N</span>, <div class=\'latex\'>u_{n+1} = 5u_n-6u_{n-1}</div>\r\nMontrer par récurrence que pour tout entier naturel <span class=\'latex\'>n</span>, <span class=\'latex\'>u_n = 2× 3^n - 5 × 2^n</span>.', '', '', '2024-04-04 21:03:07', '2024-04-12 11:27:00', 'On considère la suite définie sur $\\N$ par $u_0 =-3$, $u_1 = -4$ et $\\forall n \\in \\N$, \\[u_{n+1} = 5u_n-6u_{n-1}\\]\r\nMontrer par récurrence que pour tout entier naturel $n$, $u_n = 2\\times 3^n - 5 \\times 2^n$.', NULL, NULL),
(74, 11, NULL, 'Montrer que, pour tout <span class=\'latex\'>n</span> entier naturel, il existe deux entiers naturels <span class=\'latex\'>p_n</span> et <span class=\'latex\'>q_n</span> tels que <div class=\'latex\'>(2+\\sqrt{3})^n = p_n + q_n \\sqrt{3}</div>', '', '', '2024-04-04 21:03:36', '2024-04-12 11:27:00', 'Montrer que, pour tout $n$ entier naturel, il existe deux entiers naturels $p_n$ et $q_n$ tels que \\[(2+\\sqrt{3})^n = p_n + q_n \\sqrt{3}\\]', NULL, NULL),
(75, 11, NULL, 'Montrer que, si <span class=\'latex\'>n \\in \\N</span>, il existe un entier impair <span class=\'latex\'>\\lambda_n</span> tel que <div class=\'latex\'> 5^{2^n} = 1 + \\lambda_n 2^{n+2} </div>', '', '', '2024-04-04 21:03:50', '2024-04-12 11:27:00', 'Montrer que, si $n \\in \\N$, il existe un entier impair $\\lambda_n$ tel que \\[ 5^{2^n} = 1 + \\lambda_n 2^{n+2} \\]', NULL, NULL),
(76, 11, NULL, 'La suite <span class=\'latex\'>\\un</span> est définie sur <span class=\'latex\'>\\N</span> par <span class=\'latex\'>u_0 = 1</span>, <span class=\'latex\'>u_1 = 2</span> et pour tout <span class=\'latex\'>n \\in \\N^*</span>, <span class=\'latex\'>u_{n+1} = \\Frac{(u_n)^2}{u_{n-1}}</span>.<br>\r\nConjecturer une expression de <span class=\'latex\'>u_n</span> en fonction de <span class=\'latex\'>n</span> puis démontrer cette conjecture.', NULL, NULL, '2024-04-04 21:04:03', '2024-04-12 11:27:00', NULL, NULL, NULL),
(77, 11, NULL, 'On considère la suite <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0 = 1</span> et <span class=\'latex\'>\\forall n \\in \\N, \\: u_{n+1} = u_0 + u_1 + \\hdots + u_n</span>.<br>\r\nMontrer par récurrence forte que <span class=\'latex\'>\\forall n \\in \\N, \\: u_n \\leqslant 2^n</span>.', NULL, NULL, '2024-04-04 21:04:11', '2024-04-12 11:27:00', NULL, NULL, NULL),
(78, 11, 'Suite de Fibonacci', 'Soit <span class=\'latex\'>(F_n)</span> la suite de Fibonacci définie sur <span class=\'latex\'>\\N</span> par <span class=\'latex\'>F_0 =0</span>, <span class=\'latex\'>F_1 = 1</span> et <div class=\'latex\'> \\forall n \\in \\N^*, F_{n+1} = F_n + F_{n-1} </div>\r\n<ol class=\'enumb\'> <li> Montrer que, <span class=\'latex\'>\\forall n \\in \\N</span>, <span class=\'latex\'>F_n \\in \\N</span>.<br>\r\nEn déduire le sens de variation de la suite <span class=\'latex\'>F_n</span>.\r\n<li> Montrer que <span class=\'latex\'>\\forall n \\in \\N^*, F_n^2 - F_{n-1} F_{n+1} = (-1)^{n+1}</span>.\r\n<li> Prouver que pour tout entier <span class=\'latex\'>n \\in \\N^*</span>, <span class=\'latex\'>\\displaystyle \\sum_{k=1}^{n} F_k^2 = F_n F_{n+1}</span>.\r\n</ol>', NULL, NULL, '2024-04-04 21:04:25', '2024-04-12 11:27:00', NULL, NULL, NULL),
(79, 12, NULL, '$(u_n)$ est la suite définie par $u_0 = 3$ et pour tout $n \\in \\N$ par \\[u_{n+1} = \\Frac{3u_n}{3+2u_n}\\]Pour tout entier $n$, on pose $v_n = \\frac{3}{u_n}$. <br>\r\nDémontrer que $(v_n)$ est une suite arithmétique.<br>\r\nEn déduire une expression $u_n$ en fonction de $n$.', '', '', '2024-04-06 12:37:17', '2024-04-12 11:27:00', '$(u_n)$ est la suite définie par $u_0 = 3$ et pour tout $n \\in \\N$ par \\[u_{n+1} = \\Frac{3u_n}{3+2u_n}\\]Pour tout entier $n$, on pose $v_n = \\frac{3}{u_n}$. \\\\\r\nDémontrer que $(v_n)$ est une suite arithmétique.\\\\\r\nEn déduire une expression $u_n$ en fonction de $n$.', NULL, NULL),
(80, 12, NULL, '$\\un$ est la suite définie par $u_0 = 2$ et pour tout $n \\in \\N$,  \\[u_{n+1} = \\Frac{u_n}{u_n+1}\\] On pose $v_n = \\frac{1}{u_n}$. Démontrer que la suite $\\vn$ est arithmétique puis en déduire l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 12:37:37', '2024-04-12 11:27:00', '$\\un$ est la suite définie par $u_0 = 2$ et pour tout $n \\in \\N$,  \\[u_{n+1} = \\Frac{u_n}{u_n+1}\\] On pose $v_n = \\frac{1}{u_n}$. Démontrer que la suite $\\vn$ est arithmétique puis en déduire l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(81, 12, NULL, 'Soit $\\un$ la suite définie par $u_0 = 5$ et pour tout $n \\in \\N$, $ u_{ n+1} = \\Frac{5u_n-16}{u_n-3}$. <br>\r\nOn suppose que pour tout $n$, $u_n \\neq 4$.<br>\r\nOn pose $v_n = \\Frac{1}{u_n-4}$. <br>\r\nMontrer que la suite $\\vn$ est arithmétique. En déduire l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 12:38:06', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 5$ et pour tout $n \\in \\N$, $ u_{ n+1} = \\Frac{5u_n-16}{u_n-3}$. \\\\\r\nOn suppose que pour tout $n$, $u_n \\neq 4$.\\\\\r\nOn pose $v_n = \\Frac{1}{u_n-4}$. \\\\\r\nMontrer que la suite $\\vn$ est arithmétique. En déduire l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(82, 12, NULL, 'Soit $\\un$ la suite définie par $u_0=2$ et pour tout $n \\geqslant 0$, \\[u_{n+1} = \\Frac{3u_n-1}{u_n+1}\\]On suppose que $\\un$ est bien définie.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout naturel $n$, $u_n > 1$.\r\n<li> On pose $v_n = \\Frac{1}{u_n-3}$. <br>\r\nMontrer que $\\vn$ est une suite arithmétique.\r\n<li> En déduire l\'expression de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 12:39:00', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0=2$ et pour tout $n \\geqslant 0$, \\[u_{n+1} = \\Frac{3u_n-1}{u_n+1}\\]On suppose que $\\un$ est bien définie.\r\n\\enmb \\item Montrer que pour tout naturel $n$, $u_n > 1$.\r\n\\item On pose $v_n = \\Frac{1}{u_n-3}$. \\\\\r\nMontrer que $\\vn$ est une suite arithmétique.\r\n\\item En déduire l\'expression de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(83, 12, NULL, 'Soit $\\un$ la suite définie par $u_0 = 3$ et pour tout entier naturel $n$, \\[u_{n+1} = u_n+2n-1\\]\r\nOn pose $v_n = u_n-n^2$. <br>\r\nMontrer que $\\vn$ est une suite arithmétique dont on précisera la raison et son premier terme. <br>\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 12:39:28', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 3$ et pour tout entier naturel $n$, \\[u_{n+1} = u_n+2n-1\\]\r\nOn pose $v_n = u_n-n^2$. \\\\\r\nMontrer que $\\vn$ est une suite arithmétique dont on précisera la raison et son premier terme. \\\\\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(84, 12, NULL, 'Soit $\\un$ la suite définie par $u_0 = 0$ et pour tout entier naturel $n$, \\[u_{n+1} = 2u_n + 2^n\\]\r\nOn pose $v_n = \\Frac{u_n}{2^n}$.\r\n<ol class=\'enumb\'> <li> Montrer que $\\vn$ est une suite arithmétique. On précisera la raison et son premier terme.\r\n<li> En déduire l\'expression de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 12:39:45', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 0$ et pour tout entier naturel $n$, \\[u_{n+1} = 2u_n + 2^n\\]\r\nOn pose $v_n = \\Frac{u_n}{2^n}$.\r\n\\enmb \\item Montrer que $\\vn$ est une suite arithmétique. On précisera la raison et son premier terme.\r\n\\item En déduire l\'expression de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(85, 12, NULL, 'Soit $\\un$ une suite arithmétique de raison $r \\neq 0$ et de premier terme $u_0 \\neq 0$.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout $k \\in \\N$, $\\Frac{r}{u_ku_{k+1}} = \\Frac{1}{u_k}-\\Frac{1}{u_{k+1}}$.\r\n<li> En sommant ces égalités, montrer que $\\displaystyle \\sum_{k=0}^{n}\\Frac{1}{u_ku_{k+1}} = \\Frac{(n+1)}{u_0u_{n+1}}$.\r\n</ol>', '', '', '2024-04-06 12:48:18', '2024-04-12 11:27:00', 'Soit $\\un$ une suite arithmétique de raison $r \\neq 0$ et de premier terme $u_0 \\neq 0$.\r\n\\enmb \\item Montrer que pour tout $k \\in \\N$, $\\Frac{r}{u_ku_{k+1}} = \\Frac{1}{u_k}-\\Frac{1}{u_{k+1}}$.\r\n\\item En sommant ces égalités, montrer que $\\displaystyle \\sum_{k=0}^{n}\\Frac{1}{u_ku_{k+1}} = \\Frac{(n+1)}{u_0u_{n+1}}$.\r\n\\fenmb', NULL, NULL),
(86, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 0$ et \\[u_{n+1} = -\\Frac{1}{2}u_n + 1\\]\r\nOn pose $v_n = u_n - \\Frac{2}{3}$. <br>\r\nMontrer que $\\vn$ est géométrique. On précisera le premier terme et la raison. <br>\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 15:31:12', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 0$ et \\[u_{n+1} = -\\Frac{1}{2}u_n + 1\\]\r\nOn pose $v_n = u_n - \\Frac{2}{3}$. \\\\\r\nMontrer que $\\vn$ est géométrique. On précisera le premier terme et la raison. \\\\\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(87, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour $k \\in \\R^*$, \\[u_{n+1} = 0,8u_n + k\\]\r\nOn pose $v_n = u_n - 5k$. <br>\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme. <br>\r\nExprimer $u_n$ en fonction de $n$ et $k$.', '', '', '2024-04-06 15:31:25', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour $k \\in \\R^*$, \\[u_{n+1} = 0,8u_n + k\\]\r\nOn pose $v_n = u_n - 5k$. \\\\\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme. \\\\\r\nExprimer $u_n$ en fonction de $n$ et $k$.', NULL, NULL),
(88, 13, 'Suite arithmético-géométrique', 'Soient $a$ et $b$ deux réels non nuls avec $a \\neq 1$. On définit la suite $\\un$ par $u_0 \\in \\R$ et pour tout entier naturel $n$, \\[u_{n+1}=au_n+b\\]On pose, pour tout entier naturel $n$, $v_n = u_n - \\lambda$ avec $\\lambda = \\Frac{b}{1-a}$. <br>\r\nMontrer que $\\vn$ est géométrique de raison $a$.', '', '', '2024-04-06 15:31:39', '2024-04-12 11:27:00', 'Soient $a$ et $b$ deux réels non nuls avec $a \\neq 1$. On définit la suite $\\un$ par $u_0 \\in \\R$ et pour tout entier naturel $n$, \\[u_{n+1}=au_n+b\\]On pose, pour tout entier naturel $n$, $v_n = u_n - \\lambda$ avec $\\lambda = \\Frac{b}{1-a}$. \\\\\r\nMontrer que $\\vn$ est géométrique de raison $a$.', NULL, NULL),
(89, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 8$ et pour tout $n \\in \\N$, \\[u_{n+1} = \\Frac{6u_n+2}{u_n+5}\\]\r\nOn pose $v_n = \\Frac{u_n-2}{u_n+1}$.  <br>\r\nMontrer que $\\vn$ est géométrique. <br>\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 15:31:47', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 8$ et pour tout $n \\in \\N$, \\[u_{n+1} = \\Frac{6u_n+2}{u_n+5}\\]\r\nOn pose $v_n = \\Frac{u_n-2}{u_n+1}$.  \\\\\r\nMontrer que $\\vn$ est géométrique. \\\\\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(90, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et \\[u_{n+1} = \\Frac{1}{2}u_n+n-1\\]\r\nOn pose $v_n = 4u_n-8n+24$. <br>\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme. <br>\r\nEn déduire que $u_n = 7\\times\\parenthese{\\Frac{1}{2}}^n+2n-6$.', '', '', '2024-04-06 15:31:56', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 1$ et \\[u_{n+1} = \\Frac{1}{2}u_n+n-1\\]\r\nOn pose $v_n = 4u_n-8n+24$. \\\\\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme. \\\\\r\nEn déduire que $u_n = 7\\times\\parenthese{\\Frac{1}{2}}^n+2n-6$.', NULL, NULL);
INSERT INTO `exercises` (`id`, `subchapter_id`, `name`, `statement`, `solution`, `clue`, `created_at`, `updated_at`, `latex_statement`, `latex_solution`, `latex_clue`) VALUES
(91, 13, NULL, 'Soit $\\un$ la suite définie par $u_1 = \\Frac{1}{2}$ et pour tout $n \\geqslant 1$, \\[u_{n+1} = \\Frac{ n+1}{2n} u_n\\]\r\nOn pose $v_n = \\Frac{u_n}{n}$. <br>\r\nMontrer que $\\vn$ est géométrique. On précisera sa raison et son premier terme $v_1$ puis en déduire une expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 15:32:05', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_1 = \\Frac{1}{2}$ et pour tout $n \\geqslant 1$, \\[u_{n+1} = \\Frac{ n+1}{2n} u_n\\]\r\nOn pose $v_n = \\Frac{u_n}{n}$. \\\\\r\nMontrer que $\\vn$ est géométrique. On précisera sa raison et son premier terme $v_1$ puis en déduire une expression de $u_n$ en fonction de $n$.', NULL, NULL),
(92, 13, NULL, 'Soit $\\un$ définie par $u_0 = 1$ et \\[\\unp = \\sqrt{2u_n}\\]\r\nOn pose $v_n = \\ln{u_n}-\\ln{2}$. <br>\r\nMontrer que $\\vn$ est géométrique. Exprimer $u_n$ en fonction de $n$.', '', '', '2024-04-06 15:32:12', '2024-04-12 11:27:00', 'Soit $\\un$ définie par $u_0 = 1$ et \\[\\unp = \\sqrt{2u_n}\\]\r\nOn pose $v_n = \\ln{u_n}-\\ln{2}$. \\\\\r\nMontrer que $\\vn$ est géométrique. Exprimer $u_n$ en fonction de $n$.', NULL, NULL),
(93, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 2$ et pour tout entier naturel $n$, \\[u_{n+1} = \\Frac{1}{5}u_n+3\\times0,5^n\\]\r\nOn pose $v_n = u_n - 10 \\times 0,5^n$. <br>\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme. <br>\r\nEn déduire que $u_n = -8\\times\\parenthese{\\Frac{1}{5}}^n+10\\times0,5^n$.', '', '', '2024-04-06 15:32:20', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 2$ et pour tout entier naturel $n$, \\[u_{n+1} = \\Frac{1}{5}u_n+3\\times0,5^n\\]\r\nOn pose $v_n = u_n - 10 \\times 0,5^n$. \\\\\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme. \\\\\r\nEn déduire que $u_n = -8\\times\\parenthese{\\Frac{1}{5}}^n+10\\times0,5^n$.', NULL, NULL),
(94, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour tout entier naturel $n$, \\[u_{n+1} = e\\sqrt{u_n}\\]\r\nOn pose $v_n = \\ln(u_n)-2$. <br>\r\nMontrer que $\\vn$ est géométrique. <br>\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 15:33:02', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour tout entier naturel $n$, \\[u_{n+1} = e\\sqrt{u_n}\\]\r\nOn pose $v_n = \\ln(u_n)-2$. \\\\\r\nMontrer que $\\vn$ est géométrique. \\\\\r\nEn déduire l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(95, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[u_{n+1} = \\Frac{1}{3}\\sqrt{u_n^2+8}\\]\r\nOn pose $v_n = u_n^2-1$. <br>\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme.', '', '', '2024-04-06 15:33:11', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[u_{n+1} = \\Frac{1}{3}\\sqrt{u_n^2+8}\\]\r\nOn pose $v_n = u_n^2-1$. \\\\\r\nMontrer que $\\vn$ est géométrique. Préciser sa raison et son premier terme.', NULL, NULL),
(96, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 4$ et pour tout entier naturel $n$, \\[u_{n+1} = \\Frac{1}{5}u_n^2\\]\r\nOn pose $v_n = \\ln(u_n)$ et $w_n = v_n - \\ln(5)$.\r\n<ol class=\'enumb\'> <li> Montrer que $v_{n+1} = 2v_n - \\ln(5)$.\r\n<li> Montrer que $(w_n)$ est géométrique de raison 2.\r\n<li> En déduire l\'expression de $v_n$ en fonction de $n$ puis celle de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:33:22', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 4$ et pour tout entier naturel $n$, \\[u_{n+1} = \\Frac{1}{5}u_n^2\\]\r\nOn pose $v_n = \\ln(u_n)$ et $w_n = v_n - \\ln(5)$.\r\n\\enmb \\item Montrer que $v_{n+1} = 2v_n - \\ln(5)$.\r\n\\item Montrer que $(w_n)$ est géométrique de raison 2.\r\n\\item En déduire l\'expression de $v_n$ en fonction de $n$ puis celle de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(97, 13, NULL, 'Soient $\\un$ et $\\vn$ deux suites telles que $u_0 = 16$, $v_0  = 5$ et \\[ \\begin{cases} u_{n+1} = \\Frac{3u_n+2v_n}{5} \\\\ v_{n+1} = \\Frac{u_n+v_n}{2} \\end{cases} \\]\r\nOn pose $w_n = u_n-v_n$. \\\\\r\n<ol class=\'enumb\'> <li> Montrer que $\\wn$ est géométrique. \\\\\r\nEn déduire l\'expression de $w_n$ en fonction de $n$.\r\n<li> Exprimer $u_{n+1} - u_n$ en fonction de $w_n$.\r\n<li> Démontrer que $\\un$ est décroissante.\r\n</ol>', '', '', '2024-04-06 15:33:31', '2024-04-12 11:27:00', 'Soient $\\un$ et $\\vn$ deux suites telles que $u_0 = 16$, $v_0  = 5$ et \\[ \\begin{cases} u_{n+1} = \\Frac{3u_n+2v_n}{5} \\\\ v_{n+1} = \\Frac{u_n+v_n}{2} \\end{cases} \\]\r\nOn pose $w_n = u_n-v_n$. \\\\\r\n\\enmb \\item Montrer que $\\wn$ est géométrique. \\\\\r\nEn déduire l\'expression de $w_n$ en fonction de $n$.\r\n\\item Exprimer $u_{n+1} - u_n$ en fonction de $w_n$.\r\n\\item Démontrer que $\\un$ est décroissante.\r\n\\fenmb', NULL, NULL),
(98, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 2$ et \\[u_{n}-2u_{n+1}=2n+3\\]\r\nSoit $b \\in \\R$, on pose $v_n = u_n+bn-1$.\r\n<ol class=\'enumb\'> <li> Déterminer $b$ pour que la suite $\\vn$ soit géométrique.\r\n<li> Exprimer $v_n$ en fonction de $n$. Puis, exprimer $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:33:38', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 2$ et \\[u_{n}-2u_{n+1}=2n+3\\]\r\nSoit $b \\in \\R$, on pose $v_n = u_n+bn-1$.\r\n\\enmb \\item Déterminer $b$ pour que la suite $\\vn$ soit géométrique.\r\n\\item Exprimer $v_n$ en fonction de $n$. Puis, exprimer $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(99, 13, NULL, 'Soit $\\un$ et $\\vn$ définies par les relations de récurrence suivantes : $u_1 = 1$, $v_1 = 3$ et \\[\\forall n \\in \\N, u_{n+1} = 3u_n+4v_n \\; \\text{ et } \\; v_{n+1} = u_n+3v_n\\]\r\nOn pose $w_n = u_n+2v_n$ et $t_n = u_n-2v_n$. \r\n<ol class=\'enumb\'> <li> Montrer que la suite $\\wn$ est géométrique.\r\n<li> Quelle est la nature de la suite $(t_n)$ ?\r\n<li> Exprimer $t_n$, puis $u_n$ puis $v_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:33:47', '2024-04-12 11:27:00', 'Soit $\\un$ et $\\vn$ définies par les relations de récurrence suivantes : $u_1 = 1$, $v_1 = 3$ et \\[\\forall n \\in \\N, u_{n+1} = 3u_n+4v_n \\; \\text{ et } \\; v_{n+1} = u_n+3v_n\\]\r\nOn pose $w_n = u_n+2v_n$ et $t_n = u_n-2v_n$. \r\n\\enmb \\item Montrer que la suite $\\wn$ est géométrique.\r\n\\item Quelle est la nature de la suite $(t_n)$ ?\r\n\\item Exprimer $t_n$, puis $u_n$ puis $v_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(100, 13, NULL, 'Soit $\\un$ définie par $u_0 =2$ et \\[u_{n+1} = \\Frac{3+2u_n}{u_n+4}\\] pour $n \\in \\N$. \r\n<ol class=\'enumb\'> <li> Montrer que le terme $u_n$ existe et est strictement positif pour tout $n \\in \\N$.\r\n<li> Montrer que $u_{n+1} = 1 \\iff u_n = 1$. En déduire que pour tout $n \\in \\N$, $u_n$ est différent de $1$.\r\n<li> On pose $v_n = \\Frac{u_n-1}{u_n+3}$. \\\\\r\nMontrer que $\\vn$ est géométrique puis en déduire l\'expression de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:33:54', '2024-04-12 11:27:00', 'Soit $\\un$ définie par $u_0 =2$ et \\[u_{n+1} = \\Frac{3+2u_n}{u_n+4}\\] pour $n \\in \\N$. \r\n\\enmb \\item Montrer que le terme $u_n$ existe et est strictement positif pour tout $n \\in \\N$.\r\n\\item Montrer que $u_{n+1} = 1 \\iff u_n = 1$. En déduire que pour tout $n \\in \\N$, $u_n$ est différent de $1$.\r\n\\item On pose $v_n = \\Frac{u_n-1}{u_n+3}$. \\\\\r\nMontrer que $\\vn$ est géométrique puis en déduire l\'expression de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(101, 13, NULL, 'Soit $\\un$ définie sur $\\N^*$ par $u_1 = \\Frac{2}{3}$ et \\[u_{n+1}=\\Frac{2}{3}u_n + \\Frac{2}{3^{n+1}}\\]On définit la suite $\\vn$ par $v_n = u_n + \\Frac{2}{3^n}$ pour tout $n \\in \\N^*$.\r\n<ol class=\'enumb\'> <li> Montrer que $\\vn$ est géométrique. Donner son premier terme et sa raison.\r\n<li> Calculer $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:34:04', '2024-04-12 11:27:00', 'Soit $\\un$ définie sur $\\N^*$ par $u_1 = \\Frac{2}{3}$ et \\[u_{n+1}=\\Frac{2}{3}u_n + \\Frac{2}{3^{n+1}}\\]On définit la suite $\\vn$ par $v_n = u_n + \\Frac{2}{3^n}$ pour tout $n \\in \\N^*$.\r\n\\enmb \\item Montrer que $\\vn$ est géométrique. Donner son premier terme et sa raison.\r\n\\item Calculer $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(102, 13, NULL, 'Soient $u$ et $v$ deux suites définies pour tout $n \\geqslant 0$ par \\[u_{n+1} = \\Frac{1}{3}(2u_n+v_n) \\text{ et } v_{n+1} = \\Frac{1}{3}(u_n+2v_n)\\]\r\n<ol class=\'enumb\'> <li> On pose $t_n = u_n-v_n$ et $s_n = u_n+v_n$. \\\\\r\nExprimer $t_n$ (resp. $s_n$) en fonction de $n$ et $t_0$ (resp. $s_0$).\r\n<li> En déduire l\'expression de $u_n$ et $v_n$ en fonction de $n$, $u_0$ et $v_0$.\r\n</ol>', '', '', '2024-04-06 15:34:13', '2024-04-12 11:27:00', 'Soient $u$ et $v$ deux suites définies pour tout $n \\geqslant 0$ par \\[u_{n+1} = \\Frac{1}{3}(2u_n+v_n) \\text{ et } v_{n+1} = \\Frac{1}{3}(u_n+2v_n)\\]\r\n\\enmb \\item On pose $t_n = u_n-v_n$ et $s_n = u_n+v_n$. \\\\\r\nExprimer $t_n$ (resp. $s_n$) en fonction de $n$ et $t_0$ (resp. $s_0$).\r\n\\item En déduire l\'expression de $u_n$ et $v_n$ en fonction de $n$, $u_0$ et $v_0$.\r\n\\fenmb', NULL, NULL),
(103, 13, NULL, 'Soit $\\un$ la suite définie par $u_1 = 2$ et pour tout entier $n \\geqslant 1$, \\[u_{n+1} = 2 \\Frac{(n+1)^2}{n(n+2)}u_n\\] \r\n<ol class=\'enumb\'> <li> On pose $v_n = \\Frac{n+1}{n}u_n$. Montrer que $\\vn$ est géométrique.\r\n<li> En déduire l\'expression de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:34:21', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_1 = 2$ et pour tout entier $n \\geqslant 1$, \\[u_{n+1} = 2 \\Frac{(n+1)^2}{n(n+2)}u_n\\] \r\n\\enmb \\item On pose $v_n = \\Frac{n+1}{n}u_n$. Montrer que $\\vn$ est géométrique.\r\n\\item En déduire l\'expression de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(104, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[u_{n+1} = \\sqrt{1+\\Frac{u_n^2}{2}}\\]\r\nOn pose $v_n = u_n^2$.\r\n<ol class=\'enumb\'> <li> Montrer que $v_{n+1} = 1 + \\Frac{v_n}{2}$. \r\n<li> On pose $w_n = v_n-2$. \\\\\r\nMontrer que $\\wn$ est géométrique de raison $\\Frac{1}{2}$.\r\n<li> En déduire que $u_n = \\sqrt{2+\\Frac{1}{2^n}(u_0^2-2)}$.\r\n</ol>', '', '', '2024-04-06 15:34:30', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[u_{n+1} = \\sqrt{1+\\Frac{u_n^2}{2}}\\]\r\nOn pose $v_n = u_n^2$.\r\n\\enmb \\item Montrer que $v_{n+1} = 1 + \\Frac{v_n}{2}$. \r\n\\item On pose $w_n = v_n-2$. \\\\\r\nMontrer que $\\wn$ est géométrique de raison $\\Frac{1}{2}$.\r\n\\item En déduire que $u_n = \\sqrt{2+\\Frac{1}{2^n}(u_0^2-2)}$.\r\n\\fenmb', NULL, NULL),
(105, 13, NULL, 'Soit $k$ un réel strictement positif différent de $1$. \\\\\r\nSoit $\\un$ la suite définie par $u_0 > 0$ et \\[u_{n+1} = \\Frac{1+ku_n}{k+u_n}\\]\r\nOn pose $v_n = \\Frac{u_n-1}{u_n+1}$.\r\n<ol class=\'enumb\'>\r\n<li> Montrer que la suite $\\vn$ est géométrique de raison $q=\\Frac{k-1}{k+1}$.\r\n<li> En déduire l\'expression de $v_n$ puis de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 15:34:38', '2024-04-12 11:27:00', 'Soit $k$ un réel strictement positif différent de $1$. \\\\\r\nSoit $\\un$ la suite définie par $u_0 > 0$ et \\[u_{n+1} = \\Frac{1+ku_n}{k+u_n}\\]\r\nOn pose $v_n = \\Frac{u_n-1}{u_n+1}$.\r\n\\enmb\r\n\\item Montrer que la suite $\\vn$ est géométrique de raison $q=\\Frac{k-1}{k+1}$.\r\n\\item En déduire l\'expression de $v_n$ puis de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(106, 13, NULL, '<ol class=\'enumb\'> <li> Soit $\\un$ une suite géométrique de raison $q$ et de premier terme $u_0$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_{n}u_{n+2} = u_{n+1}^2$.\r\n<li> Réciproquement on suppose que pour tout entier naturel $n$, $u_nu_{n+2} = u_{n+1}^2$. \\\\\r\nOn suppose que pour tout entier naturel $n$, $u_n$ est non nul.\r\n<ol class=\'enumb\'> <li> On pose $w_n = \\Frac{u_{n+1}}{u_n}$. Montrer que $\\wn$ est constante. \r\n<li> En déduire que $\\un$ est géométrique.\r\n</ol>\r\n</ol>', '', '', '2024-04-06 15:34:46', '2024-04-12 11:27:00', '\\enmb \\item Soit $\\un$ une suite géométrique de raison $q$ et de premier terme $u_0$. \\\\\r\nMontrer que pour tout entier naturel $n$, $u_{n}u_{n+2} = u_{n+1}^2$.\r\n\\item Réciproquement on suppose que pour tout entier naturel $n$, $u_nu_{n+2} = u_{n+1}^2$. \\\\\r\nOn suppose que pour tout entier naturel $n$, $u_n$ est non nul.\r\n\\enmb \\item On pose $w_n = \\Frac{u_{n+1}}{u_n}$. Montrer que $\\wn$ est constante. \r\n\\item En déduire que $\\un$ est géométrique.\r\n\\fenmb\r\n\\fenmb', NULL, NULL),
(107, 13, NULL, 'Soit $\\un$ la suite définie par $u_0 = 2$ et pour tout $n \\geqslant 0$, $u_{n+1} = (u_n)^3$.\\\\\r\nOn pose $v_n = \\ln(u_n)$. \\\\\r\nMontrer que $v_n$ est bien défini pour tout $n$, puis reconnaître $v_n$. Déterminer alors $v_n$ puis $u_n$ en fonction de $n$.', '', '', '2024-04-06 15:35:00', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 2$ et pour tout $n \\geqslant 0$, $u_{n+1} = (u_n)^3$.\\\\\r\nOn pose $v_n = \\ln(u_n)$. \\\\\r\nMontrer que $v_n$ est bien défini pour tout $n$, puis reconnaître $v_n$. Déterminer alors $v_n$ puis $u_n$ en fonction de $n$.', NULL, NULL),
(108, 14, NULL, 'Déterminer le sens de variation des suites suivantes : \r\n<ul class=\'point\'> <li> $u_n = n^2+n-1$ \\\\\r\n\r\n<li> $u_n = 13 - \\Frac{100}{9}\\times0,9^n $ \\\\\r\n\r\n<li> $u_n = \\sqrt{n+5}$ \\\\\r\n\r\n<li> $u_n = \\Frac{2n+5}{n+1}$ \\\\\r\n\r\n<li> $u_n = e^{-n+1}$ \\\\\r\n\r\n<li> $u_n = \\Frac{n^2}{n+1}$ \\\\\r\n</ul>', '', '', '2024-04-06 15:48:12', '2024-04-12 11:27:00', 'Déterminer le sens de variation des suites suivantes : \r\n\\itm \\item $u_n = n^2+n-1$ \\\\\r\n\r\n\\item $u_n = 13 - \\Frac{100}{9}\\times0,9^n $ \\\\\r\n\r\n\\item $u_n = \\sqrt{n+5}$ \\\\\r\n\r\n\\item $u_n = \\Frac{2n+5}{n+1}$ \\\\\r\n\r\n\\item $u_n = e^{-n+1}$ \\\\\r\n\r\n\\item $u_n = \\Frac{n^2}{n+1}$ \\\\\r\n\\fitm', NULL, NULL),
(109, 14, NULL, 'Soit $\\un$ la suite définie par $u_0 = 2$ et\\[ u_{n+1} = u_n(1-5u_n)\\]\r\nEtudier le sens de variation de $\\un$.', '', '', '2024-04-06 15:49:12', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 2$ et\\[ u_{n+1} = u_n(1-5u_n)\\]\r\nEtudier le sens de variation de $\\un$.', NULL, NULL),
(110, 14, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour tout entier naturel $n$, $ u_{n+1} = \\Frac{u_n}{1+u_n} $.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout entier naturel $n$, $u_n > 0$.\r\n<li> Déterminer le sens de variation de $\\un$.\r\n</ol>', '', '', '2024-04-06 15:49:26', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 1$ et pour tout entier naturel $n$, $ u_{n+1} = \\Frac{u_n}{1+u_n} $.\r\n\\enmb \\item Montrer que pour tout entier naturel $n$, $u_n > 0$.\r\n\\item Déterminer le sens de variation de $\\un$.\r\n\\fenmb', NULL, NULL),
(111, 14, NULL, 'Soit $(u_n)$ la suite définie pour tout entier naturel $n$ par $u_0 = 2$ et $u_{n+1} = \\Frac{1}{2} u_n^2 + 1$.\\\\\r\nConjecturer le sens de variation de cette suite puis démontrer votre conjecture.', '', '', '2024-04-06 15:49:58', '2024-04-12 11:27:00', 'Soit $(u_n)$ la suite définie pour tout entier naturel $n$ par $u_0 = 2$ et $u_{n+1} = \\Frac{1}{2} u_n^2 + 1$.\\\\\r\nConjecturer le sens de variation de cette suite puis démontrer votre conjecture.', NULL, NULL),
(112, 14, NULL, 'Soit $\\un$ la suite définie par $u_1 = \\Frac{1}{e}$ et \\[u_{n+1} = \\Frac{1}{e}\\parenthese{1+\\Frac{1}{n}}u_n\\]\r\n<ol class=\'enumb\'> <li> Montrer que $n \\geqslant 1$, $1+\\Frac{1}{n} \\leqslant e$.\r\n<li> Montrer que $\\un$ est décroissante.\r\n</ol>', '', '', '2024-04-06 15:50:34', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_1 = \\Frac{1}{e}$ et \\[u_{n+1} = \\Frac{1}{e}\\parenthese{1+\\Frac{1}{n}}u_n\\]\r\n\\enmb \\item Montrer que $n \\geqslant 1$, $1+\\Frac{1}{n} \\leqslant e$.\r\n\\item Montrer que $\\un$ est décroissante.\r\n\\fenmb', NULL, NULL),
(113, 14, NULL, 'Soit $\\un$ définie par $u_0 = 1$ et \\[u_{n+1} = \\Frac{u_n^3+2}{u_n^2+1}\\]\r\n<ol class=\'enumb\'> <li> Montrer par récurrence que $0 < u_n < 2$ pour tout $n \\in \\N$.\r\n<li> Etudier les variations de la suite $\\un$.\r\n</ol>', '', '', '2024-04-06 15:50:50', '2024-04-12 11:27:00', 'Soit $\\un$ définie par $u_0 = 1$ et \\[u_{n+1} = \\Frac{u_n^3+2}{u_n^2+1}\\]\r\n\\enmb \\item Montrer par récurrence que $0 < u_n < 2$ pour tout $n \\in \\N$.\r\n\\item Etudier les variations de la suite $\\un$.\r\n\\fenmb', NULL, NULL),
(114, 14, NULL, 'Montrer que la suite $\\un$ définie par $u_0 = 0$ et pour tout entier naturel $n$, $u_{n+1} =\\Frac{u_{n}^2+1}{2}$ est croissante.', '', '', '2024-04-06 15:51:00', '2024-04-12 11:27:00', 'Montrer que la suite $\\un$ définie par $u_0 = 0$ et pour tout entier naturel $n$, $u_{n+1} =\\Frac{u_{n}^2+1}{2}$ est croissante.', NULL, NULL),
(115, 14, NULL, 'Soit $\\un$ la suite définie par $u_0 = 0,3$ et $u_{n+1} = f(u_n)$ avec $f(x) = 2x(1-x)$.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout entier naturel $n$, $0 \\leqslant u_n \\leqslant 0,5$. \r\n<li> Montrer par récurrence que pour tout entier naturel $n$, $u_n \\leqslant u_{n+1}$.\r\n<li> En déduire le sens de variation de $\\un$.\r\n</ol>', '', '', '2024-04-06 15:51:27', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 0,3$ et $u_{n+1} = f(u_n)$ avec $f(x) = 2x(1-x)$.\r\n\\enmb \\item Montrer que pour tout entier naturel $n$, $0 \\leqslant u_n \\leqslant 0,5$. \r\n\\item Montrer par récurrence que pour tout entier naturel $n$, $u_n \\leqslant u_{n+1}$.\r\n\\item En déduire le sens de variation de $\\un$.\r\n\\fenmb', NULL, NULL),
(116, 14, NULL, 'Soit $\\un$ la suite définie par $u_0 = 5$ et $u_{n+1} = f(u_n)$ avec $f(x) = \\Frac{1}{2}\\parenthese{x+\\Frac{11}{x}}$.\r\n<ol class=\'enumb\'> <li> Montrer par récurrence que pour tout entier naturel $n$, $u_n \\geqslant u_{n+1} \\geqslant \\sqrt{11}$.\r\n<li> En déduire le sens de variation de $\\un$.\r\n</ol>', '', '', '2024-04-06 15:51:34', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 5$ et $u_{n+1} = f(u_n)$ avec $f(x) = \\Frac{1}{2}\\parenthese{x+\\Frac{11}{x}}$.\r\n\\enmb \\item Montrer par récurrence que pour tout entier naturel $n$, $u_n \\geqslant u_{n+1} \\geqslant \\sqrt{11}$.\r\n\\item En déduire le sens de variation de $\\un$.\r\n\\fenmb', NULL, NULL),
(117, 14, NULL, 'Soit $\\un$ la suite définie par $u_0 = 0$ et $u_{n+1} = f(u_n)$ avec $f(x) = \\Frac{-x-4}{x+3}$.\r\n<ol class=\'enumb\'> <li> Montrer par récurrence que pour tout entier naturel $n$, $u_{n+1} \\leqslant u_{n}$.\r\n<li> En déduire le sens de variation de $\\un$.\r\n</ol>', '', '', '2024-04-06 15:52:02', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 0$ et $u_{n+1} = f(u_n)$ avec $f(x) = \\Frac{-x-4}{x+3}$.\r\n\\enmb \\item Montrer par récurrence que pour tout entier naturel $n$, $u_{n+1} \\leqslant u_{n}$.\r\n\\item En déduire le sens de variation de $\\un$.\r\n\\fenmb', NULL, NULL),
(118, 14, NULL, 'Soit $\\un$ la suite définie par $u_0 = 1$ et $u_{n+1} = \\Frac{u_n}{1+u_n^2}$. \r\n<ol class=\'enumb\'> <li> Montrer que $\\forall n \\in \\N$, $u_n \\geqslant 0$.\r\n<li> Etudier la monotonie de $\\un$.\r\n</ol>', '', '', '2024-04-06 15:52:14', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 = 1$ et $u_{n+1} = \\Frac{u_n}{1+u_n^2}$. \r\n\\enmb \\item Montrer que $\\forall n \\in \\N$, $u_n \\geqslant 0$.\r\n\\item Etudier la monotonie de $\\un$.\r\n\\fenmb', NULL, NULL),
(119, 14, NULL, 'Etudier la monotonie de la suite $\\un$ définie par $u_0 = 1$ et $u_{n+1} = u_n^2+u_n$.', '', '', '2024-04-06 15:52:25', '2024-04-12 11:27:00', 'Etudier la monotonie de la suite $\\un$ définie par $u_0 = 1$ et $u_{n+1} = u_n^2+u_n$.', NULL, NULL),
(120, 14, NULL, 'Soit $(a,b) \\in (\\R^{*}_{+})^2$ et deux suites $\\an$ et $\\bn$ définies par $a_0 = a$, $b_0 = b$ et \\[ a_{n+1} = \\sqrt{a_nb_n} \\quad \\text{ et } \\quad b_{n+1} = \\Frac{a_n+b_n}{2} \\]\r\n<ol class=\'enumb\'> <li> Montrer que pour tout réel $x,y$ strictement positifs, $\\sqrt{xy} \\leqslant \\Frac{x+y}{2}$.\r\n<li> Montrer que pour tout $n \\in \\N$, $a_n$ et $b_n$ existent et sont strictement positifs.\r\n<li> Montrer que pour tout $n \\in \\N^*$, $b_n \\geqslant a_n$.\r\n<li> <ol class=\'enumb\'> <li> Montrer que $\\an$ est croissante.\r\n<li> Montrer que $\\bn$ est décroissante.\r\n</ol>\r\n</ol>', '', '', '2024-04-06 15:52:36', '2024-04-12 11:27:00', 'Soit $(a,b) \\in (\\R^{*}_{+})^2$ et deux suites $\\an$ et $\\bn$ définies par $a_0 = a$, $b_0 = b$ et \\[ a_{n+1} = \\sqrt{a_nb_n} \\quad \\text{ et } \\quad b_{n+1} = \\Frac{a_n+b_n}{2} \\]\r\n\\enmb \\item Montrer que pour tout réel $x,y$ strictement positifs, $\\sqrt{xy} \\leqslant \\Frac{x+y}{2}$.\r\n\\item Montrer que pour tout $n \\in \\N$, $a_n$ et $b_n$ existent et sont strictement positifs.\r\n\\item Montrer que pour tout $n \\in \\N^*$, $b_n \\geqslant a_n$.\r\n\\item \\enmb \\item Montrer que $\\an$ est croissante.\r\n\\item Montrer que $\\bn$ est décroissante.\r\n\\fenmb\r\n\\fenmb', NULL, NULL),
(121, 14, NULL, 'Soit $a \\in ]0,1[$. On considère la suite $\\un$ définie pour tout entier $n \\geqslant 1$ par $u_n = \\Frac{2n}{a^n}$. \\\\\r\nMontrer que la suite $\\un$ est strictement croissante.', '', '', '2024-04-06 15:52:50', '2024-04-12 11:27:00', 'Soit $a \\in ]0,1[$. On considère la suite $\\un$ définie pour tout entier $n \\geqslant 1$ par $u_n = \\Frac{2n}{a^n}$. \\\\\r\nMontrer que la suite $\\un$ est strictement croissante.', NULL, NULL),
(122, 14, NULL, 'Etudier la monotonie de la suite $\\un_{n\\geqslant 1}$ définie par $u_n = \\displaystyle \\sum_{k=n}^{2n} \\Frac{1}{k}$.', '', '', '2024-04-06 15:52:57', '2024-04-12 11:27:00', 'Etudier la monotonie de la suite $\\un_{n\\geqslant 1}$ définie par $u_n = \\displaystyle \\sum_{k=n}^{2n} \\Frac{1}{k}$.', NULL, NULL),
(123, 14, NULL, 'On considère la suite $\\un$ définie par $u_0 = 2$ et pour tout $n \\in \\N$, $u_{n+1} = 1 - \\Frac{1}{u_n}$.\\\\\r\nDémontrer que la suite $(u_{3n})$ est constante.', '', '', '2024-04-06 15:53:05', '2024-04-12 11:27:00', 'On considère la suite $\\un$ définie par $u_0 = 2$ et pour tout $n \\in \\N$, $u_{n+1} = 1 - \\Frac{1}{u_n}$.\\\\\r\nDémontrer que la suite $(u_{3n})$ est constante.', NULL, NULL),
(124, 14, NULL, 'Déterminer le sens de variations de la suite $\\un$ définie par $u_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{1}{ke^k}$.', '', '', '2024-04-06 15:53:19', '2024-04-12 11:27:00', 'Déterminer le sens de variations de la suite $\\un$ définie par $u_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{1}{ke^k}$.', NULL, NULL),
(125, 15, NULL, 'Calculer la somme $1 + 3 + 5 + \\hdots + 139$.', '', '', '2024-04-06 16:02:22', '2024-04-12 11:27:00', 'Calculer la somme $1 + 3 + 5 + \\hdots + 139$.', NULL, NULL),
(126, 15, NULL, 'Calculer la somme $9+3 + \\Frac{1}{3} + \\Frac{1}{9} + \\hdots + \\Frac{1}{59049}$.', '', '', '2024-04-06 16:03:01', '2024-04-12 11:27:00', 'Calculer la somme $9+3 + \\Frac{1}{3} + \\Frac{1}{9} + \\hdots + \\Frac{1}{59049}$.', NULL, NULL),
(127, 15, NULL, 'Calculer la somme $\\displaystyle \\sum_{k=0}^{n} \\parenthese{\\Frac{1}{7}}^{k}$.', '', '', '2024-04-06 16:03:15', '2024-04-12 11:27:00', 'Calculer la somme $\\displaystyle \\sum_{k=0}^{n} \\parenthese{\\Frac{1}{7}}^{k}$.', NULL, NULL),
(128, 15, NULL, 'On pose $u_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{9}{10^k}$.\r\n<ol class=\'enumb\'> <li> Calculer $u_1$, $u_2$.\r\n<li> Déterminer l\'expression de $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 16:03:25', '2024-04-12 11:27:00', 'On pose $u_n = \\displaystyle \\sum_{k=1}^{n} \\Frac{9}{10^k}$.\r\n\\enmb \\item Calculer $u_1$, $u_2$.\r\n\\item Déterminer l\'expression de $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(129, 15, NULL, 'Pour $n \\geqslant 2$, déterminer une expression simplifiée de $\\displaystyle \\sum_{k=1}^{n-1}k\\ln\\parenthese{1+\\Frac{1}{k}}$.', '', '', '2024-04-06 16:03:34', '2024-04-12 11:27:00', 'Pour $n \\geqslant 2$, déterminer une expression simplifiée de $\\displaystyle \\sum_{k=1}^{n-1}k\\ln\\parenthese{1+\\Frac{1}{k}}$.', NULL, NULL),
(130, 15, NULL, 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[\\forall n \\in \\N, u_{n+1} = (n+1)u_n+2^n(n+1)!\\]\r\n<ol class=\'enumb\'> <li> On pose $v_n = \\Frac{u_n}{n!}$ pour tout $n \\in \\N$. Exprimer $v_n$ en fonction de $n$ pour tout $n \\in \\N$.\r\n<li> Déterminer $u_n$ en fonction de $n$.', '', '', '2024-04-06 16:03:47', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[\\forall n \\in \\N, u_{n+1} = (n+1)u_n+2^n(n+1)!\\]\r\n\\enmb \\item On pose $v_n = \\Frac{u_n}{n!}$ pour tout $n \\in \\N$. Exprimer $v_n$ en fonction de $n$ pour tout $n \\in \\N$.\r\n\\item Déterminer $u_n$ en fonction de $n$.', NULL, NULL),
(131, 15, NULL, 'Soit $\\un$ une suite définie par $u_0 = 0$ et par \\[u_{n+1} = u_n + \\Frac{1}{6}(3n+2)\\]\r\nDéterminer, par sommation télescopique, l\'expression de $u_n$ en fonction de $n$.', '', '', '2024-04-06 16:03:59', '2024-04-12 11:27:00', 'Soit $\\un$ une suite définie par $u_0 = 0$ et par \\[u_{n+1} = u_n + \\Frac{1}{6}(3n+2)\\]\r\nDéterminer, par sommation télescopique, l\'expression de $u_n$ en fonction de $n$.', NULL, NULL),
(132, 15, NULL, 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[u_{n+1} = (n+1)u_n+2^n(n+1)!\\]\r\n<ol class=\'enumb\'> <li> On pose $v_n = \\Frac{u_n}{n!}$. Exprimer $v_n$ en fonction de $n$.\r\n<li> Déterminer $u_n$ en fonction de $n$.\r\n</ol>', '', '', '2024-04-06 16:04:09', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 \\in \\R$ et \\[u_{n+1} = (n+1)u_n+2^n(n+1)!\\]\r\n\\enmb \\item On pose $v_n = \\Frac{u_n}{n!}$. Exprimer $v_n$ en fonction de $n$.\r\n\\item Déterminer $u_n$ en fonction de $n$.\r\n\\fenmb', NULL, NULL),
(133, 15, NULL, 'Soit $\\un$ la suite définie pour tout $n \\in \\N^*$ par \\[u_n = 1+\\Frac{1}{1!}+\\Frac{1}{2!}+\\hdots+\\Frac{1}{n!}\\]\r\n<ol class=\'enumb\'> <li> Montrer que pour tout $k \\in \\N^*$, $\\Frac{1}{k!} \\leqslant \\Frac{1}{2^{k-1}}$.\r\n<li> En déduire que pour tout $n \\in \\N$, la suite $(u_n)$ est majorée par 3.\r\n</ol>', '', '', '2024-04-06 16:04:24', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie pour tout $n \\in \\N^*$ par \\[u_n = 1+\\Frac{1}{1!}+\\Frac{1}{2!}+\\hdots+\\Frac{1}{n!}\\]\r\n\\enmb \\item Montrer que pour tout $k \\in \\N^*$, $\\Frac{1}{k!} \\leqslant \\Frac{1}{2^{k-1}}$.\r\n\\item En déduire que pour tout $n \\in \\N$, la suite $(u_n)$ est majorée par 3.\r\n\\fenmb', NULL, NULL),
(134, 15, NULL, 'Soit $\\un$ une suite définie sur $\\N^*$ telle que :\r\n<ul class=\'point\'> <li> $u_1 = 2023$;\r\n<li> pour tout entier $n \\geqslant 1$, $\\displaystyle \\sum_{k=1}^{n} u_k = n^2u_n$.\r\n</ul>\r\nCalculer $u_{2023}$.', '', '', '2024-04-06 16:04:32', '2024-04-12 11:27:00', 'Soit $\\un$ une suite définie sur $\\N^*$ telle que :\r\n\\itm \\item $u_1 = 2023$;\r\n\\item pour tout entier $n \\geqslant 1$, $\\displaystyle \\sum_{k=1}^{n} u_k = n^2u_n$.\r\n\\fitm\r\nCalculer $u_{2023}$.', NULL, NULL),
(135, 17, NULL, 'Dans chaque cas, déterminer $\\limn u_n$<br>\r\n<ul class=\'point\'> <li> $u_n = 0,1^n$ <br>\r\n<li> $u_n = 5^n+6^n$ <br>\r\n<li> $u_n = \\Frac{0,9^n-0,1^n}{1+3^n}$ <br>\r\n<li> $u_n=\\parenthese{\\Frac{1}{10^5}+1}^n$ <br>\r\n<li> $u_n = \\Frac{3}{0,5^n}$. <br>\r\n<li> $u_n = 7-\\parenthese{\\Frac{e}{2}}^{n-1}$. <br>\r\n<li> $u_n = -8\\parenthese{\\Frac{1}{5}}^n+10\\times0,5^n$.\r\n</ul>', '', '', '2024-04-07 06:45:57', '2024-04-12 11:27:00', 'Dans chaque cas, déterminer $\\limn u_n$\\\\\r\n\\itm \\item $u_n = 0,1^n$ \\\\\r\n\\item $u_n = 5^n+6^n$ \\\\\r\n\\item $u_n = \\Frac{0,9^n-0,1^n}{1+3^n}$ \\\\\r\n\\item $u_n=\\parenthese{\\Frac{1}{10^5}+1}^n$ \\\\\r\n\\item $u_n = \\Frac{3}{0,5^n}$. \\\\\r\n\\item $u_n = 7-\\parenthese{\\Frac{e}{2}}^{n-1}$. \\\\\r\n\\item $u_n = -8\\parenthese{\\Frac{1}{5}}^n+10\\times0,5^n$.\r\n\\fitm', NULL, NULL),
(136, 17, NULL, 'Dans chaque cas, déterminer $\\limn u_n$<br>\r\n<ul class=\'point\'> <li> $u_n = -10\\times2^n$ <br>\r\n<li> $1-10\\parenthese{1-\\parenthese{\\Frac{1}{2}}^{n+1}}$ <br>\r\n<li> $u_n = 1+2\\times\\parenthese{\\Frac{5}{6}}^n$ <br>\r\n<li> $u_n=4 \\times \\Frac{1-0,5^{n+1}}{1-0,5}$ <br>\r\n<li> $u_n = 13-\\Frac{100}{9}\\times0,9^n$. <br>\r\n<li> $u_n = 20+160\\times0,955^n$.\r\n</ul>', '', '', '2024-04-07 06:47:38', '2024-04-12 11:27:00', 'Dans chaque cas, déterminer $\\limn u_n$\\\\\r\n\\itm \\item $u_n = -10\\times2^n$ \\\\\r\n\\item $1-10\\parenthese{1-\\parenthese{\\Frac{1}{2}}^{n+1}}$ \\\\\r\n\\item $u_n = 1+2\\times\\parenthese{\\Frac{5}{6}}^n$ \\\\\r\n\\item $u_n=4 \\times \\Frac{1-0,5^{n+1}}{1-0,5}$ \\\\\r\n\\item $u_n = 13-\\Frac{100}{9}\\times0,9^n$. \\\\\r\n\\item $u_n = 20+160\\times0,955^n$.\r\n\\fitm', NULL, NULL),
(137, 17, NULL, 'Déterminer, en justifiant, la limite de chacune des suites définies sur $\\N$ par :<br>\r\n<ul class=\'point\'>\r\n<li> $u_n = 4 \\times \\parenthese{\\Frac{2}{3}}^n$ <br>\r\n<li> $u_n = 10^{-3} \\times \\parenthese{\\Frac{5}{3}}^n$ <br>\r\n<li> $u_n = - \\parenthese{\\Frac{9}{4}}^n$ <br>\r\n<li> $u_n = 40 \\times (1-0,1^n)$ <br>\r\n<li> $u_n = \\parenthese{1+ \\Frac{1}{100}}^n$ <br>\r\n<li> $u_n = \\Frac{3+0,3^n}{1+0,5^n}$ <br>\r\n<li> $u_n = \\Frac{ 3+3^n}{4+0,5^n}$\r\n</ul>', '', '', '2024-04-07 06:48:00', '2024-04-12 11:27:00', 'Déterminer, en justifiant, la limite de chacune des suites définies sur $\\N$ par :\\\\\r\n\\itm\r\n\\item $u_n = 4 \\times \\parenthese{\\Frac{2}{3}}^n$ \\\\\r\n\\item $u_n = 10^{-3} \\times \\parenthese{\\Frac{5}{3}}^n$ \\\\\r\n\\item $u_n = - \\parenthese{\\Frac{9}{4}}^n$ \\\\\r\n\\item $u_n = 40 \\times (1-0,1^n)$ \\\\\r\n\\item $u_n = \\parenthese{1+ \\Frac{1}{100}}^n$ \\\\\r\n\\item $u_n = \\Frac{3+0,3^n}{1+0,5^n}$ \\\\\r\n\\item $u_n = \\Frac{ 3+3^n}{4+0,5^n}$\r\n\\fitm', NULL, NULL),
(138, 17, NULL, 'Déterminer la limite de la suite $\\vn$ définie par $v_n = \\Frac{ 3^{n+1}}{2^n}$', NULL, NULL, '2024-04-07 06:48:37', '2024-04-12 11:27:00', 'Déterminer la limite de la suite $\\vn$ définie par $v_n = \\Frac{ 3^{n+1}}{2^n}$', NULL, NULL),
(139, 17, NULL, 'Déterminer la limite de la suite $\\vn$ définie par $v_n = \\Frac{ 3^n}{2^n+1}$', NULL, NULL, '2024-04-07 06:48:47', '2024-04-12 11:27:00', 'Déterminer la limite de la suite $\\vn$ définie par $v_n = \\Frac{ 3^n}{2^n+1}$', NULL, NULL),
(140, 17, NULL, '<ol class=\'enumb\'> <li> Déterminer la limite de la suite $\\un$, géométrique de raison $\\frac{1}{e}$ et de premier terme $u_0 = 500$.\r\n<li> Déterminer la limite de la suite $\\vn$, géométrique de raison $\\pi$ et de premier terme $0,5$.\r\n<li> Déterminer la limite des suites $(u_n+v_n)$, $\\parenthese{\\Frac{u_n}{v_n}}$, $\\parenthese{\\Frac{v_n}{u_n}}$, $\\parenthese{u_n-v_n}$, $\\parenthese{v_n-u_n}$, $\\parenthese{u_nv_n}$.\r\n</ol>', NULL, NULL, '2024-04-07 06:48:59', '2024-04-12 11:27:00', '\\enmb \\item Déterminer la limite de la suite $\\un$, géométrique de raison $\\frac{1}{e}$ et de premier terme $u_0 = 500$.\r\n\\item Déterminer la limite de la suite $\\vn$, géométrique de raison $\\pi$ et de premier terme $0,5$.\r\n\\item Déterminer la limite des suites $(u_n+v_n)$, $\\parenthese{\\Frac{u_n}{v_n}}$, $\\parenthese{\\Frac{v_n}{u_n}}$, $\\parenthese{u_n-v_n}$, $\\parenthese{v_n-u_n}$, $\\parenthese{u_nv_n}$.\r\n\\fenmb', NULL, NULL),
(141, 17, NULL, 'On lance $n$ fois de suite un dé équilibré et on note $p_n$ la probabilité d\'obtenir au moins une fois la face 6. \r\n<ol class=\'enumb\'> <li> Exprimer $p_n$ en fonction de $n$. \r\n<li> Etudier la limite de la suite $(p_n)$. Interpréter.\r\n</ol>', NULL, NULL, '2024-04-07 06:49:10', '2024-04-12 11:27:00', 'On lance $n$ fois de suite un dé équilibré et on note $p_n$ la probabilité d\'obtenir au moins une fois la face 6. \r\n\\enmb \\item Exprimer $p_n$ en fonction de $n$. \r\n\\item Etudier la limite de la suite $(p_n)$. Interpréter.\r\n\\fenmb', NULL, NULL),
(142, 17, NULL, 'Soit $a$, $b$ deux réels non nuls tels que $a \\neq 1$. Soit $\\un$ la suite définie par \\[ u_{n+1} = au_n+b \\]\r\non pose $v_n = u_n - \\lambda$ avec $\\lambda = \\Frac{b}{1-a}$.\r\n<ol class=\'enumb\'> <li> Montrer que $\\vn$ est géométrique de raison $a$.\r\n<li> En déduire la limite de $\\un$ suivant les valeurs de $a$.\r\n</ol>', NULL, NULL, '2024-04-07 06:50:48', '2024-04-12 11:27:00', 'Soit $a$, $b$ deux réels non nuls tels que $a \\neq 1$. Soit $\\un$ la suite définie par \\[ u_{n+1} = au_n+b \\]\r\non pose $v_n = u_n - \\lambda$ avec $\\lambda = \\Frac{b}{1-a}$.\r\n\\enmb \\item Montrer que $\\vn$ est géométrique de raison $a$.\r\n\\item En déduire la limite de $\\un$ suivant les valeurs de $a$.\r\n\\fenmb', NULL, NULL),
(143, 17, NULL, 'Soit $a \\in ]-1,1[$. Pour $n \\in \\N$, on considère $S_n = \\displaystyle \\sum_{k=0}^{n}a^k$. <br>\r\nMontrer que $\\limn S_n = \\Frac{1}{1-a}$.', '', '', '2024-04-07 06:50:57', '2024-04-12 11:27:00', 'Soit $a \\in ]-1,1[$. Pour $n \\in \\N$, on considère $S_n = \\displaystyle \\sum_{k=0}^{n}a^k$. \\\\\r\nMontrer que $\\limn S_n = \\Frac{1}{1-a}$.', NULL, NULL),
(144, 17, NULL, 'Déterminer la limite des suites suivantes : <br>\r\n<ul>\r\n<li> $u_n = \\displaystyle \\frac{0,5^n-0,2^n}{2^n+1}$ <br>\r\n<li> $u_n = \\displaystyle \\sum_{k=0}^n \\frac{1}{2^k}$ <br>\r\n<li> $u_n = \\displaystyle \\sum_{k=0}^n \\left(\\frac{3}{2} \\right)^k$ \r\n</ul>', '', '', '2024-04-07 06:51:05', '2024-04-12 11:27:00', 'Déterminer la limite des suites suivantes : \\\\\r\n\\begin{itemize}\r\n\\item $u_n = \\displaystyle \\frac{0,5^n-0,2^n}{2^n+1}$ \\\\\r\n\\item $u_n = \\displaystyle \\sum_{k=0}^n \\frac{1}{2^k}$ \\\\\r\n\\item $u_n = \\displaystyle \\sum_{k=0}^n \\left(\\frac{3}{2} \\right)^k$ \r\n\\end{itemize}', NULL, NULL),
(145, 17, NULL, 'Soit $\\un$ la suite définie par $u_0 > 0$ et $u_{n+1} = \\sqrt{u_n}$. \r\n<ol class=\'enumb\'> <li> Montrer par récurrence que, pour tout entier naturel $n$, $u_n = u_0^{\\parenthese{\\frac{1}{2}}^n}$.\r\n<li> Déterminer la limite de la suite $\\un$.\r\n</ol>', NULL, NULL, '2024-04-07 06:51:13', '2024-04-12 11:27:00', 'Soit $\\un$ la suite définie par $u_0 > 0$ et $u_{n+1} = \\sqrt{u_n}$. \r\n\\enmb \\item Montrer par récurrence que, pour tout entier naturel $n$, $u_n = u_0^{\\parenthese{\\frac{1}{2}}^n}$.\r\n\\item Déterminer la limite de la suite $\\un$.\r\n\\fenmb', NULL, NULL),
(146, 17, NULL, 'Déterminer la limite de $\\parenthese{\\Frac{2}{q}}^n$ lorsque $n$ tend vers $+\\infty$ suivant les valeurs de $q$.', NULL, NULL, '2024-04-07 06:51:20', '2024-04-12 11:27:00', 'Déterminer la limite de $\\parenthese{\\Frac{2}{q}}^n$ lorsque $n$ tend vers $+\\infty$ suivant les valeurs de $q$.', NULL, NULL),
(147, 17, NULL, 'Déterminer la limite de $\\parenthese{-3q+1}^n$ lorsque $n$ tend vers $+\\infty$ suivant les valeurs de $q$.', NULL, NULL, '2024-04-07 06:51:26', '2024-04-12 11:27:00', 'Déterminer la limite de $\\parenthese{-3q+1}^n$ lorsque $n$ tend vers $+\\infty$ suivant les valeurs de $q$.', NULL, NULL),
(148, 17, NULL, 'Soit $a \\in \\Rpe$.\r\n<ol class=\'enumb\'> <li> Montrer par récurrence que pour tout entier naturel $n$, $(1+a)^n \\geqslant 1+na$.\r\n<li> En déduire que $\\limn q^n = + \\infty$ pour $q > 1$.\r\n</ol>', NULL, NULL, '2024-04-07 06:51:32', '2024-04-12 11:27:00', 'Soit $a \\in \\Rpe$.\r\n\\enmb \\item Montrer par récurrence que pour tout entier naturel $n$, $(1+a)^n \\geqslant 1+na$.\r\n\\item En déduire que $\\limn q^n = + \\infty$ pour $q > 1$.\r\n\\fenmb', NULL, NULL),
(149, 17, NULL, 'Soit $a \\in \\R^+$.\r\n<ol class=\'enumb\'>  <li> Prouver l\'inégalité $\\forall n \\in \\N^*, (1+a)^n \\geqslant 1+na+\\Frac{n(n-1)}{2}a^2$.\r\n<li> On considère la suite $\\un$ définie sur $\\N^*$ par $u_n = \\Frac{3n}{3^n}$. <br>\r\nMontrer que, pour tout entier $n$ non nul, $0 < u_n \\leqslant \\Frac{3n}{2n^2+1}$.\r\n<li> En déduire $\\limn u_n$.\r\n</ol>', '', '', '2024-04-07 06:51:38', '2024-04-12 11:27:00', 'Soit $a \\in \\R^+$.\r\n\\enmb  \\item Prouver l\'inégalité $\\forall n \\in \\N^*, (1+a)^n \\geqslant 1+na+\\Frac{n(n-1)}{2}a^2$.\r\n\\item On considère la suite $\\un$ définie sur $\\N^*$ par $u_n = \\Frac{3n}{3^n}$. \\\\\r\nMontrer que, pour tout entier $n$ non nul, $0 < u_n \\leqslant \\Frac{3n}{2n^2+1}$.\r\n\\item En déduire $\\limn u_n$.\r\n\\fenmb', NULL, NULL),
(150, 17, NULL, 'On souhaite démontrer que $0,999\\hdots = 1$. <br>\r\nLe nombre $0,999\\hdots$ comporte des $9$ à l\'infini. <br>\r\nOn note $x = 0,999\\hdots$\r\n<ol class=\'enumb\'> <li> Expliquer pourquoi $x$ est la limite lorsque $n$ tend vers $+\\infty$ de la suite $(S_n)$ définie par \\[ S_n = \\sum_{k=1}^{n} \\Frac{9}{10^{k}} \\]\r\n<li> Déterminer l\'expression de $S_n$ en fonction de $n$.\r\n<li> Calculer $\\limn S_n$ puis conclure.\r\n</ol>', '', '', '2024-04-07 06:51:45', '2024-04-12 11:27:00', 'On souhaite démontrer que $0,999\\hdots = 1$. \\\\\r\nLe nombre $0,999\\hdots$ comporte des $9$ à l\'infini. \\\\\r\nOn note $x = 0,999\\hdots$\r\n\\enmb \\item Expliquer pourquoi $x$ est la limite lorsque $n$ tend vers $+\\infty$ de la suite $(S_n)$ définie par \\[ S_n = \\sum_{k=1}^{n} \\Frac{9}{10^{k}} \\]\r\n\\item Déterminer l\'expression de $S_n$ en fonction de $n$.\r\n\\item Calculer $\\limn S_n$ puis conclure.\r\n\\fenmb', NULL, NULL),
(151, 18, NULL, 'Dans chaque cas, déterminer $\\limn u_n$\r\n<ul class=\'point\'> <li> $u_n = n^2+2n+5$\r\n<li> $u_n = n^2-n$\r\n<li> $u_n = n-n^4$\r\n<li> $u_n=\\sqrt{n^2-1}$\r\n<li> $u_n = e^n$.\r\n<li> $u_n = \\sqrt{e^n}+n+1$.\r\n<li> $u_n = -4n^2+1$.\r\n</ul>', NULL, NULL, '2024-04-09 17:58:06', '2024-04-12 11:36:27', 'Dans chaque cas, déterminer $\\limn u_n$\r\n\\itm \\item $u_n = n^2+2n+5$\r\n\\item $u_n = n^2-n$\r\n\\item $u_n = n-n^4$\r\n\\item $u_n=\\sqrt{n^2-1}$\r\n\\item $u_n = e^n$.\r\n\\item $u_n = \\sqrt{e^n}+n+1$.\r\n\\item $u_n = -4n^2+1$.\r\n\\fitm', NULL, NULL),
(152, 18, NULL, 'Dans chaque cas, déterminer $\\limn u_n$\r\n<ul class=\'point\'> <li> $u_n = \\Frac{n^2+2}{n-4}$\r\n<li> $u_n = \\Frac{n^3+n+1}{10-n^6}$\r\n<li> $u_n = \\Frac{-n^4+n+3}{1+n^4}$\r\n<li> $u_n = \\Frac{2n^2+5}{1-3n^2}$\r\n<li> $u_n = \\Frac{\\sqrt{n}}{n}$\r\n<li> $u_n = \\Frac{\\sqrt{n+1}}{\\sqrt{4n+4}}$\r\n</ul>', NULL, NULL, '2024-04-09 17:58:14', '2024-04-12 11:36:27', 'Dans chaque cas, déterminer $\\limn u_n$\r\n\\itm \\item $u_n = \\Frac{n^2+2}{n-4}$\r\n\\item $u_n = \\Frac{n^3+n+1}{10-n^6}$\r\n\\item $u_n = \\Frac{-n^4+n+3}{1+n^4}$\r\n\\item $u_n = \\Frac{2n^2+5}{1-3n^2}$\r\n\\item $u_n = \\Frac{\\sqrt{n}}{n}$\r\n\\item $u_n = \\Frac{\\sqrt{n+1}}{\\sqrt{4n+4}}$\r\n\\fitm', NULL, NULL),
(153, 18, NULL, 'Déterminer la limite des suites suivantes : \r\n<ul>\r\n<li> $u_n = \\displaystyle \\frac{n^2 - 1}{n+1}$ \r\n<li> $u_n = \\displaystyle \\frac{ 1-n^3}{n - 5n^4}$ \r\n<li> $u_n = \\displaystyle \\frac{ 2n^4-1}{n^2 + 5n^4}$ \r\n</ul>', NULL, NULL, '2024-04-09 17:58:21', '2024-04-12 11:36:27', 'Déterminer la limite des suites suivantes : \r\n\\begin{itemize}\r\n\\item $u_n = \\displaystyle \\frac{n^2 - 1}{n+1}$ \r\n\\item $u_n = \\displaystyle \\frac{ 1-n^3}{n - 5n^4}$ \r\n\\item $u_n = \\displaystyle \\frac{ 2n^4-1}{n^2 + 5n^4}$ \r\n\\end{itemize}', NULL, NULL),
(154, 18, NULL, 'Déterminer les limites en $+ \\infty$ de \r\n<ul>\r\n<li> $u_n = \\displaystyle \\frac{6n^2 - 3n + 7}{n^2 + n + 1}$ \r\n<li> $u_n = \\displaystyle \\sqrt { \\frac{ 3n^2-1}{5n+4} }$ \r\n<li>  $u_n = n^2 \\displaystyle \\left( \\sqrt { 3 - \\frac {2}{n} } - \\sqrt{3} \\right) $ \r\n<li> $u_n = \\displaystyle \\frac{ 3n - \\sqrt{9n^2 +1} }{ \\sqrt{n^2+5} }$\r\n</ul>', NULL, NULL, '2024-04-09 17:58:27', '2024-04-12 11:36:28', 'Déterminer les limites en $+ \\infty$ de \r\n\\begin{itemize}\r\n\\item $u_n = \\displaystyle \\frac{6n^2 - 3n + 7}{n^2 + n + 1}$ \r\n\\item $u_n = \\displaystyle \\sqrt { \\frac{ 3n^2-1}{5n+4} }$ \r\n\\item  $u_n = n^2 \\displaystyle \\left( \\sqrt { 3 - \\frac {2}{n} } - \\sqrt{3} \\right) $ \r\n\\item $u_n = \\displaystyle \\frac{ 3n - \\sqrt{9n^2 +1} }{ \\sqrt{n^2+5} }$\r\n\\end{itemize}', NULL, NULL),
(155, 18, NULL, 'Déterminer les limites en $+\\infty$ de \r\n<ul class=\'point\'> \r\n<li> $u_n = \\Frac{e^n}{n}$.\r\n<li> $u_n = -ne^{-n}$\r\n<li> $u_n = \\Frac{n^5}{e^n}$\r\n<li> $u_n = n!$\r\n<li> $u_n = n^n$\r\n<li> $u_n = 2^{-n}$\r\n<li> $u_n = e^{-2n+1}$\r\n</ul>', NULL, NULL, '2024-04-09 17:58:35', '2024-04-12 11:36:28', 'Déterminer les limites en $+\\infty$ de \r\n\\itm \r\n\\item $u_n = \\Frac{e^n}{n}$.\r\n\\item $u_n = -ne^{-n}$\r\n\\item $u_n = \\Frac{n^5}{e^n}$\r\n\\item $u_n = n!$\r\n\\item $u_n = n^n$\r\n\\item $u_n = 2^{-n}$\r\n\\item $u_n = e^{-2n+1}$\r\n\\fitm', NULL, NULL),
(156, 18, NULL, '<ol class=\'enumb\'> <li> Déterminer la limite de la suite $\\un$ définie par $u_n = \\Frac{\\sqrt{n}}{3n}$.\r\n<li> Soit $a \\in \\R$. Discuter selon les valeurs de $a$ de la limite de $u_n = \\Frac{\\sqrt{n}}{an}$.\r\n</ol>', NULL, NULL, '2024-04-09 17:58:43', '2024-04-12 11:36:28', '\\enmb \\item Déterminer la limite de la suite $\\un$ définie par $u_n = \\Frac{\\sqrt{n}}{3n}$.\r\n\\item Soit $a \\in \\R$. Discuter selon les valeurs de $a$ de la limite de $u_n = \\Frac{\\sqrt{n}}{an}$.\r\n\\fenmb', NULL, NULL),
(157, 19, NULL, 'Soit $\\un$ définie pour tout $n\\geqslant 1$ par $u_n = 1 + \\Frac{1}{\\sqrt{2}}+\\Frac{1}{\\sqrt{3}} + \\hdots + \\Frac{1}{\\sqrt{n}}$.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout $n \\geqslant 1$, $u_n \\geqslant \\sqrt{n}$.\r\n<li> En déduire $\\limn u_n$.\r\n</ol>', NULL, NULL, '2024-04-09 17:59:27', '2024-04-12 11:36:28', 'Soit $\\un$ définie pour tout $n\\geqslant 1$ par $u_n = 1 + \\Frac{1}{\\sqrt{2}}+\\Frac{1}{\\sqrt{3}} + \\hdots + \\Frac{1}{\\sqrt{n}}$.\r\n\\enmb \\item Montrer que pour tout $n \\geqslant 1$, $u_n \\geqslant \\sqrt{n}$.\r\n\\item En déduire $\\limn u_n$.\r\n\\fenmb', NULL, NULL),
(158, 19, NULL, 'Soit $\\un$ définie par $u_0 = 0$ et pour tout entier naturel $n$, $u_{n+1} = 3u_n-2n+3$. \r\n<ol class=\'enumb\'> <li> Démontrer par récurrence que, pour tout entier naturel $n$, $u_n \\geqslant n$. \r\n<li> En déduire la limite de la suite $\\un$. \r\n</ol>', NULL, NULL, '2024-04-09 17:59:34', '2024-04-12 11:36:28', 'Soit $\\un$ définie par $u_0 = 0$ et pour tout entier naturel $n$, $u_{n+1} = 3u_n-2n+3$. \r\n\\enmb \\item Démontrer par récurrence que, pour tout entier naturel $n$, $u_n \\geqslant n$. \r\n\\item En déduire la limite de la suite $\\un$. \r\n\\fenmb', NULL, NULL),
(159, 19, NULL, 'Soit $\\vn$ définie par $v_0 = 0$ et pour tout entier $n$, \\[v_{n+1} = v_n^2 + 1\\]\r\n<ol class=\'enumb\'> <li> Démontrer que, pour tout $x \\in \\R$, $x^2+1 \\geqslant 2x$. <br>\r\nEn déduire par récurrence que si $n\\geqslant 4$, alors $v_n \\geqslant 2^n$. \r\n<li> En déduire la limite de $\\vn$. \r\n</ol>', '', '', '2024-04-09 17:59:41', '2024-04-12 11:36:28', 'Soit $\\vn$ définie par $v_0 = 0$ et pour tout entier $n$, \\[v_{n+1} = v_n^2 + 1\\]\r\n\\enmb \\item Démontrer que, pour tout $x \\in \\R$, $x^2+1 \\geqslant 2x$. \\\\\r\nEn déduire par récurrence que si $n\\geqslant 4$, alors $v_n \\geqslant 2^n$. \r\n\\item En déduire la limite de $\\vn$. \r\n\\fenmb', NULL, NULL),
(160, 19, NULL, 'Déterminer $\\limn n^2+\\Frac{\\sin{n^2}}{2}$.', NULL, NULL, '2024-04-09 17:59:47', '2024-04-12 11:36:28', 'Déterminer $\\limn n^2+\\Frac{\\sin{n^2}}{2}$.', NULL, NULL),
(161, 19, NULL, 'La suite $\\un$ vérifie pour tout entier naturel $n \\geqslant 20$, \\[ -2 - \\Frac{1}{n} \\leqslant u_n \\leqslant -2 + \\Frac{3}{n} \\]\r\nDémontrer que $\\un$ est convergente et préciser sa limite.', NULL, NULL, '2024-04-09 17:59:53', '2024-04-12 11:36:28', 'La suite $\\un$ vérifie pour tout entier naturel $n \\geqslant 20$, \\[ -2 - \\Frac{1}{n} \\leqslant u_n \\leqslant -2 + \\Frac{3}{n} \\]\r\nDémontrer que $\\un$ est convergente et préciser sa limite.', NULL, NULL),
(162, 19, NULL, 'On considère une suite $(w_n)$ qui vérifie, $\\forall n \\in \\N$, \\[  n^2 \\leqslant (n+1)^2w_n \\leqslant n^2+n \\]\r\nDire si la proposition est vraie ou fausse en justifiant : <br>\r\n<span class=\'textbf\'>Proposition</span> : La suite $\\wn$ converge.', '', '', '2024-04-09 18:00:00', '2024-04-12 11:36:28', 'On considère une suite $(w_n)$ qui vérifie, $\\forall n \\in \\N$, \\[  n^2 \\leqslant (n+1)^2w_n \\leqslant n^2+n \\]\r\nDire si la proposition est vraie ou fausse en justifiant : \\\\\r\n\\textbf{Proposition} : La suite $\\wn$ converge.', NULL, NULL),
(163, 19, NULL, 'Déterminer la limite de la suite $\\un$ définie par \\[u_n = \\Frac{\\sin{n^2}}{n}\\]', NULL, NULL, '2024-04-09 18:00:06', '2024-04-12 11:36:28', 'Déterminer la limite de la suite $\\un$ définie par \\[u_n = \\Frac{\\sin{n^2}}{n}\\]', NULL, NULL),
(164, 19, NULL, 'Déterminer la limite des suites suivantes : \r\n<ul>\r\n<li> $u_n = \\displaystyle \\frac{n^2 - \\sin{n}}{n+1}$ \r\n<li> $u_n = n^2 - (-1)^n$ \r\n<li> $u_n = \\displaystyle \\frac{ \\sin{n^2}}{n}$ \r\n</ul>', NULL, NULL, '2024-04-09 18:00:12', '2024-04-12 11:36:28', 'Déterminer la limite des suites suivantes : \r\n\\begin{itemize}\r\n\\item $u_n = \\displaystyle \\frac{n^2 - \\sin{n}}{n+1}$ \r\n\\item $u_n = n^2 - (-1)^n$ \r\n\\item $u_n = \\displaystyle \\frac{ \\sin{n^2}}{n}$ \r\n\\end{itemize}', NULL, NULL),
(165, 19, NULL, 'Soit la suite $(u_n)$ définie sur $\\N^*$ par \\[u_n = \\Frac{1}{n+\\sqrt{1}} + \\Frac{1}{n+\\sqrt{2}} + \\hdots + \\Frac{1}{n+\\sqrt{n}}\\]\r\n<ol class=\'enumb\'>\r\n<li> Montrer que pour tout $n \\in \\N^*$, $ \\Frac{n}{n+\\sqrt{n}} \\leqslant u_n \\leqslant \\Frac{n}{n+1}$. \r\n<li> En déduire que la suite converge et calculer sa limite. \r\n</ol>', NULL, NULL, '2024-04-09 18:00:19', '2024-04-12 11:36:28', 'Soit la suite $(u_n)$ définie sur $\\N^*$ par \\[u_n = \\Frac{1}{n+\\sqrt{1}} + \\Frac{1}{n+\\sqrt{2}} + \\hdots + \\Frac{1}{n+\\sqrt{n}}\\]\r\n\\enmb\r\n\\item Montrer que pour tout $n \\in \\N^*$, $ \\Frac{n}{n+\\sqrt{n}} \\leqslant u_n \\leqslant \\Frac{n}{n+1}$. \r\n\\item En déduire que la suite converge et calculer sa limite. \r\n\\fenmb', NULL, NULL),
(166, 19, NULL, 'On considère la suite $\\un$ définie pour tout $n \\geqslant 1$ par $u_n = \\sqrt{n+1}-\\sqrt{n}$.\r\n<ol class=\'enumb\'> <li> Montrer que $\\Frac{1}{2\\sqrt{n+1}} \\leqslant u_n \\leqslant \\Frac{1}{2\\sqrt{n}}$.\r\n<li> En déduire la limite de $\\un$.\r\n<li> Retrouver ce résultat en utilisant le conjugué.\r\n</ol>', NULL, NULL, '2024-04-09 18:00:25', '2024-04-12 11:36:28', 'On considère la suite $\\un$ définie pour tout $n \\geqslant 1$ par $u_n = \\sqrt{n+1}-\\sqrt{n}$.\r\n\\enmb \\item Montrer que $\\Frac{1}{2\\sqrt{n+1}} \\leqslant u_n \\leqslant \\Frac{1}{2\\sqrt{n}}$.\r\n\\item En déduire la limite de $\\un$.\r\n\\item Retrouver ce résultat en utilisant le conjugué.\r\n\\fenmb', NULL, NULL),
(167, 19, NULL, 'Pour $n \\in \\N$, on pose $u_n = \\binom{2n}{n}$.\r\n<ol class=\'enumb\'> <li> Pour $n \\in \\N$, simplifier le quotient $\\Frac{u_{n+1}}{u_n}$. En déduire que $u_{n+1} \\geqslant 2u_n$.\r\n<li> Montrer par récurrence que $u_{n+1} \\geqslant 2^n$ pour $n \\in \\N$.\r\n<li> En déduire $\\limn u_n$.\r\n</ol>', NULL, NULL, '2024-04-09 18:00:31', '2024-04-12 11:36:28', 'Pour $n \\in \\N$, on pose $u_n = \\binom{2n}{n}$.\r\n\\enmb \\item Pour $n \\in \\N$, simplifier le quotient $\\Frac{u_{n+1}}{u_n}$. En déduire que $u_{n+1} \\geqslant 2u_n$.\r\n\\item Montrer par récurrence que $u_{n+1} \\geqslant 2^n$ pour $n \\in \\N$.\r\n\\item En déduire $\\limn u_n$.\r\n\\fenmb', NULL, NULL),
(168, 19, NULL, 'Soient $\\un$ et $\\vn$ deux suites à valeurs dans $[0,1]$ telles que $\\limn u_nv_n = 1$. <br>\r\nMontrer que $\\limn u_n = \\limn v_n = 1$.', '', '', '2024-04-09 18:00:38', '2024-04-12 11:36:28', 'Soient $\\un$ et $\\vn$ deux suites à valeurs dans $[0,1]$ telles que $\\limn u_nv_n = 1$. \\\\\r\nMontrer que $\\limn u_n = \\limn v_n = 1$.', NULL, NULL),
(169, 19, NULL, 'Soient $\\un$ et $\\vn$ deux suites telles que $\\forall n \\in \\N$, $u_n \\geqslant v_n$. <br>\r\nEst-il vrai que si $\\vn$ converge alors $\\un$ converge ? Et si on ajoute l\'hypothèse que $\\un$ croissante ?', '', '', '2024-04-09 18:00:44', '2024-04-12 11:36:28', 'Soient $\\un$ et $\\vn$ deux suites telles que $\\forall n \\in \\N$, $u_n \\geqslant v_n$. \\\\\r\nEst-il vrai que si $\\vn$ converge alors $\\un$ converge ? Et si on ajoute l\'hypothèse que $\\un$ croissante ?', NULL, NULL),
(170, 19, NULL, 'Soient $\\un$ et $\\vn$ deux suites, et soient $a$ et $b$ deux réels tels que $\\forall n \\in \\N$, $u_n \\leqslant a$ et $v_n \\leqslant b$. <br>\r\nMontrer que si $\\limn (u_n+v_n) = a+b$, alors $\\limn u_n = a$ et $\\limn v_n = b$.', '', '', '2024-04-09 18:00:51', '2024-04-12 11:36:28', 'Soient $\\un$ et $\\vn$ deux suites, et soient $a$ et $b$ deux réels tels que $\\forall n \\in \\N$, $u_n \\leqslant a$ et $v_n \\leqslant b$. \\\\\r\nMontrer que si $\\limn (u_n+v_n) = a+b$, alors $\\limn u_n = a$ et $\\limn v_n = b$.', NULL, NULL),
(171, 19, NULL, 'Soit $\\un$ la suite définie sur $\\N$ par $u_0 \\in \\R^+$ et $u_{n+1} = u_n + \\sqrt{n+1} + \\sin{u_n}$.\r\n<ol class=\'enumb\'> <li> Déterminer les variations de $\\un$ puis en déduire que $\\un$ est à valeurs dans $\\R^+$.\r\n<li> En déduire la limite de $\\un$.\r\n</ol>', NULL, NULL, '2024-04-09 18:16:51', '2024-04-12 11:36:28', 'Soit $\\un$ la suite définie sur $\\N$ par $u_0 \\in \\R^+$ et $u_{n+1} = u_n + \\sqrt{n+1} + \\sin{u_n}$.\r\n\\enmb \\item Déterminer les variations de $\\un$ puis en déduire que $\\un$ est à valeurs dans $\\R^+$.\r\n\\item En déduire la limite de $\\un$.\r\n\\fenmb', NULL, NULL),
(172, 19, NULL, 'Soit $\\un_{n \\geqslant 1}$ définie par $u_{n} = \\Frac{1}{n^2}\\displaystyle \\sum_{k=1}^{n}\\left\\lfloor \\Frac{k^2}{n} \\right\\rfloor$. <br>\r\nEn utilisant un encadrement, déterminer $\\limn u_n$.', '', '', '2024-04-09 18:17:00', '2024-04-12 11:36:28', 'Soit $\\un_{n \\geqslant 1}$ définie par $u_{n} = \\Frac{1}{n^2}\\displaystyle \\sum_{k=1}^{n}\\left\\lfloor \\Frac{k^2}{n} \\right\\rfloor$. \\\\\r\nEn utilisant un encadrement, déterminer $\\limn u_n$.', NULL, NULL);
INSERT INTO `exercises` (`id`, `subchapter_id`, `name`, `statement`, `solution`, `clue`, `created_at`, `updated_at`, `latex_statement`, `latex_solution`, `latex_clue`) VALUES
(173, 16, NULL, '<span class=\'textit\'>Bac 2022</span>\r\nOn considère la fonction $f$ définie sur $\\R$ par \\[ f(x) = x^3e^{x} \\]\r\nOn admet que $f$ est dérivable sur $\\R$ et on note $f\'$ sa dérivée.\r\n<ol class=\'enumb\'> <li> On définit la suite $\\un$ par $u_0 = -1$ et pour tout entier naturel $n$, \\[ u_{n+1} = f(u_n) \\]\r\n<ol class=\'enumb\'> <li> Calculer $u_1$ et $u_2$. \\\\\r\nOn donnera les valeurs exactes, puis les valeurs approchées à $10^{-3}$.\r\n<li> On considère la fonction <span class=\'texttt\'>fonc</span> écrite en langage Python ci-dessous :\r\n<div class=\'latex-center\'>\r\n<div class=\'latex-minipage\'><style=\'width: calc(0.40% - 2em);\'> </style>\r\n\r\n<span class=\'latex latex-tabularx\' style=\'width: \\linewidth%;\'>${|X|}$ <hr>\r\n<span class=\'texttt\'>def $fonc(n)$ :</span>\\\\\r\n\\qquad <span class=\'texttt\'>u=-1</span>\\\\\r\n\\qquad <span class=\'texttt\'>for i in range(n) :</span>\\\\\r\n\\qquad \\qquad <span class=\'texttt\'>u=u**3*exp(u)</span> \\\\\r\n\\qquad <span class=\'texttt\'>return u</span> \\\\ <hr>\r\n</span>\r\n</div>\r\n </div>\r\nOn rappelle qu\'en Python, \"<span class=\'texttt\'>i in range (n)</span>\" signifie que <span class=\'texttt\'>i</span> varie de <span class=\'texttt\'>0</span> à <span class=\'texttt\'>n-1</span>. \\\\\r\nDéterminer, sans justifier, la valeur renvoyée par <span class=\'texttt\'>fonc(2)</span> arrondie à $10^{-3}$ près.\r\n</ol>\r\n<li> <ol class=\'enumb\'> <li> Démontrer que pour tout réel $x$, on a \\[ f\'(x) = x^2e^{x}(x+3) \\]\r\n<li> Déterminer le tableau de variations de $f$ sur $\\R$.\r\n<li> Montrer par récurrence que pour tout entier naturel $n$, on a \\[ -1 \\leqslant u_n \\leqslant u_{n+1} \\leqslant 0 \\]\r\n<li> En déduire que la suite $\\un$ est convergente.\r\n<li> On note $\\ell$ la limite de la suite $\\un$. \\\\\r\nOn rappelle que $\\ell$ est solution de l\'équation $f(x)=x$. \\\\\r\nDéterminer $\\ell$. (Pour cela, on admettra que l\'équation $x^2e^x-1=0$ possède une unique solution dans $\\R$ et que celle-ci est strictement supérieure à $\\Frac{1}{2}$.)\r\n</ol>\r\n</ol>', '<span class=\'textit\'>Bac 2022</span>\r\nOn considère la fonction $f$ définie sur $\\R$ par \\[ f(x) = x^3e^{x} \\]\r\nOn admet que $f$ est dérivable sur $\\R$ et on note $f\'$ sa dérivée.\r\n<ol class=\'enumb\'> <li> On définit la suite $\\un$ par $u_0 = -1$ et pour tout entier naturel $n$, \\[ u_{n+1} = f(u_n) \\]\r\n<ol class=\'enumb\'> <li> Calculer $u_1$ et $u_2$. \\\\\r\nOn donnera les valeurs exactes, puis les valeurs approchées à $10^{-3}$.\r\n<li> On considère la fonction <span class=\'texttt\'>fonc</span> écrite en langage Python ci-dessous :\r\n<div class=\'latex-center\'>\r\n<div class=\'latex-minipage\'><style=\'width: calc(0.40% - 2em);\'> </style>\r\n\r\n<span class=\'latex latex-tabularx\' style=\'width: \\linewidth%;\'>{|X|} <hr>\r\n<span class=\'texttt\'>def fonc(n) :</span>\\\\\r\n\\qquad <span class=\'texttt\'>u=-1</span>\\\\\r\n\\qquad <span class=\'texttt\'>for i in range(n) :</span>\\\\\r\n\\qquad \\qquad <span class=\'texttt\'>u=u**3*exp(u)</span> \\\\\r\n\\qquad <span class=\'texttt\'>return u</span> \\\\ <hr>\r\n</span>\r\n</div>\r\n </div>\r\nOn rappelle qu\'en Python, \"<span class=\'texttt\'>i in range (n)</span>\" signifie que <span class=\'texttt\'>i</span> varie de <span class=\'texttt\'>0</span> à <span class=\'texttt\'>n-1</span>. \\\\\r\nDéterminer, sans justifier, la valeur renvoyée par <span class=\'texttt\'>fonc(2)</span> arrondie à $10^{-3}$ près.\r\n</ol>\r\n<li> <ol class=\'enumb\'> <li> Démontrer que pour tout réel $x$, on a \\[ f\'(x) = x^2e^{x}(x+3) \\]\r\n<li> Déterminer le tableau de variations de $f$ sur $\\R$.\r\n<li> Montrer par récurrence que pour tout entier naturel $n$, on a \\[ -1 \\leqslant u_n \\leqslant u_{n+1} \\leqslant 0 \\]\r\n<li> En déduire que la suite $\\un$ est convergente.\r\n<li> On note $\\ell$ la limite de la suite $\\un$. \\\\\r\nOn rappelle que $\\ell$ est solution de l\'équation $f(x)=x$. \\\\\r\nDéterminer $\\ell$. (Pour cela, on admettra que l\'équation $x^2e^x-1=0$ possède une unique solution dans $\\R$ et que celle-ci est strictement supérieure à $\\Frac{1}{2}$.)\r\n</ol>\r\n</ol>', '', '2024-04-09 19:51:39', '2024-04-13 12:34:15', '\\textit{Bac 2022}\r\nOn considère la fonction $f$ définie sur $\\R$ par \\[ f(x) = x^3e^{x} \\]\r\nOn admet que $f$ est dérivable sur $\\R$ et on note $f\'$ sa dérivée.\r\n\\enmb \\item On définit la suite $\\un$ par $u_0 = -1$ et pour tout entier naturel $n$, \\[ u_{n+1} = f(u_n) \\]\r\n\\enmb \\item Calculer $u_1$ et $u_2$. \\\\\r\nOn donnera les valeurs exactes, puis les valeurs approchées à $10^{-3}$.\r\n\\item On considère la fonction \\texttt{fonc} écrite en langage Python ci-dessous :\r\n\\begin{center}\r\n\\begin{minipage}{0.40\\linewidth}\r\n\\renewcommand\\arraystretch{0.9}\r\n\\begin{tabularx}{\\linewidth}${|X|}$ \\hline\r\n\\texttt{def $fonc(n)$ :}\\\\\r\n\\qquad \\texttt{u=-1}\\\\\r\n\\qquad \\texttt{for i in range(n) :}\\\\\r\n\\qquad \\qquad \\texttt{u=u**3*exp(u)} \\\\\r\n\\qquad \\texttt{return u} \\\\ \\hline\r\n\\end{tabularx}\r\n\\end{minipage}\r\n\\end{center}\r\nOn rappelle qu\'en Python, \"\\texttt{i in range (n)}\" signifie que \\texttt{i} varie de \\texttt{0} à \\texttt{n-1}. \\\\\r\nDéterminer, sans justifier, la valeur renvoyée par \\texttt{fonc(2)} arrondie à $10^{-3}$ près.\r\n\\fenmb\r\n\\item \\enmb \\item Démontrer que pour tout réel $x$, on a \\[ f\'(x) = x^2e^{x}(x+3) \\]\r\n\\item Déterminer le tableau de variations de $f$ sur $\\R$.\r\n\\item Montrer par récurrence que pour tout entier naturel $n$, on a \\[ -1 \\leqslant u_n \\leqslant u_{n+1} \\leqslant 0 \\]\r\n\\item En déduire que la suite $\\un$ est convergente.\r\n\\item On note $\\ell$ la limite de la suite $\\un$. \\\\\r\nOn rappelle que $\\ell$ est solution de l\'équation $f(x)=x$. \\\\\r\nDéterminer $\\ell$. (Pour cela, on admettra que l\'équation $x^2e^x-1=0$ possède une unique solution dans $\\R$ et que celle-ci est strictement supérieure à $\\Frac{1}{2}$.)\r\n\\fenmb\r\n\\fenmb', '\\textit{Bac 2022}\r\nOn considère la fonction $f$ définie sur $\\R$ par \\[ f(x) = x^3e^{x} \\]\r\nOn admet que $f$ est dérivable sur $\\R$ et on note $f\'$ sa dérivée.\r\n\\enmb \\item On définit la suite $\\un$ par $u_0 = -1$ et pour tout entier naturel $n$, \\[ u_{n+1} = f(u_n) \\]\r\n\\enmb \\item Calculer $u_1$ et $u_2$. \\\\\r\nOn donnera les valeurs exactes, puis les valeurs approchées à $10^{-3}$.\r\n\\item On considère la fonction \\texttt{fonc} écrite en langage Python ci-dessous :\r\n\\begin{center}\r\n\\begin{minipage}{0.40\\linewidth}\r\n\\renewcommand\\arraystretch{0.9}\r\n\\begin{tabularx}{\\linewidth}{|X|} \\hline\r\n\\texttt{def fonc(n) :}\\\\\r\n\\qquad \\texttt{u=-1}\\\\\r\n\\qquad \\texttt{for i in range(n) :}\\\\\r\n\\qquad \\qquad \\texttt{u=u**3*exp(u)} \\\\\r\n\\qquad \\texttt{return u} \\\\ \\hline\r\n\\end{tabularx}\r\n\\end{minipage}\r\n\\end{center}\r\nOn rappelle qu\'en Python, \"\\texttt{i in range (n)}\" signifie que \\texttt{i} varie de \\texttt{0} à \\texttt{n-1}. \\\\\r\nDéterminer, sans justifier, la valeur renvoyée par \\texttt{fonc(2)} arrondie à $10^{-3}$ près.\r\n\\fenmb\r\n\\item \\enmb \\item Démontrer que pour tout réel $x$, on a \\[ f\'(x) = x^2e^{x}(x+3) \\]\r\n\\item Déterminer le tableau de variations de $f$ sur $\\R$.\r\n\\item Montrer par récurrence que pour tout entier naturel $n$, on a \\[ -1 \\leqslant u_n \\leqslant u_{n+1} \\leqslant 0 \\]\r\n\\item En déduire que la suite $\\un$ est convergente.\r\n\\item On note $\\ell$ la limite de la suite $\\un$. \\\\\r\nOn rappelle que $\\ell$ est solution de l\'équation $f(x)=x$. \\\\\r\nDéterminer $\\ell$. (Pour cela, on admettra que l\'équation $x^2e^x-1=0$ possède une unique solution dans $\\R$ et que celle-ci est strictement supérieure à $\\Frac{1}{2}$.)\r\n\\fenmb\r\n\\fenmb', NULL),
(174, 16, 'dsd', 'On pose $\\mathcal{P}(n)$ la propriété : \"$u_n = 2^{n+1}-1$\". \\\\\r\n<ul class=\'point\'>\r\n<li> <span class=\'textbf\'>Initialisation</span> : pour $n=0$ : \\\\\r\n$u_0 = 1$ et \\\\\r\n$2^{0+1}-1 = 2^1-1 = 2-1 = 1$. \\\\\r\nOn a donc bien $u_0 = 2^{0+1}-1$, c\'est à dire <span class=\'latex latex-boxed\'> \\mathcal{P}(0) \\text{ est vraie.} </span> \\\\\r\n<li> <span class=\'textbf\'>Hérédité</span> : On suppose $\\mathcal{P}(n)$ vraie pour un $n \\in \\N$ fixé.\r\n\\begin{align*} u_{n+1} &= 2u_n+1 &\\text{par définition} \\\\ &= 2(2^{n+1}-1)+1 &\\text{ par H.R. } \\\\ &= 2\\times 2^{n+1} - 2 + 1 \\\\ &= 2^{n+2} -1 \\end{align*}\r\nOn vient de montrer que $u_{n+1} = 2^{(n+1)+1}-1$ ce qui prouve que <span class=\'latex latex-boxed\'> $\\mathcal{P}(n+1)$ est vraie. </span>\r\n</ul>\r\nLe principe de récurrence conclut. \\\\\r\nAinsi, <span class=\'latex latex-boxed\'> $\\forall n \\in \\N, u_n = 2^{n+1}-1$ </span>', '', '', '2024-04-12 11:29:05', '2024-04-13 11:51:22', 'On pose $\\mathcal{P}(n)$ la propriété : \"$u_n = 2^{n+1}-1$\". \\\\\r\n\\itm\r\n\\item \\textbf{Initialisation} : pour $n=0$ : \\\\\r\n$u_0 = 1$ et \\\\\r\n$2^{0+1}-1 = 2^1-1 = 2-1 = 1$. \\\\\r\nOn a donc bien $u_0 = 2^{0+1}-1$, c\'est à dire \\begin{boxed} \\mathcal{P}(0) \\text{ est vraie.} \\end{boxed} \\\\\r\n\\item \\textbf{Hérédité} : On suppose $\\mathcal{P}(n)$ vraie pour un $n \\in \\N$ fixé.\r\n\\begin{align*} u_{n+1} &= 2u_n+1 &\\text{par définition} \\\\ &= 2(2^{n+1}-1)+1 &\\text{ par H.R. } \\\\ &= 2\\times 2^{n+1} - 2 + 1 \\\\ &= 2^{n+2} -1 \\end{align*}\r\nOn vient de montrer que $u_{n+1} = 2^{(n+1)+1}-1$ ce qui prouve que \\begin{boxed} $\\mathcal{P}(n+1)$ est vraie. \\end{boxed}\r\n\\fitm\r\nLe principe de récurrence conclut. \\\\\r\nAinsi, \\begin{boxed} $\\forall n \\in \\N, u_n = 2^{n+1}-1$ \\end{boxed}', NULL, NULL),
(175, 16, NULL, '\\begin{document}\r\n\r\n%%% AVEC SOMMES%%%%\r\n\r\n<div class=\'latex latex-center\'>\r\n\\textsc{Sujet de ds} \\\\\r\n\\textsc{Mathématiques} \\\\\r\n\\textsc{Terminale Spécialité}\r\n</div>\r\n\\vspace{5pt}\r\n<div class=\'latex latex-center\'>\r\n\\textit{Ce sujet est une simulation rigoureuse de l\'examen du baccalauréat pour vous aider à maîtriser la gestion du temps et les exigences de l\'épreuve. Merci de traiter ce devoir avec sérieux, en respectant le temps imparti et en soignant votre présentation. N\'oubliez pas d\'espacer vos équations et d\'encadrer vos résultats. La calculatrice est autorisée. \\\\\r\nVeuillez envoyer votre copie à la fin pour correction.}\r\n</div>\r\n\r\n\r\n\\end{document}', NULL, NULL, '2024-04-12 18:29:30', '2024-04-12 18:29:30', '\\begin{document}\r\n\r\n%%% AVEC SOMMES%%%%\r\n\r\n\\begin{center}\r\n\\textsc{Sujet de ds} \\\\\r\n\\textsc{Mathématiques} \\\\\r\n\\textsc{Terminale Spécialité}\r\n\\end{center}\r\n\\vspace{5pt}\r\n\\begin{center}\r\n\\textit{Ce sujet est une simulation rigoureuse de l\'examen du baccalauréat pour vous aider à maîtriser la gestion du temps et les exigences de l\'épreuve. Merci de traiter ce devoir avec sérieux, en respectant le temps imparti et en soignant votre présentation. N\'oubliez pas d\'espacer vos équations et d\'encadrer vos résultats. La calculatrice est autorisée. \\\\\r\nVeuillez envoyer votre copie à la fin pour correction.}\r\n\\end{center}\r\n\r\n\r\n\\end{document}', NULL, NULL),
(176, 16, NULL, '\\boxed{\\pi=\\frac c d}', NULL, NULL, '2024-04-12 23:58:59', '2024-04-12 23:58:59', '\\boxed{\\pi=\\frac c d}', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2024_03_14_180715_create_classes_table', 1),
(4, '2024_03_14_180723_create_chapters_table', 1),
(5, '2024_03_14_180731_create_subchapters_table', 1),
(6, '2024_03_15_223220_add_role_to_users_table', 1),
(7, '2024_03_15_223310_add_role_to_users_table', 1),
(8, '2024_03_20_034838_create_exercises_table', 1),
(9, '2024_03_25_154641_add_theme_to_chapitres_table', 2),
(10, '2024_03_20_034838_create_exercises_tableupdate', 3),
(11, '2024_04_04_200225_create_quizzes_table', 3),
(12, '2024_04_05_030402_rename_column_in_exercises', 3),
(13, '2024_04_05_035715_change_column_to_text_in_exercises_table', 3),
(14, '2024_04_10_172142_add_verified_to_users_table', 4),
(15, '2024_04_10_185149_create_multiple_chapters_table', 5),
(16, '2024_04_10_185332_create_ds_exercises_table', 6),
(17, '2024_04_10_204240_drop_chapters_id_column_from_eds_table', 7),
(18, '2024_04_10_204529_create_chapters_exercises_ds_table', 8),
(19, '2024_04_10_223124_modify_ds_exercises_table', 9),
(20, '2024_04_11_020844_add_cascade_to_ds_exercises', 10),
(21, '2024_04_11_134351_create_ds_table', 11),
(22, '2024_04_11_163149_create_ds_exercises_ds_table', 12),
(25, '2024_04_11_175735_add_user_id_to_ds_table', 13),
(26, '2024_04_11_175735_add_user_id_to_ds_table2', 14),
(27, '2024_04_11_175735_add_user_id_to_ds_table3', 15),
(28, '2024_04_11_175735_add_user_id_to_ds_table4', 16),
(29, '2024_04_11_175735_add_user_id_to_ds_table5', 17),
(30, '2024_04_13_032823_modify_ds_table_status_enum', 18),
(31, '2024_04_13_033648_remodify_ds_table_status_enum', 19);

-- --------------------------------------------------------

--
-- Structure de la table `multiple_chapters`
--

DROP TABLE IF EXISTS `multiple_chapters`;
CREATE TABLE `multiple_chapters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `multiple_chapters`
--

INSERT INTO `multiple_chapters` (`id`, `title`, `description`, `theme`, `created_at`, `updated_at`) VALUES
(1, 'Dérivation', NULL, '#87CBEA', NULL, '2024-04-11 20:13:16'),
(2, 'Limites de fonctions', NULL, '#87CBEA', NULL, '2024-04-15 19:45:18'),
(9, 'Convexité', NULL, '#87CBEA', '2024-04-11 20:13:50', '2024-04-11 20:13:50'),
(10, 'Limites + Convexité', NULL, '#87CBEA', '2024-04-11 20:14:02', '2024-04-15 19:46:01'),
(11, 'Limites + Continuité', NULL, '#87CBEA', '2024-04-11 20:14:24', '2024-04-15 19:46:07'),
(12, 'Limites + Continuité + Convexité', NULL, '#87CBEA', '2024-04-11 20:14:50', '2024-04-15 19:46:51'),
(13, 'Suites', NULL, '#7CE6D0', '2024-04-11 20:15:01', '2024-04-11 20:15:01'),
(14, 'Suites + Limites de suites', NULL, '#7CE6D0', '2024-04-11 20:15:16', '2024-04-15 19:46:25'),
(15, 'Suites + Limites + Continuité', NULL, '#87CBEA', '2024-04-11 20:15:42', '2024-04-15 19:46:43'),
(16, 'Logarithme', NULL, '#6CAAEE', '2024-04-11 20:16:05', '2024-04-11 20:16:05'),
(17, 'Intégrales', NULL, '#6CAAEE', '2024-04-11 20:16:15', '2024-04-11 20:16:15'),
(19, 'Probabilités conditionnelles', NULL, '#E67C7C', '2024-04-15 19:47:28', '2024-04-15 19:47:28'),
(20, 'Probas + Loi binomiale', NULL, '#E67C7C', '2024-04-15 19:47:40', '2024-04-15 19:47:40'),
(21, 'Vecteurs, droites et plans', NULL, '#E6D07C', '2024-04-15 19:48:36', '2024-04-15 19:48:36'),
(22, 'Vecteurs + Orthogonalité (produit scalaire)', NULL, '#E6D07C', '2024-04-15 19:48:55', '2024-04-15 19:48:55'),
(23, 'Equations différentielles', NULL, '#6CAAEE', '2024-04-15 19:49:19', '2024-04-15 19:49:19'),
(24, 'Probas + Variables aléatoires', NULL, '#E67C7C', '2024-04-15 19:49:46', '2024-04-15 19:49:46');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('04imLT8dbdxuWILMXEBC8lk1CJb5k7GESVnx4fRk', NULL, '147.78.103.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQWh2M2JwWjdMbEM2R0dhVmtoUDRNcGJ2OE5FQVV3YkU5WjZiQk5YOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713218150),
('1cOogOvCUAUmiuC1yjPL3cZyF1K3EO0OZ2JeYMBs', NULL, '89.248.171.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFRKSGtSczBpTXNBTU1jMFdTZ0YzQWpXeTN4ZG1LVXVOcEx4Y3k4QyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713218991),
('3TLSQX3gUvFFhqY1q3Tw0hnXYdN4DoddwO8xh78Q', NULL, '2001:41d0:8:d154::5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTh0TTNqMHg1bnVjeXNpTEh3eFhEdFdVYlpkOElKVmVLeDVITkxwTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713218661),
('5RUNF7o9A3ZGzwLDAKUI4GC8alsPNzPn3CfiX4k9', NULL, '51.38.135.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWhZdnNycnl2TGlHVWI3akNtY2JWUEpZblRlQ2UwQ1dpRFBXV3U3NCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9tYXRoc21hbmFnZXIuZnIvY2xhc3NlL21hdGhzRXhwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713216890),
('97uNyDzHmKhmHBcR01rUCrwOAODUZ9YGA60wEXa6', 27, '77.140.54.227', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREJYRDFBTFBjUE01WFNlMUhFZnJ2SmhOcW1mZkZ4dFp4bUwwSW9PRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyL2NsYXNzZS90ZXJtU3BlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjc7fQ==', 1713218063),
('9HGybryMxRKwtzsN59ihxMeeYV0DIwACuYG6154c', NULL, '51.38.135.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidkF5a1FnUjN4TFlpYnVHSHRTdEZDcUFGQ1JQTEVVeFdURklySXAzWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9tYXRoc21hbmFnZXIuZnIvY2xhc3NlLzFzcGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1713216888),
('CbA4lpuzP5O9WjKJ8pCc9cmT8Gtc29pgYz2IUx32', NULL, '104.166.80.23', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieldySGdDcWprUDllb3VaR3JkU1VYN040a0tkMmJIV3Bld0ltV0hJNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713218130),
('cc3XKQRVAIJ5aKIxZgtGDDMufxSzh6K9EUyHYkWp', NULL, '51.38.135.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicElFZ1hiWGZzUTdpQno0Nk90UlA1SXBSaFlvc0UwN0tDN0hxZE9sWiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovL21hdGhzbWFuYWdlci5mci9kcy9teURTIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9tYXRoc21hbmFnZXIuZnIvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1713216890),
('G9DVA0zBSK1NIA6x89uVFEFdqOFMzS602DKmYID3', NULL, '104.166.80.23', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXEyOEZ3c0ZmYmlTSnhxakNVMzRNNFZYQlVJdHk5ZExGU3pvWmRpZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713218131),
('GCqYHnLLbTHoTrnkXZAWOFxWJlnTvjSWYvAZjYP9', NULL, '51.75.162.18', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia1FCa1haVlpXbUF6dnphb0dkVDhTUXRDWmJrUlUxS1o0YXNaUmlTSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYXRoc21hbmFnZXIuZnIvY2xhc3NlL3Rlc3RzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713216889),
('I93O8YFxOYfREYZcqMyGTEmUwtwtQ5jbhpQrfRAE', NULL, '51.75.162.18', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFJ0OGM5OVVSSlRueWhhVHZORHRpV1BEbmc2eHBUWXNPR08zTGdQTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXRoc21hbmFnZXIuZnIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1713216882),
('m2DSb6VuL5OKbTCDNn5WRLUkbc782aJaFW59oEMz', NULL, '147.78.103.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZE1ySzEzS3Z3aThiWkJzY1lKS1hjV3Nma0M1Y0g1VFh4clV3cTZKOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713219678),
('NbHgCMdMiqdbx5yfT3QVmbwg1CblFsgvTAUW7SGe', NULL, '104.166.80.173', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoianBndThRY3dDaEh3UmxtU09ENzZDdmtZblNPYXppZ3NFdlZPMjVlSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713213852),
('P2iar72bQni4Y7aBdjB5mNDof7QbpTc0wyY5eXv6', NULL, '95.164.234.248', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/55.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRlRzTVdQVE5UeWZFVnVUU0kyNnRjNktZcklMTHNzYk1ub25Id2JabSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXRoc21hbmFnZXIuZnIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1713215250),
('TLIhxSfv71LfMY2qQOdXIlFPB0EnWS79EgRJnsqc', 31, '2a01:e0a:2e3:fd60:2cc6:9329:d58f:14a9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaVh6QXlocWJSVUpGcm1mem9tWTBGeTNBeVlzTFZZTzZ2dWl5QmNsQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyL211bHRpcGxlX2NoYXB0ZXJzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzE7fQ==', 1713218212),
('yFBzwGjVMQ2lZVXyEMFbb3l0Ya1A7sVLF09AoW1X', NULL, '40.113.118.83', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUpnOVRGaFptTUFRVUZjeTlOOEZsQzltUzVxNElOOUVyRGdTRFV1NiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWF0aHNtYW5hZ2VyLmZyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1713211082);

-- --------------------------------------------------------

--
-- Structure de la table `subchapters`
--

DROP TABLE IF EXISTS `subchapters`;
CREATE TABLE `subchapters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `subchapters`
--

INSERT INTO `subchapters` (`id`, `chapter_id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(6, 5, 'Démontrer des égalités', 'Le raisonnement par récurrence peut servir à montrer des égalités, notamment sur les suites. Les exercices sont organisés par ordre de difficulté.', '2024-04-04 19:05:52', '2024-04-04 19:06:29'),
(7, 5, 'Démontrer des inégalités', 'Les récurrences sont souvent utilisées pour démontrer des résultats tels que des inégalités, des encadrements ou des monotonies. Il est utile de penser au raisonnement par récurrence même si la question ne l\'indique pas.', '2024-04-04 20:20:44', '2024-04-04 20:20:44'),
(8, 5, 'Conjecturer, puis démontrer', 'Conjecturer signifie émettre une hypothèse, proposer une formule qui paraît juste. Pour se faire, par exemple lorsqu\'il s\'agit de conjecturer l\'expression explicite d\'une suite, on calcule les premiers termes et on essaye de trouver une relation entre le rang et la valeur du terme. Démontrez alors cette conjecture par récurrence pour conclure.', '2024-04-04 20:41:02', '2024-04-04 20:41:02'),
(9, 5, 'Divisibilité et Multiples', 'Ces récurrences sont à travailler surtout pour les personnes en Maths Expertes. Les spé peuvent tout de même y jeter un coup d\'oeil.', '2024-04-04 20:53:42', '2024-04-04 20:53:42'),
(10, 5, 'Sommes et produits', 'Les sommes et produits sont une initiation vers le monde du supérieur. A travailler uniquement si vous souhaitez poursuivre vos études supérieures avec des mathématiques.', '2024-04-04 20:56:09', '2024-04-04 20:56:09'),
(11, 5, 'Récurrences doubles et fortes', 'Ces types de récurrences ne sont pas vues en terminale, cependant si vous souhaitez poursuivre vos études supérieures dans les mathématiques, il vous faudra apprendre ces méthodes.', '2024-04-04 21:02:27', '2024-04-04 21:02:27'),
(12, 6, 'Suites arithmétiques', 'Les suites arithmétiques ne tombent pas souvent au bac, et même en général. Il est cependant important de les maîtriser, et obligatoire pour continuer dans les études supérieures.', '2024-04-06 12:36:56', '2024-04-06 12:36:56'),
(13, 6, 'Suites géométriques', NULL, '2024-04-06 15:30:45', '2024-04-06 15:30:45'),
(14, 6, 'Sens de variation des suites', NULL, '2024-04-06 15:47:46', '2024-04-06 15:47:46'),
(15, 6, 'Sommes', NULL, '2024-04-06 16:01:42', '2024-04-06 16:02:06'),
(16, 7, 'zfrds', NULL, '2024-04-06 18:38:20', '2024-04-06 18:38:20'),
(17, 8, 'Limites d\'une suite géométrique', NULL, '2024-04-07 06:39:51', '2024-04-07 06:39:51'),
(18, 8, 'Limites et formes indéterminées', NULL, '2024-04-09 17:57:48', '2024-04-09 17:57:48'),
(19, 8, 'Théorèmes d\'encadrement et comparaison', NULL, '2024-04-09 17:59:04', '2024-04-09 17:59:04');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `provider_token` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `email_verified_at`, `password`, `provider`, `provider_id`, `provider_token`, `avatar`, `remember_token`, `created_at`, `updated_at`, `verified`) VALUES
(26, 'dadougl', 'admin', 'david@gmail.com', NULL, '$2y$12$kpEuDza8AyrUMWGN/2eU7ejENl3SVl4UxRzubtXjn72uEB/VeKL9O', NULL, NULL, NULL, 'david@gmail.com-Saitama.png', NULL, '2024-03-25 03:26:58', '2024-04-15 18:50:10', 1),
(27, 'mimigl', 'admin', 'mimigl@gmail.com', NULL, '$2y$12$EqkTRuxVbD4wAg1GsantcuX0Av8Zjse4DHQswYf78nnqHgRGd.sNm', NULL, NULL, NULL, 'mimigl@gmail.com-sakuragi.jpg', NULL, '2024-03-25 13:44:46', '2024-04-15 18:49:59', 1),
(29, 'David Meguira', 'student', 'davidmeguira6@gmail.com', NULL, NULL, 'google', '111520259489012703862', 'ya29.a0Ad52N3_tHAedec3E2LruJ2vwWJ_nXK_-GPvslFfQYywme6pjdM2dAPgx-9GJHdBXxbKZ0kS93pHPPga697uypEEFHNPXZpYD1iY_ZkzgpB_pmsuSLR4M4bQo7ohdZYfGJ-pydPoUcn1xK4DtXLEvZn_Zk3oVejsKr7rQaCgYKAf0SARISFQHGX2Mi7we75-8aazpeRAY4OJ3Sbw0171', 'davidmeguira6@gmail.com-cyberpunk.jpg', NULL, '2024-03-26 17:39:47', '2024-04-11 20:11:46', 0),
(30, 'Maxime Boutboul', 'student', 'boutboulmaxime@gmail.com', NULL, NULL, 'google', '106195468100964080906', 'ya29.a0Ad52N3_eqUTOV11mILh-ScqzYDeAwYUy-TAdsfFYp8kq2_TiHT4R1gAUbZEOTvUUSfFfShzzXj0ldaNrNoYv5JkQltoWq83Y7Lnhw3ytzCdZCcPhAwRgFFNZOS2eKDcfsG8Ph6m40bt7Zu9_ti9Dmy18Q_dbmVCCLw4yaCgYKAfwSARASFQHGX2MimzqKfZ3SRkP3LhT_FCf66w0171', 'https://lh3.googleusercontent.com/a/ACg8ocLVR-BT1Vd-3etkZzMeej49msjkHMPnUK4N9r2nWm3qmlygsD0=s96-c', NULL, '2024-04-15 18:37:41', '2024-04-15 18:51:11', 1),
(31, 'DavidMgr_', 'admin', 'davidmgr93@gmail.com', NULL, NULL, 'google', '111164376983001905055', 'ya29.a0Ad52N3_vo6M0O5NWHDQCLqWO5htL42kHqIz5q8r78Mnth-hnN_a-b0cHC8Y8J13xKyrKKyOs2QboIfMxy-8rNxeUyuEB0MIXurQNY8iprrmt7UlyZM4QSXmnMbvBOR3ZTZZPnAMRb89WnsFhrzoPAjKev2Q4odd5cwaCgYKAUASARESFQHGX2MiV8NiDRyO0cl9HYROUTjitA0169', 'https://lh3.googleusercontent.com/a/ACg8ocJpGBybhWnqk85BOzLy8o1b2FEUTeUbkyl-ZQ7tAB_o_KqmEeVn=s96-c', NULL, '2024-04-15 19:26:55', '2024-04-15 19:27:16', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapters_class_id_foreign` (`class_id`);

--
-- Index pour la table `chapters_exercises_ds`
--
ALTER TABLE `chapters_exercises_ds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapters_exercises_ds_chapter_id_foreign` (`chapter_id`),
  ADD KEY `chapters_exercises_ds_exercise_ds_id_foreign` (`exercise_ds_id`);

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `DS`
--
ALTER TABLE `DS`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ds_user_id_foreign` (`user_id`);

--
-- Index pour la table `ds_chapter`
--
ALTER TABLE `ds_chapter`
  ADD KEY `ds_chapter_ds_id_foreign` (`ds_id`),
  ADD KEY `ds_chapter_chapter_id_foreign` (`chapter_id`);

--
-- Index pour la table `ds_exercises`
--
ALTER TABLE `ds_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ds_exercises_multiple_chapter_id_foreign` (`multiple_chapter_id`);

--
-- Index pour la table `ds_exercises_ds`
--
ALTER TABLE `ds_exercises_ds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ds_exercises_ds_ds_id_foreign` (`ds_id`),
  ADD KEY `ds_exercises_ds_ds_exercise_id_foreign` (`ds_exercise_id`);

--
-- Index pour la table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercises_subchapter_id_foreign` (`subchapter_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `multiple_chapters`
--
ALTER TABLE `multiple_chapters`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_chapter_id_foreign` (`chapter_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `subchapters`
--
ALTER TABLE `subchapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subchapters_chapter_id_foreign` (`chapter_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `chapters_exercises_ds`
--
ALTER TABLE `chapters_exercises_ds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `DS`
--
ALTER TABLE `DS`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `ds_exercises`
--
ALTER TABLE `ds_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `ds_exercises_ds`
--
ALTER TABLE `ds_exercises_ds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT pour la table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `multiple_chapters`
--
ALTER TABLE `multiple_chapters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `subchapters`
--
ALTER TABLE `subchapters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `chapters_exercises_ds`
--
ALTER TABLE `chapters_exercises_ds`
  ADD CONSTRAINT `chapters_exercises_ds_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chapters_exercises_ds_exercise_ds_id_foreign` FOREIGN KEY (`exercise_ds_id`) REFERENCES `ds_exercises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `DS`
--
ALTER TABLE `DS`
  ADD CONSTRAINT `ds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `ds_chapter`
--
ALTER TABLE `ds_chapter`
  ADD CONSTRAINT `ds_chapter_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ds_chapter_ds_id_foreign` FOREIGN KEY (`ds_id`) REFERENCES `DS` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ds_exercises`
--
ALTER TABLE `ds_exercises`
  ADD CONSTRAINT `ds_exercises_multiple_chapter_id_foreign` FOREIGN KEY (`multiple_chapter_id`) REFERENCES `multiple_chapters` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ds_exercises_ds`
--
ALTER TABLE `ds_exercises_ds`
  ADD CONSTRAINT `ds_exercises_ds_ds_exercise_id_foreign` FOREIGN KEY (`ds_exercise_id`) REFERENCES `ds_exercises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ds_exercises_ds_ds_id_foreign` FOREIGN KEY (`ds_id`) REFERENCES `DS` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `exercises_subchapter_id_foreign` FOREIGN KEY (`subchapter_id`) REFERENCES `subchapters` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subchapters`
--
ALTER TABLE `subchapters`
  ADD CONSTRAINT `subchapters_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
