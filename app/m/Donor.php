<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class Donor extends Model {

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $donor_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $donor_name;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $donor_gender;

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $donor_weight = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $donor_birthdate;

	/**
	 * @var string
	 * @since 0.2
	 */
	protected $donor_blood_group;

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $donor_distr_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $donor_phone;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $donor_email;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $donor_rtime;

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $donor_status = 0;

}

/**
 * @since 0.4.2
 */
class Donor_Meta extends Model_Meta {

	/**
	 * @var string
	 * @since 0.4.2
	 */
	const TABLE = 'donor_meta';

	/**
	 * @var string
	 * @since 0.4.2
	 */
	const FK_ATTR = 'donor_id';

}