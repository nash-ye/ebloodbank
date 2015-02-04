<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Stock extends Model {

	/**
	 * @var string
	 * @since 0.5.4
	 */
	const TABLE = 'stock';

	/**
	 * @var string
	 * @since 0.5.4
	 */
	const PK_ATTR = 'stock_id';

	/**
	 * @var int
	 * @since 0.5.4
	 */
	public $stock_id = 0;

	/**
	 * @var int
	 * @since 0.5.4
	 */
	public $stock_bank_id;

	/**
	 * @var sting
	 * @since 0.5.4
	 */
	public $stock_blood_group;

	/**
	 * @var int
	 * @since 0.5.4
	 */
	public $stock_quantity;

	/**
	 * @var string
	 * @since 0.5.4
	 */
	public $stock_status = 'available';

	/**
	 * @var array
	 * @since 0.541
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

}