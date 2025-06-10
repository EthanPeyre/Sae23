-- Schéma SQL pour la SAE23 – Portail IoT IUT Blagnac

-- 1) Base de données
CREATE DATABASE IF NOT EXISTS `sae23` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;
USE `sae23`;

-- 2) Table Administrateur
--    Stocke les comptes du super‑administrateur du site
CREATE TABLE `Administrateur` (
  `login`       VARCHAR(50)   NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,  -- MD5 ou password_hash selon vos choix
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3) Table Bâtiment
--    Représente un bâtiment géré par le site
CREATE TABLE `Batiment` (
  `id`           INT            NOT NULL AUTO_INCREMENT,
  `nom`          VARCHAR(100)   NOT NULL UNIQUE,
  `login`        VARCHAR(50)    NULL,  -- si vous autorisez chaque bâtiment à avoir login/mdp
  `mot_de_passe` VARCHAR(25)   NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`login`) REFERENCES `Administrateur`(`login`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4) Table Salle
--    Chaque salle appartient à un bâtiment
CREATE TABLE `Salle` (
  `nom`         VARCHAR(50)  NOT NULL,
  `type`        VARCHAR(50)  NOT NULL,
  `capacite`    INT          NOT NULL,
  `id_batiment` INT          NOT NULL,
  PRIMARY KEY (`nom`),
  INDEX (`id_batiment`),
  FOREIGN KEY (`id_batiment`)
    REFERENCES `Batiment`(`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5) Table Capteur
--    Chaque capteur est rattaché à une salle
CREATE TABLE `Capteur` (
  `nom`       VARCHAR(100) NOT NULL,
  `type`      VARCHAR(50)  NOT NULL,   -- ex. temperature, humidity, co2…
  `unite`     VARCHAR(20)  NOT NULL,   -- ex. °C, %, ppm…
  `id_salle`  VARCHAR(50)  NOT NULL,   -- FK vers Salle.nom
  PRIMARY KEY (`nom`),
  INDEX (`id_salle`),
  FOREIGN KEY (`id_salle`)
    REFERENCES `Salle`(`nom`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6) Table Mesure
--    Chaque relevé d’un capteur à un instant donné
CREATE TABLE `Mesure` (
  `id`         INT            NOT NULL AUTO_INCREMENT,
  `valeur`     DOUBLE         NOT NULL,
  `date`       DATE           NOT NULL,
  `heure`      TIME           NOT NULL,
  `id_capteur` VARCHAR(100)   NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`id_capteur`),
  FOREIGN KEY (`id_capteur`)
    REFERENCES `Capteur`(`nom`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
