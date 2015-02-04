<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class Donor extends Model {

	use Model_Meta;

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const TABLE = 'donor';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const PK_ATTR = 'donor_id';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const META_TABLE = 'donor_meta';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const META_FK_ATTR = 'donor_id';

	/**
	 * @var int
	 * @since 0.1
	 */
	public $donor_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_name;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_gender;

	/**
	 * @var int
	 * @since 0.1
	 */
	public $donor_weight = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_birthdate;

	/**
	 * @var string
	 * @since 0.2
	 */
	public $donor_blood_group;

	/**
	 * @var int
	 * @since 0.1
	 */
	public $donor_distr_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_phone;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_email;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_rtime;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $donor_status = 'pending';

	/**
	 * @var array
	 * @since 0.5.1
	 */
	public static $genders = array(
		'male'   => 'ذكر',
		'female' => 'أنثى',
	);

	/**
	 * @var array
	 * @since 0.5.1
	 */
	public static $blood_groups = array(
		'-O',
		'+O',
		'-A',
		'+A',
		'-B',
		'+B',
		'-AB',
		'+AB',
	);

	/**
	 * @var int
	 * @since 0.5.1
	 */
	public function get_age() {

		$current_date = new \DateTime( date( 'Y-m-d' ) );
		$birthdate = new \DateTime( $this->get( 'donor_birthdate' ) );

		if ( $birthdate > $current_date ) {
			return FALSE;
		}

		return (int) $current_date->diff( $birthdate )->format( '%y' );

	}

	/**
	 * @var string
	 * @since 0.5.1
	 */
	public function get_city_name() {

		try {

			global $db;

			$stmt = $db->prepare( 'SELECT city_name FROM city WHERE city_id IN( SELECT distr_city_id FROM district WHERE distr_id = ?)', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( array( (int) $this->get( 'donor_distr_id' ) ) );

			$city_name = $stmt->fetchColumn();
			$stmt->closeCursor();

			return $city_name;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @var string
	 * @since 0.5.1
	 */
	public function get_district_name() {

		try {

			global $db;

			$stmt = $db->prepare( 'SELECT distr_name FROM district WHERE distr_id = ?', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( array( (int) $this->get( 'donor_distr_id' ) ) );

			$distr_name = $stmt->fetchColumn();
			$stmt->closeCursor();

			return $distr_name;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @var string
	 * @since 0.5.1
	 */
	public function get_gender_label() {

		$gender = $this->get( 'donor_gender' );

		if ( isset( self::$genders[ $gender ] ) ) {
			return self::$genders[ $gender ];
		}

	}

	/**
	 * @var bool
	 * @since 0.4.6
	 */
	public function is_approved() {
		return 'approved' === $this->get( 'donor_status' );
	}

	/**
	 * @var bool
	 * @since 0.4.6
	 */
	public function is_pending() {
		return 'pending' === $this->get( 'donor_status' );
	}

}