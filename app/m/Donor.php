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