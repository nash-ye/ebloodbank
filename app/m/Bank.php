<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Bank extends Model {

	use Model_Meta;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	const TABLE = 'bank';

	/**
	 * @var string
	 * @since 0.5.4
	 */
	const PK_ATTR = 'bank_id';

	/**
	 * @var string
	 * @since 0.5.4
	 */
	const META_TABLE = 'bank_meta';

	/**
	 * @var string
	 * @since 0.5.4
	 */
	const META_FK_ATTR = 'bank_id';

	/**
	 * @var int
	 * @since 0.5.4
	 */
	public $bank_id = 0;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $bank_name;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $bank_phone;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $bank_email;

	/**
	 * @var int
	 * @since 0.5.4
	 */
	public $bank_distr_id;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $bank_address;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $bank_rtime;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $bank_status = 'pending';

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public function get_city_name() {

		try {

			global $db;

			$stmt = $db->prepare( 'SELECT city_name FROM city WHERE city_id IN( SELECT distr_city_id FROM district WHERE distr_id = ?)', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( array( (int) $this->get( 'bank_distr_id' ) ) );

			$city_name = $stmt->fetchColumn();
			$stmt->closeCursor();

			return $city_name;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public function get_district_name() {

		try {

			global $db;

			$stmt = $db->prepare( 'SELECT distr_name FROM district WHERE distr_id = ?', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( array( (int) $this->get( 'bank_distr_id' ) ) );

			$distr_name = $stmt->fetchColumn();
			$stmt->closeCursor();

			return $distr_name;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

}