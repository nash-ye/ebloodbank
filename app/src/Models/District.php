<?php
namespace eBloodBank\Models;

use eBloodBank\Kernal\Model;

/**
 * @since 1.0
 */
class District extends Model
{
    /**
     * @var string
     * @since 1.0
     */
    const TABLE = 'district';

    /**
     * @var string
     * @since 1.0
     */
    const PK_ATTR = 'distr_id';

    /**
     * @var int
     * @since 1.0
     */
    public $distr_id = 0;

    /**
     * @var string
     * @since 1.0
     */
    public $distr_name;

    /**
     * @var int
     * @since 1.0
     */
    public $distr_city_id = 0;
}
