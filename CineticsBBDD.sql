DROP DATABASE IF EXISTS `cinetics`;
CREATE DATABASE IF NOT EXISTS `cinetics` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE `cinetics`;

CREATE TABLE IF NOT EXISTS `users`(
    `iduser` INT AUTO_INCREMENT NOT NULL,
    `mail` VARCHAR(40) UNIQUE NOT NULL,
    `username` VARCHAR(16) UNIQUE NOT NULL,
    `passHash` VARCHAR(60) NOT NULL,
    `userFirstName` VARCHAR(60) NOT NULL,
    `userLastName` VARCHAR(120) NOT NULL,
    `creationDate` DATETIME NOT NULL,
    `removeDate` DATETIME NOT NULL,
    `lastSignIn` DATETIME NOT NULL,
    `active` TINYINT(1) NOT NULL,
    `activationDate` DATETIME,
	`activationCode` CHAR(64),
	`resetPassExpiry` DATETIME,
	`resetPassCode` CHAR(64),
    PRIMARY KEY(`iduser`)
);