SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
CREATE SCHEMA IF NOT EXISTS `shelfspace` DEFAULT CHARACTER SET latin1 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `shelfspace`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `shelfspace`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NULL DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` TEXT NOT NULL,
  `admin` TINYINT(4) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 40
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `shelfspace`.`items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `shelfspace`.`items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `item` TEXT NOT NULL,
  `status` INT(1) NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `users_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `users_id`),
  INDEX `fk_items_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_items_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `shelfspace`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `mydb`.`images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image` LONGBLOB NULL,
  `created_at` TIMESTAMP NULL,
  `items_id` INT(11) NOT NULL,
  `items_users_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `items_id`, `items_users_id`),
  INDEX `fk_images_items_idx` (`items_id` ASC, `items_users_id` ASC),
  CONSTRAINT `fk_images_items`
    FOREIGN KEY (`items_id` , `items_users_id`)
    REFERENCES `shelfspace`.`items` (`id` , `users_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `shelfspace` ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
