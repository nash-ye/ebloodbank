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