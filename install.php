<?php

require 'config.php';
require 'loader.php';

// Create Tables.

$db->exec(
'
-- -----------------------------------------------------
-- Table `city`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `city` (
  `city_id` INT NOT NULL AUTO_INCREMENT ,
  `city_name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`city_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `district`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `district` (
  `distr_id` INT NOT NULL ,
  `distr_name` VARCHAR(255) NOT NULL ,
  `distr_city_id` INT NOT NULL ,
  PRIMARY KEY (`distr_id`) ,
  CONSTRAINT `district_city_id`
    FOREIGN KEY (`distr_city_id` )
    REFERENCES `city` (`city_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `dis_idx` ON `district` (`distr_city_id` ASC) ;


-- -----------------------------------------------------
-- Table `donor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donor` (
  `donor_id` INT NOT NULL AUTO_INCREMENT ,
  `donor_name` VARCHAR(255) NOT NULL ,
  `donor_gender` VARCHAR(45) NOT NULL ,
  `donor_weight` SMALLINT NOT NULL ,
  `donor_birthdate` DATE NOT NULL ,
  `donor_blood_group` VARCHAR(45) NOT NULL ,
  `donor_distr_id` INT NOT NULL ,
  `donor_phone` VARCHAR(50) NOT NULL ,
  `donor_email` VARCHAR(100) NULL ,
  `donor_rtime` DATETIME NOT NULL ,
  `donor_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`donor_id`) ,
  CONSTRAINT `donor_district_id`
    FOREIGN KEY (`donor_distr_id` )
    REFERENCES `district` (`distr_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `donor_district_id_idx` ON `donor` (`donor_distr_id` ASC) ;


-- -----------------------------------------------------
-- Table `donor_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donor_meta` (
  `meta_id` INT NOT NULL AUTO_INCREMENT ,
  `donor_id` INT NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `dm_donor_id`
    FOREIGN KEY (`donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `dm_donor_id_idx` ON `donor_meta` (`donor_id` ASC) ;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_logon` VARCHAR(100) NOT NULL ,
  `user_pass` VARCHAR(100) NOT NULL ,
  `user_role` VARCHAR(45) NOT NULL ,
  `user_rtime` DATETIME NOT NULL ,
  `user_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_logon_UNIQUE` ON `user` (`user_logon` ASC) ;


-- -----------------------------------------------------
-- Table `user_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_meta` (
  `meta_id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `um_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `um_user_id_idx` ON `user_meta` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `test_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `test_type` (
  `tt_id` INT NOT NULL AUTO_INCREMENT ,
  `tt_title` VARCHAR(255) NOT NULL ,
  `tt_priority` INT NOT NULL DEFAULT 10 ,
  PRIMARY KEY (`tt_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `donor_test`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donor_test` (
  `test_id` INT NOT NULL AUTO_INCREMENT ,
  `test_time` DATETIME NOT NULL ,
  `test_type_id` INT NOT NULL ,
  `test_donor_id` INT NOT NULL ,
  `test_document` BLOB NOT NULL ,
  `test_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`test_id`) ,
  CONSTRAINT `dt_donor_id`
    FOREIGN KEY (`test_donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `dt_type_id`
    FOREIGN KEY (`test_type_id` )
    REFERENCES `test_type` (`tt_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `dt_donor_id_idx` ON `donor_test` (`test_donor_id` ASC) ;

CREATE INDEX `dt_type_id_idx` ON `donor_test` (`test_type_id` ASC) ;


-- -----------------------------------------------------
-- Table `donation`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donation` (
  `donat_id` INT NOT NULL ,
  `donat_time` DATETIME NOT NULL ,
  `donat_purpose` VARCHAR(255) NULL ,
  `donat_donor_id` INT NOT NULL ,
  PRIMARY KEY (`donat_id`) ,
  CONSTRAINT `donat_donor_id`
    FOREIGN KEY (`donat_donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `donat_donor_id_idx` ON `donation` (`donat_donor_id` ASC) ;
'
		);

echo 'Done!';