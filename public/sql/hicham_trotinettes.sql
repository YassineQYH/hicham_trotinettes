-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 sep. 2025 à 12:48
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hicham_trotinettes`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessory`
--

CREATE TABLE `accessory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accessory`
--

INSERT INTO `accessory` (`id`, `name`, `slug`, `description`, `image`, `is_best`) VALUES
(1, 'Volant', 'volant', 'Volant', 'test.png', 1),
(2, 'Roue', 'roue', 'Roue', 'test.png', 1),
(3, 'Guidon', 'guidon', 'Guidon', 'guidon.png', 0),
(4, 'Frein', 'frein', 'Frein', 'frein.png', 0),
(5, 'Accessoire de test', 'Accessoire-de-test', '<div>Accessoire de test</div>', 'controls.png', 0);

-- --------------------------------------------------------

--
-- Structure de la table `caracteristique`
--

CREATE TABLE `caracteristique` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `caracteristique`
--

INSERT INTO `caracteristique` (`id`, `name`) VALUES
(1, 'Taille'),
(2, 'Poids'),
(3, 'Batterie'),
(4, 'Vitesse maximale'),
(5, 'Autonomie'),
(6, 'Charge maximale'),
(7, 'sécurité enfant');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250924130421', '2025-09-25 10:42:28', 295),
('DoctrineMigrations\\Version20250924145650', '2025-09-25 10:42:29', 41),
('DoctrineMigrations\\Version20250925080726', '2025-09-25 10:42:29', 100),
('DoctrineMigrations\\Version20250925084521', '2025-09-25 10:45:32', 43),
('DoctrineMigrations\\Version20250926132657', '2025-09-26 15:27:14', 254),
('DoctrineMigrations\\Version20250929072839', '2025-09-29 09:29:00', 543),
('DoctrineMigrations\\Version20250929075713', '2025-09-29 09:57:34', 270),
('DoctrineMigrations\\Version20250929084045', '2025-09-29 10:40:57', 135),
('DoctrineMigrations\\Version20250929095851', '2025-09-29 11:59:01', 271);

-- --------------------------------------------------------

--
-- Structure de la table `illustration`
--

CREATE TABLE `illustration` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `illustration`
--

INSERT INTO `illustration` (`id`, `trottinette_id`, `image`) VALUES
(1, 1, 'trottbleue-02.png'),
(2, 1, 'trottbleue-03.png'),
(3, 1, 'trottbleue-04.png'),
(4, 1, 'trottbleue-05.png'),
(5, 2, 'trottjaune-02.jpg'),
(6, 2, 'trottjaune-03.jpg'),
(7, 2, 'trottjaune-04.jpg'),
(8, 2, 'trottjaune-05.jpg'),
(9, 3, 'trottvert-02.jpg'),
(10, 3, 'trottvert-03.jpg'),
(11, 3, 'trottvert-04.jpg'),
(12, 3, 'trottvert-05.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `illustrationaccess`
--

CREATE TABLE `illustrationaccess` (
  `id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trottinette`
--

CREATE TABLE `trottinette` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_short` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `description_short` longtext DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `is_header` tinyint(1) NOT NULL,
  `header_image` varchar(255) DEFAULT NULL,
  `header_btn_title` varchar(255) DEFAULT NULL,
  `header_btn_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette`
--

INSERT INTO `trottinette` (`id`, `name`, `name_short`, `slug`, `description`, `description_short`, `image`, `is_best`, `is_header`, `header_image`, `header_btn_title`, `header_btn_url`) VALUES
(1, 'Trottinette électrique honey whale m5 max avec siège', 'Honey Whale M5 Max', 'Trottinette-électrique-honey-whale-m5-max-avec-siège', '<div>【Performance puissante】...</div>', '<div>Moteur 1000 W, pneus 14 pouces, autonomie 40 km</div>', 'trottbleue-01.png', 1, 1, NULL, 'test', 'test'),
(2, 'KUGOO Kukirin C1 Pro', 'KUGOO C1 Pro', 'KUGOO-Kukirin-C1-Pro', '<div>Aperçu du produit : Vitesse maximale 45 km/h Charge max. 120 kg Autonomie 100 km Puissance continue 500 W Siège</div>', '<div>Vitesse 45 km/h, autonomie 100 km, charge max 120 kg</div>', 'trottjaune-01.jpg', 1, 0, NULL, 'test', 'test'),
(3, 'Bogist M5 Pro', 'Bogist M5 Pro', 'Bogist-M5-Pro', '<div>Moteur puissant de 500 W pour des vitesses élevées...</div>', '<div>Moteur 500 W, pneus 12 pouces, autonomie 35 km</div>', 'trottvert-01.jpg', 1, 1, NULL, 'test', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_accessory`
--

CREATE TABLE `trottinette_accessory` (
  `trottinette_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_accessory`
--

INSERT INTO `trottinette_accessory` (`trottinette_id`, `accessory_id`, `id`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(2, 1, 5),
(2, 3, 6),
(3, 2, 7),
(3, 4, 8),
(1, 5, 9);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_caracteristique`
--

CREATE TABLE `trottinette_caracteristique` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) DEFAULT NULL,
  `caracteristique_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_caracteristique`
--

INSERT INTO `trottinette_caracteristique` (`id`, `trottinette_id`, `caracteristique_id`, `value`) VALUES
(1, 1, 1, '1380 x 320 x 630 mm'),
(2, 1, 2, '36 kg'),
(3, 1, 3, '48 V 13 Ah'),
(4, 1, 4, '40 km/h'),
(5, 1, 5, '40 km'),
(6, 1, 6, '120 kg'),
(7, 2, 1, '1200 x 300 x 600 mm'),
(8, 2, 2, '30 kg'),
(9, 2, 3, '48 V 12 Ah'),
(10, 2, 4, '45 km/h'),
(11, 2, 5, '100 km'),
(12, 2, 6, '120 kg'),
(13, 3, 1, '1250 x 310 x 620 mm'),
(14, 3, 2, '25 kg'),
(15, 3, 3, '48 V 15 Ah'),
(16, 3, 4, '40 km/h'),
(17, 3, 5, '35 km'),
(18, 3, 6, '120 kg'),
(19, 1, 7, 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_description_section`
--

CREATE TABLE `trottinette_description_section` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `section_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `tel` varchar(16) NOT NULL,
  `country` varchar(32) NOT NULL,
  `postal_code` varchar(16) NOT NULL,
  `address` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `tel`, `country`, `postal_code`, `address`) VALUES
(1, 'admin@admin.fr', '[\"ROLE_ADMIN\",\"ROLE_USER\"]', 'Admin', 'Admin', 'Admin', '06 04 05 02 09', 'France', '63200', '51 Rue de Konoha'),
(2, 'user@user.fr', '[]', 'User', 'User', 'User', '06 01 01 01 02', 'France', '63118', '51 Rue du Hueco Mundo');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessory`
--
ALTER TABLE `accessory`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `illustration`
--
ALTER TABLE `illustration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D67B9A42F6798F43` (`trottinette_id`);

--
-- Index pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EA75D19D27E8CC78` (`accessory_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B37F755EF6798F43` (`trottinette_id`),
  ADD KEY `IDX_B37F755E27E8CC78` (`accessory_id`);

--
-- Index pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_22FC340CF6798F43` (`trottinette_id`),
  ADD KEY `IDX_22FC340C1704EEB7` (`caracteristique_id`);

--
-- Index pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B92E215BF6798F43` (`trottinette_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accessory`
--
ALTER TABLE `accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trottinette`
--
ALTER TABLE `trottinette`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `illustration`
--
ALTER TABLE `illustration`
  ADD CONSTRAINT `FK_D67B9A42F6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  ADD CONSTRAINT `FK_EA75D19D27E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `accessory` (`id`);

--
-- Contraintes pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  ADD CONSTRAINT `FK_B37F755E27E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `accessory` (`id`),
  ADD CONSTRAINT `FK_B37F755EF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  ADD CONSTRAINT `FK_22FC340C1704EEB7` FOREIGN KEY (`caracteristique_id`) REFERENCES `caracteristique` (`id`),
  ADD CONSTRAINT `FK_22FC340CF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD CONSTRAINT `FK_B92E215BF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
