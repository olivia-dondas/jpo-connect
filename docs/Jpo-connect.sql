-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : sam. 14 juin 2025 à 22:28
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jpo-connect`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Contenu du commentaire ou de l''avis.',
  `user_id` int NOT NULL,
  `open_day_id` int NOT NULL,
  `parent_id` int DEFAULT NULL COMMENT 'Auto-référence pour les réponses.',
  `moderation_status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'Statut pour la modération.',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `content`, `user_id`, `open_day_id`, `parent_id`, `moderation_status`, `created_at`) VALUES
(1, 'Super journée, l\'accueil était top !', 4, 1, NULL, 'approved', '2025-03-15 17:00:00'),
(2, 'Bonjour, est-ce que les formations sont accessibles en alternance ?', 5, 1, NULL, 'pending', '2025-03-16 08:30:00'),
(3, 'Merci pour votre retour positif Alice !', 2, 1, 1, 'approved', '2025-03-16 09:00:00'),
(4, 'Un peu déçue, il y avait beaucoup trop de monde.', 6, 1, NULL, 'rejected', '2025-03-17 11:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `locations`
--

CREATE TABLE `locations` (
  `id` int NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Par exemple : Paris, Cannes, Martigues.',
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `locations`
--

INSERT INTO `locations` (`id`, `city`, `address`, `created_at`) VALUES
(1, 'Cannes', '7 Rue de la Gare, 06400 Cannes', '2024-01-10 09:00:00'),
(2, 'Martigues', '12 Avenue des Rayettes, 13500 Martigues', '2024-01-10 09:00:00'),
(3, 'Paris', '3 Rue de Vienne, 75008 Paris', '2024-01-10 09:00:00'),
(4, 'Marseille', '8 Rue d\'Hozier, 13002 Marseille', '2024-01-10 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `open_days`
--

CREATE TABLE `open_days` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `event_date` datetime NOT NULL,
  `capacity` int NOT NULL DEFAULT '100' COMMENT 'Capacité d''accueil maximale pour l''événement.',
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'upcoming' COMMENT 'Statut : à venir, terminé, annulé.',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `open_days`
--

INSERT INTO `open_days` (`id`, `location_id`, `title`, `description`, `event_date`, `capacity`, `status`, `created_at`) VALUES
(1, 3, 'JPO La Plateforme_ Paris - Printemps 2025', 'Découvrez nos formations dans notre campus parisien.', '2025-03-15 10:00:00', 150, 'completed', '2025-01-15 10:00:00'),
(2, 1, 'JPO La Plateforme_ Cannes - Été 2025', 'Venez nous rencontrer sur la Côte d\'Azur.', '2025-07-12 10:00:00', 200, 'upcoming', '2025-05-20 10:00:00'),
(3, 2, 'JPO La Plateforme_ Martigues - Rentrée 2025', 'Préparez votre avenir à Martigues.', '2025-09-06 10:00:00', 120, 'upcoming', '2025-06-01 14:00:00'),
(4, 3, 'Session d\'information spéciale IA - Paris', 'Une session annulée pour tester l\'affichage.', '2025-04-20 14:00:00', 50, 'cancelled', '2025-03-10 08:00:00'),
(5, 4, 'Grande JPO de rentrée - Marseille', 'Le campus historique vous ouvre ses portes !', '2025-09-13 10:00:00', 300, 'upcoming', '2025-06-15 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `registrations`
--

CREATE TABLE `registrations` (
  `user_id` int NOT NULL,
  `open_day_id` int NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attendance_status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'registered' COMMENT 'Statut de présence pour les stats.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gère la relation N-N entre utilisateurs et JPO.';

--
-- Déchargement des données de la table `registrations`
--

INSERT INTO `registrations` (`user_id`, `open_day_id`, `registration_date`, `attendance_status`) VALUES
(4, 1, '2025-03-02 09:20:00', 'attended'),
(4, 2, '2025-06-05 12:00:00', 'registered'),
(4, 5, '2025-07-01 09:00:00', 'registered'),
(5, 1, '2025-03-03 10:30:00', 'attended'),
(5, 2, '2025-06-08 13:00:00', 'registered'),
(6, 1, '2025-03-06 17:10:00', 'absent'),
(6, 5, '2025-07-02 14:30:00', 'registered');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('student','employee','manager','director') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'student' COMMENT 'Définit les permissions : étudiant, salarié, responsable, directeur.',
  `google_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Permet la connexion via un compte Google.',
  `linkedin_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Permet la connexion via un compte LinkedIn.',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `phone_number`, `role`, `google_id`, `linkedin_id`, `created_at`) VALUES
(1, 'Jean', 'Dupont', 'directeur@laplateforme.io', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '0123456789', 'director', NULL, NULL, '2024-02-01 08:00:00'),
(2, 'Marie', 'Curie', 'responsable@laplateforme.io', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '0234567890', 'manager', NULL, NULL, '2024-02-01 08:10:00'),
(3, 'Pierre', 'Martin', 'salarie@laplateforme.io', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '0345678901', 'employee', NULL, NULL, '2024-02-01 08:15:00'),
(4, 'Alice', 'Dubois', 'alice.d@email.com', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '0611223344', 'student', NULL, NULL, '2025-03-01 13:00:00'),
(5, 'Bob', 'Lemoine', 'bob.l@email.com', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '0655667788', 'student', NULL, NULL, '2025-03-02 10:00:00'),
(6, 'Chloé', 'Petit', 'chloe.p@email.com', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '0699887766', 'student', NULL, NULL, '2025-03-05 17:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `open_day_id` (`open_day_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `city` (`city`);

--
-- Index pour la table `open_days`
--
ALTER TABLE `open_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `event_date` (`event_date`);

--
-- Index pour la table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`user_id`,`open_day_id`),
  ADD KEY `open_day_id` (`open_day_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD UNIQUE KEY `linkedin_id` (`linkedin_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `open_days`
--
ALTER TABLE `open_days`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_open_day` FOREIGN KEY (`open_day_id`) REFERENCES `open_days` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `open_days`
--
ALTER TABLE `open_days`
  ADD CONSTRAINT `fk_open_days_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_registrations_open_day` FOREIGN KEY (`open_day_id`) REFERENCES `open_days` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registrations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
