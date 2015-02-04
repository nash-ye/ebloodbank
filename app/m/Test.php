<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test extends Model {

	/**
	 * @var string
	 * @since 0.5.3
	 */
	const TABLE = 'test';

	/**
	 * @var string
	 * @since 0.5.3
	 */
	const PK_ATTR = 'test_id';

	/**
	 * @var int
	 * @since 0.5.3
	 */
	public $test_id = 0;

	/**
	 * @var string
	 * @since 0.5.3
	 */
	public $test_date;

	/**
	 * @var int
	 * @since 0.5.3
	 */
	public $test_type_id = 0;

	/**
	 * @var int
	 * @since 0.5.3
	 */
	public $test_donor_id = 0;

	/**
	 * @var
	 * @since 0.5.3
	 */
	public $test_document;

	/**
	 * @var string
	 * @since 0.5.3
	 */
	public $test_status = 'pending';

}