<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class District extends Model {

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const TABLE = 'district';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const PK_ATTR = 'distr_id';

	/**
	 * @var int
	 * @since 0.1
	 */
	public $distr_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $distr_name;

	/**
	 * @var int
	 * @since 0.1
	 */
	public $distr_city_id = 0;

}