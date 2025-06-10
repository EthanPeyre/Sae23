-- SQL Schema for SAE23 – IUT Blagnac IoT Portal

-- 1) Database
CREATE DATABASE IF NOT EXISTS `sae23` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;
USE `sae23`;

-- 2) Administrator Table
--    Stores the accounts for the site's super-administrator
CREATE TABLE `Administrateur` (
  `login`       VARCHAR(50)   NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,  -- MD5 or password_hash depending on your choices
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3) Building Table
--    Represents a building managed by the site
CREATE TABLE `Batiment` (
  `id`           INT            NOT NULL AUTO_INCREMENT,
  `nom`          VARCHAR(100)   NOT NULL UNIQUE,
  `login`        VARCHAR(50)    NULL,  -- if you allow each building to have login/password
  `mot_de_passe` VARCHAR(25)   NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`login`) REFERENCES `Administrateur`(`login`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4) Room Table
--    Each room belongs to a building
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

-- 5) Sensor Table
--    Each sensor is attached to a room
CREATE TABLE `Capteur` (
  `nom`       VARCHAR(100) NOT NULL,
  `type`      VARCHAR(50)  NOT NULL,   -- e.g. temperature, humidity, co2…
  `unite`     VARCHAR(20)  NOT NULL,   -- e.g. °C, %, ppm…
  `id_salle`  VARCHAR(50)  NOT NULL,   -- FK to Salle.nom
  PRIMARY KEY (`nom`),
  INDEX (`id_salle`),
  FOREIGN KEY (`id_salle`)
    REFERENCES `Salle`(`nom`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6) Measurement Table
--    Each reading from a sensor at a given time
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