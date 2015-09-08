SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user` (
  `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(255) NOT NULL ,
  `user_email` VARCHAR(100) NOT NULL ,
  `user_pass` VARCHAR(128) NOT NULL ,
  `user_role` VARCHAR(45) NOT NULL ,
  `user_created_at` DATETIME NOT NULL ,
  `user_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_email_UNIQUE` ON `user` (`user_email` ASC) ;


-- -----------------------------------------------------
-- Table `city`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `city` (
  `city_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `city_name` VARCHAR(255) NOT NULL ,
  `city_created_at` DATETIME NOT NULL ,
  `city_created_by` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`city_id`) ,
  CONSTRAINT `city_created_by`
    FOREIGN KEY (`city_created_by` )
    REFERENCES `user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `city_created_by_idx` ON `city` (`city_created_by` ASC) ;

CREATE UNIQUE INDEX `city_name_UNIQUE` ON `city` (`city_name` ASC) ;


-- -----------------------------------------------------
-- Table `district`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `district` (
  `district_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `district_name` VARCHAR(255) NOT NULL ,
  `district_city_id` INT UNSIGNED NOT NULL ,
  `district_created_at` DATETIME NOT NULL ,
  `district_created_by` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`district_id`) ,
  CONSTRAINT `district_city_id`
    FOREIGN KEY (`district_city_id` )
    REFERENCES `city` (`city_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `district_created_by`
    FOREIGN KEY (`district_created_by` )
    REFERENCES `user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `district_city_id_idx` ON `district` (`district_city_id` ASC) ;

CREATE INDEX `district_created_by_idx` ON `district` (`district_created_by` ASC) ;

CREATE UNIQUE INDEX `district_name_UNIQUE` ON `district` (`district_name` ASC, `district_city_id` ASC) ;


-- -----------------------------------------------------
-- Table `donor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donor` (
  `donor_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `donor_name` VARCHAR(255) NOT NULL ,
  `donor_gender` VARCHAR(45) NOT NULL ,
  `donor_birthdate` DATE NOT NULL ,
  `donor_blood_group` VARCHAR(45) NOT NULL ,
  `donor_district_id` INT UNSIGNED NOT NULL ,
  `donor_created_at` DATETIME NOT NULL ,
  `donor_created_by` INT UNSIGNED NOT NULL ,
  `donor_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`donor_id`) ,
  CONSTRAINT `donor_district_id`
    FOREIGN KEY (`donor_district_id` )
    REFERENCES `district` (`district_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `donor_created_by`
    FOREIGN KEY (`donor_created_by` )
    REFERENCES `user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `donor_district_id_idx` ON `donor` (`donor_district_id` ASC) ;

CREATE INDEX `donor_created_by_idx` ON `donor` (`donor_created_by` ASC) ;


-- -----------------------------------------------------
-- Table `donor_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donor_meta` (
  `meta_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `donor_id` INT UNSIGNED NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `dm_donor_id`
    FOREIGN KEY (`donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `dm_donor_id_idx` ON `donor_meta` (`donor_id` ASC) ;


-- -----------------------------------------------------
-- Table `user_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_meta` (
  `meta_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `um_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `um_user_id_idx` ON `user_meta` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `variable`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `variable` (
  `variable_name` VARCHAR(45) NOT NULL ,
  `variable_value` LONGTEXT NOT NULL ,
  PRIMARY KEY (`variable_name`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
