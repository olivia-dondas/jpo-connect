CREATE TABLE `locations` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `city` varchar(255) UNIQUE NOT NULL COMMENT 'Par exemple : Paris, Cannes, Martigues.',
  `address` text NOT NULL,
  `created_at` timestamp DEFAULT (now())
);

CREATE TABLE `open_days` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `location_id` integer NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `event_date` datetime NOT NULL,
  `capacity` integer NOT NULL DEFAULT 100 COMMENT 'Capacité d''accueil maximale pour l''événement.',
  `status` varchar(50) DEFAULT 'upcoming' COMMENT 'Statut de l''événement : à venir, terminé, annulé.',
  `created_at` timestamp DEFAULT (now())
);

CREATE TABLE `users` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `email` varchar(255) UNIQUE NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone_number` varchar(20),
  `role` ENUM ('student', 'employee', 'manager', 'director') NOT NULL DEFAULT 'student' COMMENT 'Définit les permissions : étudiant, salarié, responsable, directeur.',
  `google_id` varchar(255) UNIQUE COMMENT 'Permet la connexion via un compte Google, une fonctionnalité optionnelle du projet.',
  `linkedin_id` varchar(255) UNIQUE COMMENT 'Permet la connexion via un compte LinkedIn, une fonctionnalité optionnelle du projet.',
  `created_at` timestamp DEFAULT (now())
);

CREATE TABLE `registrations` (
  `user_id` integer NOT NULL,
  `open_day_id` integer NOT NULL,
  `registration_date` timestamp DEFAULT (now()),
  `attendance_status` varchar(50) NOT NULL DEFAULT 'registered' COMMENT 'Permet de suivre la présence pour les statistiques : inscrit, désinscrit, présent, absent.',
  PRIMARY KEY (`user_id`, `open_day_id`)
);

CREATE TABLE `comments` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `content` text NOT NULL COMMENT 'Contenu du commentaire ou de l''avis.',
  `user_id` integer NOT NULL,
  `open_day_id` integer NOT NULL,
  `parent_id` integer COMMENT 'Auto-référence pour permettre les réponses aux commentaires (fils de discussion).',
  `moderation_status` varchar(50) NOT NULL DEFAULT 'pending' COMMENT 'Statut pour la modération : en attente, approuvé, rejeté.',
  `created_at` timestamp DEFAULT (now())
);

CREATE INDEX `open_days_index_0` ON `open_days` (`event_date`);

CREATE INDEX `open_days_index_1` ON `open_days` (`location_id`);

CREATE INDEX `comments_index_2` ON `comments` (`user_id`);

CREATE INDEX `comments_index_3` ON `comments` (`open_day_id`);

CREATE INDEX `comments_index_4` ON `comments` (`parent_id`);

ALTER TABLE `registrations` COMMENT = 'Gère la relation plusieurs-à-plusieurs (N-N) entre les utilisateurs et les JPO.';

ALTER TABLE `open_days` ADD FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

ALTER TABLE `registrations` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `registrations` ADD FOREIGN KEY (`open_day_id`) REFERENCES `open_days` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`open_day_id`) REFERENCES `open_days` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`);
