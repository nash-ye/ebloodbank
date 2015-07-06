<?php
namespace eBloodBank\Models;

use eBloodBank\Kernal\Model;

/**
 * @since 1.0
 */
class City extends Model
{
    /**
     * @var string
     * @since 1.0
     */
    const TABLE = 'city';

    /**
     * @var string
     * @since 1.0
     */
    const PK_ATTR = 'city_id';

    /**
     * @var int
     * @since 1.0
     */
    public $city_id = 0;

    /**
     * @var string
     * @since 1.0
     */
    public $city_name;
}
