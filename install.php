<?php

namespace eBloodBank;

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
  `distr_id` INT NOT NULL AUTO_INCREMENT ,
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
  `donar_address` VARCHAR(255) NOT NULL ,
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
-- Table `donation`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `donation` (
  `donat_id` INT NOT NULL AUTO_INCREMENT ,
  `donat_amount` INT NULL ,
  `donat_purpose` VARCHAR(255) NULL ,
  `donat_donor_id` INT NOT NULL ,
  `donat_date` DATE NULL ,
  PRIMARY KEY (`donat_id`) ,
  CONSTRAINT `donat_donor_id`
    FOREIGN KEY (`donat_donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `donat_donor_id_idx` ON `donation` (`donat_donor_id` ASC) ;


-- -----------------------------------------------------
-- Table `bank`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `bank` (
  `bank_id` INT NOT NULL AUTO_INCREMENT ,
  `bank_name` VARCHAR(255) NOT NULL ,
  `bank_phone` VARCHAR(50) NOT NULL ,
  `bank_email` VARCHAR(100) NULL ,
  `bank_distr_id` INT NOT NULL ,
  `bank_address` VARCHAR(255) NOT NULL ,
  `bank_rtime` DATETIME NOT NULL ,
  `bank_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`bank_id`) ,
  CONSTRAINT `bank_district_id`
    FOREIGN KEY (`bank_distr_id` )
    REFERENCES `district` (`distr_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `bank_district_id_idx` ON `bank` (`bank_distr_id` ASC) ;


-- -----------------------------------------------------
-- Table `bank_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `bank_meta` (
  `meta_id` INT NOT NULL AUTO_INCREMENT ,
  `bank_id` INT NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `bm_bank_id`
    FOREIGN KEY (`bank_id` )
    REFERENCES `bank` (`bank_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `bm_bank_id_idx` ON `bank_meta` (`bank_id` ASC) ;


-- -----------------------------------------------------
-- Table `stock`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `stock` (
  `stock_id` INT NOT NULL AUTO_INCREMENT ,
  `stock_bank_id` INT NOT NULL ,
  `stock_blood_group` VARCHAR(45) NOT NULL ,
  `stock_quantity` INT NOT NULL ,
  `stock_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`stock_id`) ,
  CONSTRAINT `stock_bank_id`
    FOREIGN KEY (`stock_bank_id` )
    REFERENCES `bank` (`bank_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `stock_bank_id_idx` ON `stock` (`stock_bank_id` ASC) ;
'
		);

Users::insert( array(
	'user_logon' => 'admin',
	'user_pass' => password_hash( 'admin', PASSWORD_BCRYPT ),
	'user_role' => 'administrator',
) );

echo 'Done!';