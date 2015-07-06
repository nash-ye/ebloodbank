<?php

namespace eBloodBank;

require 'config.php';
require 'bootstrap.php';

/**
 * @return bool
 * @since 0.6
 */
function create_database() {

	global $db;

	if ( ! defined( 'DB_NAME' ) ) {
		return FALSE;
	}

	try {

		$sql_create_db = sprintf( 'CREATE DATABASE IF NOT EXISTS `%s`', DB_NAME );
		$sql_create_db = ' CHARACTER SET utf8 COLLATE utf8_general_ci;';

		$db->exec( $sql_create_db );

	} catch ( \PDOException $ex ) {
		return FALSE;
	}

	return TRUE;

}

/**
 * @return bool
 * @since 0.6
 */
function create_tables() {

	global $db;

	try {

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
		  `donor_address` VARCHAR(255) NOT NULL ,
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
		'
				);

	} catch ( \PDOException $ex ) {
		return FALSE;
	}

	return TRUE;

}


/**
 * @return bool
 * @since 0.6
 */
function insert_user_admin() {

	$user_id = Users::insert( array(
		'user_logon' => 'admin',
		'user_pass' => password_hash( 'admin', PASSWORD_BCRYPT ),
		'user_role' => 'administrator',
	) );

	return ( ! empty( $user_id ) && isVaildID( $user_id ) );

}

create_database();
create_tables();
insert_user_admin();

redirect( getSiteURL() );