SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `core` ;
CREATE SCHEMA IF NOT EXISTS `core` ;
USE `core` ;

-- -----------------------------------------------------
-- Table `core`.`Gender`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Gender` ;

CREATE  TABLE IF NOT EXISTS `core`.`Gender` (
  `GenderId` INT NOT NULL AUTO_INCREMENT ,
  `GenderName` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`GenderId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`USState`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`USState` ;

CREATE  TABLE IF NOT EXISTS `core`.`USState` (
  `USStateId` INT NOT NULL AUTO_INCREMENT ,
  `USStateName` VARCHAR(45) NOT NULL ,
  `USStateAbbr` VARCHAR(2) NOT NULL ,
  PRIMARY KEY (`USStateId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`User` ;

CREATE  TABLE IF NOT EXISTS `core`.`User` (
  `UserId` INT NOT NULL AUTO_INCREMENT ,
  `UserEmail` VARCHAR(45) NULL ,
  `UserPassword` VARCHAR(45) NULL ,
  `UserFirstName` VARCHAR(45) NULL ,
  `UserLastName` VARCHAR(45) NULL ,
  `GenderId` INT NULL ,
  `UserAddress` VARCHAR(100) NULL ,
  `UserCity` VARCHAR(45) NULL ,
  `USStateId` INT NULL ,
  `UserZip` VARCHAR(5) NULL ,
  `UserPhoneNumber` VARCHAR(45) NULL ,
  `UserLastLogin` TIMESTAMP NULL DEFAULT NOW() ,
  `UserLoginCount` INT NULL DEFAULT 0 ,
  `UserRegistered` TINYINT(1) NULL DEFAULT false ,
  PRIMARY KEY (`UserId`) ,
  INDEX `User_GenderId_idx` (`GenderId` ASC) ,
  INDEX `User_USStateId_idx` (`USStateId` ASC) ,
  CONSTRAINT `User_GenderId_fk`
    FOREIGN KEY (`GenderId` )
    REFERENCES `core`.`Gender` (`GenderId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `User_USStateId_fk`
    FOREIGN KEY (`USStateId` )
    REFERENCES `core`.`USState` (`USStateId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Image` ;

CREATE  TABLE IF NOT EXISTS `core`.`Image` (
  `ImageId` INT NOT NULL AUTO_INCREMENT ,
  `ImageName` VARCHAR(100) NOT NULL ,
  `ImageOrigWidth` INT NOT NULL ,
  `ImageOrigHeight` INT NOT NULL ,
  PRIMARY KEY (`ImageId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Location` ;

CREATE  TABLE IF NOT EXISTS `core`.`Location` (
  `LocationId` INT NOT NULL AUTO_INCREMENT ,
  `UserId` INT NOT NULL ,
  `LocationName` VARCHAR(200) NOT NULL ,
  `LocationDescription` TEXT NULL ,
  `ImageId` INT NOT NULL ,
  `LocationPhoneNumber` VARCHAR(45) NOT NULL ,
  `LocationAddress` VARCHAR(100) NOT NULL ,
  `LocationCity` VARCHAR(45) NOT NULL ,
  `USStateId` INT NOT NULL ,
  `LocationZip` VARCHAR(5) NOT NULL ,
  `LocationWebSite` VARCHAR(200) NULL ,
  `LocationTwitterUserName` VARCHAR(45) NULL ,
  `LocationFacebookUserName` VARCHAR(45) NULL ,
  `LocationInstagramUserName` VARCHAR(45) NULL ,
  `LocationDeleted` TINYINT(1) NOT NULL DEFAULT False ,
  `LocationCreatedTime` TIMESTAMP NOT NULL DEFAULT NOW() ,
  `LocationTz` VARCHAR(5) NULL ,
  `LocationLat` VARCHAR(15) NULL ,
  `LocationLng` VARCHAR(15) NULL ,
  PRIMARY KEY (`LocationId`) ,
  INDEX `Location_UserId_idx` (`UserId` ASC) ,
  UNIQUE INDEX `Location_UserId_LocationName_unq` (`LocationName` ASC, `UserId` ASC) ,
  INDEX `Location_USStateId_idx` (`USStateId` ASC) ,
  INDEX `Location_ImageId_idx` (`ImageId` ASC) ,
  CONSTRAINT `Location_UserId_fk`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE RESTRICT,
  CONSTRAINT `Location_USStateId_fk`
    FOREIGN KEY (`USStateId` )
    REFERENCES `core`.`USState` (`USStateId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `Location_ImageId`
    FOREIGN KEY (`ImageId` )
    REFERENCES `core`.`Image` (`ImageId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Transmitter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Transmitter` ;

CREATE  TABLE IF NOT EXISTS `core`.`Transmitter` (
  `TransmitterId` INT NOT NULL AUTO_INCREMENT ,
  `TransmitterName` VARCHAR(500) NULL COMMENT 'This is used to describe uniquly what the transmitter looks like.' ,
  `TransmitterSSID` VARCHAR(100) NOT NULL ,
  `TransmitterBSSID` VARCHAR(45) NULL ,
  `LocationId` INT NOT NULL ,
  PRIMARY KEY (`TransmitterId`) ,
  INDEX `TransmitterLocationId_idx_idx` (`LocationId` ASC) ,
  CONSTRAINT `TransmitterLocationId_idx`
    FOREIGN KEY (`LocationId` )
    REFERENCES `core`.`Location` (`LocationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Tile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Tile` ;

CREATE  TABLE IF NOT EXISTS `core`.`Tile` (
  `TileId` INT NOT NULL AUTO_INCREMENT ,
  `LocationId` INT NOT NULL ,
  `ImageId` INT NOT NULL ,
  `TileCaption` TEXT NOT NULL ,
  `TileDeleted` TINYINT(1) NOT NULL DEFAULT False ,
  `TileCreationTime` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`TileId`) ,
  INDEX `TileLocationId_idx_idx` (`LocationId` ASC) ,
  INDEX `TileImageId_idx` (`ImageId` ASC) ,
  CONSTRAINT `TileLocationId_idx`
    FOREIGN KEY (`LocationId` )
    REFERENCES `core`.`Location` (`LocationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TileImageId`
    FOREIGN KEY (`ImageId` )
    REFERENCES `core`.`Image` (`ImageId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`DeviceType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`DeviceType` ;

CREATE  TABLE IF NOT EXISTS `core`.`DeviceType` (
  `DeviceTypeId` INT NOT NULL AUTO_INCREMENT ,
  `DeviceTypeName` VARCHAR(45) NULL ,
  PRIMARY KEY (`DeviceTypeId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Device`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Device` ;

CREATE  TABLE IF NOT EXISTS `core`.`Device` (
  `DeviceId` INT NOT NULL AUTO_INCREMENT ,
  `DeviceUUID` VARCHAR(45) NULL COMMENT 'Some unique identifier that can identify the device.' ,
  `DeviceTypeId` INT NULL ,
  `DeviceRegisterCount` INT NOT NULL DEFAULT 0 ,
  `DeviceLastRegistered` TIMESTAMP NOT NULL DEFAULT NOW() ,
  `UserId` INT NOT NULL ,
  PRIMARY KEY (`DeviceId`) ,
  INDEX `DeviceTypeId_idx` (`DeviceTypeId` ASC) ,
  INDEX `UserId_idx` (`UserId` ASC) ,
  CONSTRAINT `DeviceTypeId`
    FOREIGN KEY (`DeviceTypeId` )
    REFERENCES `core`.`DeviceType` (`DeviceTypeId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserId`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`UserLocationFavorite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`UserLocationFavorite` ;

CREATE  TABLE IF NOT EXISTS `core`.`UserLocationFavorite` (
  `UserLocationFavoriteId` INT NOT NULL AUTO_INCREMENT ,
  `UserId` INT NULL ,
  `LocationId` INT NULL ,
  `FavoritedTime` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`UserLocationFavoriteId`) ,
  INDEX `UserLocationStarUserId_idx` (`UserId` ASC) ,
  INDEX `UserLocationStarLocationId_idx` (`LocationId` ASC) ,
  CONSTRAINT `UserLocationStarUserId`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserLocationStarLocationId`
    FOREIGN KEY (`LocationId` )
    REFERENCES `core`.`Location` (`LocationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`UserTileFavorite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`UserTileFavorite` ;

CREATE  TABLE IF NOT EXISTS `core`.`UserTileFavorite` (
  `UserTileFavoriteId` INT NOT NULL AUTO_INCREMENT ,
  `UserId` INT NULL ,
  `TileId` INT NULL ,
  `FavoritedTimestamp` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`UserTileFavoriteId`) ,
  INDEX `UserTIleStarUserId_idx` (`UserId` ASC) ,
  INDEX `UserTileStarTileId_idx_idx` (`TileId` ASC) ,
  CONSTRAINT `UserTIleStarUserId_idx`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserTileStarTileId_idx`
    FOREIGN KEY (`TileId` )
    REFERENCES `core`.`Tile` (`TileId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`UserLocationVisit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`UserLocationVisit` ;

CREATE  TABLE IF NOT EXISTS `core`.`UserLocationVisit` (
  `UserLocationVisitId` INT NOT NULL AUTO_INCREMENT ,
  `UserId` INT NOT NULL ,
  `LocationId` INT NOT NULL ,
  `VisitCount` INT NOT NULL DEFAULT 1 ,
  `LastVisited` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`UserLocationVisitId`) ,
  INDEX `UserLocationVisitUserId_idx` (`UserId` ASC) ,
  INDEX `UserLocationVisitLocationId_idx` (`LocationId` ASC) ,
  CONSTRAINT `UserLocationVisitUserId`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserLocationVisitLocationId`
    FOREIGN KEY (`LocationId` )
    REFERENCES `core`.`Location` (`LocationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`UserLocationPassBy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`UserLocationPassBy` ;

CREATE  TABLE IF NOT EXISTS `core`.`UserLocationPassBy` (
  `UserLocationPassById` INT NOT NULL AUTO_INCREMENT ,
  `UserId` INT NOT NULL ,
  `LocationId` INT NOT NULL ,
  `PassByCount` INT NOT NULL DEFAULT 1 ,
  `LastPassBy` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`UserLocationPassById`) ,
  INDEX `UserLocationPassByUserId_idx` (`UserId` ASC) ,
  INDEX `UserLocationPassByLocationId_idx` (`LocationId` ASC) ,
  CONSTRAINT `UserLocationPassByUserId`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserLocationPassByLocationId`
    FOREIGN KEY (`LocationId` )
    REFERENCES `core`.`Location` (`LocationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`UserTileVisit`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`UserTileVisit` ;

CREATE  TABLE IF NOT EXISTS `core`.`UserTileVisit` (
  `UserTileVisitId` INT NOT NULL AUTO_INCREMENT ,
  `UserId` INT NOT NULL ,
  `TileId` INT NOT NULL ,
  `VisitCount` INT NOT NULL DEFAULT 1 ,
  `LastVisited` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`UserTileVisitId`) ,
  INDEX `UserTileVisitUserId_idx` (`UserId` ASC) ,
  INDEX `UserTileVisitTileId_idx` (`TileId` ASC) ,
  CONSTRAINT `UserTileVisitUserId`
    FOREIGN KEY (`UserId` )
    REFERENCES `core`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserTileVisitTileId`
    FOREIGN KEY (`TileId` )
    REFERENCES `core`.`Tile` (`TileId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Zipcode`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Zipcode` ;

CREATE  TABLE IF NOT EXISTS `core`.`Zipcode` (
  `ZipcodeId` INT NOT NULL AUTO_INCREMENT ,
  `Zipcode` VARCHAR(10) NOT NULL ,
  `ZipcodeCity` VARCHAR(100) NOT NULL ,
  `ZipcodeState` VARCHAR(2) NOT NULL ,
  PRIMARY KEY (`ZipcodeId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`Day`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`Day` ;

CREATE  TABLE IF NOT EXISTS `core`.`Day` (
  `DayId` INT NOT NULL ,
  `DayName` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`DayId`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `core`.`LocationHours`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `core`.`LocationHours` ;

CREATE  TABLE IF NOT EXISTS `core`.`LocationHours` (
  `LocationHoursId` INT NOT NULL AUTO_INCREMENT ,
  `LocationId` INT NOT NULL ,
  `DayId` INT NOT NULL ,
  `OpenTime` VARCHAR(10) NOT NULL ,
  `CloseTime` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`LocationHoursId`) ,
  INDEX `DayId_idx` (`DayId` ASC) ,
  INDEX `LocationId_idx` (`LocationId` ASC) ,
  CONSTRAINT `DayId`
    FOREIGN KEY (`DayId` )
    REFERENCES `core`.`Day` (`DayId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `LocationId`
    FOREIGN KEY (`LocationId` )
    REFERENCES `core`.`Location` (`LocationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `core` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `core`.`Gender`
-- -----------------------------------------------------
START TRANSACTION;
USE `core`;
INSERT INTO `core`.`Gender` (`GenderId`, `GenderName`) VALUES (1, 'Male');
INSERT INTO `core`.`Gender` (`GenderId`, `GenderName`) VALUES (2, 'Female');

COMMIT;

-- -----------------------------------------------------
-- Data for table `core`.`DeviceType`
-- -----------------------------------------------------
START TRANSACTION;
USE `core`;
INSERT INTO `core`.`DeviceType` (`DeviceTypeId`, `DeviceTypeName`) VALUES (1, 'Smartphone');
INSERT INTO `core`.`DeviceType` (`DeviceTypeId`, `DeviceTypeName`) VALUES (2, 'Tablet');
INSERT INTO `core`.`DeviceType` (`DeviceTypeId`, `DeviceTypeName`) VALUES (3, 'Laptop');
INSERT INTO `core`.`DeviceType` (`DeviceTypeId`, `DeviceTypeName`) VALUES (4, 'Phablet');

COMMIT;

-- -----------------------------------------------------
-- Data for table `core`.`Day`
-- -----------------------------------------------------
START TRANSACTION;
USE `core`;
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (1, 'Monday');
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (2, 'Tuesday');
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (3, 'Wednesday');
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (4, 'Thursday');
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (5, 'Friday');
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (6, 'Saturday');
INSERT INTO `core`.`Day` (`DayId`, `DayName`) VALUES (7, 'Sunday');

COMMIT;
