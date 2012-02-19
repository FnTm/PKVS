SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `turniri` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `turniri` ;

-- -----------------------------------------------------
-- Table `turniri`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`users` (
  `userId` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  `email` VARCHAR(320) NOT NULL ,
  `role` SET('u','b','a') NOT NULL ,
  `isFromFacebook` TINYINT NOT NULL DEFAULT 0 ,
  `facebookId` INT NULL DEFAULT NULL ,
  `registered` DATETIME NOT NULL ,
  `isBlocked` TINYINT NOT NULL DEFAULT 0 ,
  `regNr` VARCHAR(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`userId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`tournaments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`tournaments` (
  `tournamentId` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  `time` DATETIME NOT NULL ,
  `tournamentOwner` INT NOT NULL ,
  `logo` TEXT NULL ,
  PRIMARY KEY (`tournamentId`) ,
  INDEX `turnirsOwner` (`tournamentOwner` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`galleries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`galleries` (
  `galleryId` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `tournamentId` INT NOT NULL ,
  PRIMARY KEY (`galleryId`) ,
  INDEX `galerijaTurnirsId` (`tournamentId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`pictures`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`pictures` (
  `pictureId` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `galleryId` INT NOT NULL ,
  PRIMARY KEY (`pictureId`) ,
  INDEX `galerijaId` (`galleryId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`news`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`news` (
  `newsId` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `content` LONGTEXT NOT NULL ,
  `tournamentId` INT NOT NULL ,
  `pictureId` INT NULL DEFAULT NULL ,
  `galleryId` INT NULL DEFAULT NULL ,
  `ownerId` INT NOT NULL ,
  `time` DATETIME NOT NULL ,
  PRIMARY KEY (`newsId`) ,
  INDEX `newsTurnirsId` (`tournamentId` ASC) ,
  INDEX `thumbnail` (`pictureId` ASC) ,
  INDEX `galerija` (`galleryId` ASC) ,
  INDEX `ownerId` (`ownerId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`attachments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`attachments` (
  `attachmentId` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `file` TEXT NOT NULL ,
  `tournamentId` INT NOT NULL ,
  PRIMARY KEY (`attachmentId`) ,
  INDEX `pielikumsTurnirsId` (`tournamentId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`rent`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`rent` (
  `rentId` INT NOT NULL AUTO_INCREMENT ,
  `description` TEXT NOT NULL ,
  `place` VARCHAR(255) NOT NULL ,
  `link` TEXT NULL DEFAULT NULL ,
  `email` VARCHAR(320) NOT NULL ,
  `phone` VARCHAR(32) NOT NULL ,
  PRIMARY KEY (`rentId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`ads`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`ads` (
  `adId` INT NOT NULL AUTO_INCREMENT ,
  `clicks` INT NOT NULL ,
  `views` INT NOT NULL ,
  `creationTime` DATETIME NOT NULL ,
  `position` INT NOT NULL ,
  PRIMARY KEY (`adId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`sponsors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`sponsors` (
  `sponsorId` INT NOT NULL AUTO_INCREMENT ,
  `userId` INT NOT NULL ,
  `demands` TEXT NOT NULL ,
  `offers` TEXT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`sponsorId`) ,
  INDEX `lietotajsId` (`userId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`ratings` (
  `ratingId` INT NOT NULL AUTO_INCREMENT ,
  `userId` INT NOT NULL ,
  `rating` DECIMAL(10,0)  NOT NULL ,
  `raterId` INT NOT NULL ,
  PRIMARY KEY (`ratingId`) ,
  INDEX `lietotajsId` (`userId` ASC) ,
  INDEX `vertetajsId` (`raterId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`participants`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`participants` (
  `userId` INT NOT NULL ,
  `tournamentId` INT NOT NULL ,
  PRIMARY KEY (`userId`, `tournamentId`) ,
  INDEX `userId` (`userId` ASC) ,
  INDEX `turnirsId` (`tournamentId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`teams`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`teams` (
  `teamId` INT NOT NULL AUTO_INCREMENT ,
  `tournamentId` INT NOT NULL ,
  `teamName` VARCHAR(45) NOT NULL ,
  `teamOwner` INT NOT NULL ,
  PRIMARY KEY (`teamId`) ,
  INDEX `tournamentId` (`tournamentId` ASC) ,
  INDEX `teamOwner` (`teamOwner` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turniri`.`teamMembers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `turniri`.`teamMembers` (
  `memberId` INT NOT NULL ,
  `teamId` INT NOT NULL ,
  INDEX `PK` (`memberId` ASC, `teamId` ASC) ,
  INDEX `userId` (`memberId` ASC) ,
  INDEX `teamId` (`teamId` ASC) ,
  PRIMARY KEY (`memberId`, `teamId`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `turniri`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `turniri`;
INSERT INTO `turniri`.`users` (`userId`, `name`, `username`, `password`, `email`, `role`, `isFromFacebook`, `facebookId`, `registered`, `isBlocked`, `regNr`) VALUES (1, 'Jānis Peisenieks', 'jpeiseni', '0260913d507268d5cbf507832f22982e', 'janis@peisenieks.lv', 'a', 0, NULL, '2012-01-06 20:00:24', 0, 'NULL');

COMMIT;
