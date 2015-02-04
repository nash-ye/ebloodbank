<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_Type extends Model {

	/**
	 * @var string
	 * @since 0.5.3
	 */
	const TABLE = 'test_type';

	/**
	 * @var string
	 * @since 0.5.3
	 */
	const PK_ATTR = 'tt_id';

	/**
	 * @var int
	 * @since 0.5.3
	 */
	public $tt_id = 0;

	/**
	 * @var string
	 * @since 0.5.3
	 */
	public $tt_title;

	/**
	 * @var int
	 * @since 0.5.3
	 */
	public $tt_priority = 10;

}