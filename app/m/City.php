<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class City extends Model {

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const TABLE = 'city';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const PK_ATTR = 'city_id';

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $city_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $city_name;

}