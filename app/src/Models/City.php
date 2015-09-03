<?php
/**
 * City Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\CityRepository")
 * @Table(name="city")
 */
class City extends Entity
{
    /**
     * @var int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="city_id")
     */
    protected $id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="city_name")
     */
    protected $name;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="city_created_at")
     */
    protected $created_at;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="integer", name="city_created_by")
     */
    protected $created_by;

    /**
     * @var District[]
     * @since 1.0
     *
     * @OneToMany(targetEntity="EBloodBank\Models\District", mappedBy="city")
     */
    protected $districts = array();

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'id':
            case 'created_by':
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'name':
            case 'created_at':
                $value = trim($value);
                break;
        }
        return $value;
    }

    /**
     * @throws \EBloodBank\Exceptions\InvalidArgument
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! isValidID($value)) {
                    throw new InvalidArgument(__('Invalid city ID.'), 'Invalid_city_id');
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid city name.'), 'Invalid_city_name');
                }
                break;
            case 'created_at':
                // TODO: Checks the validity of DATETIME.
                break;
            case 'created_by':
                if (! isValidID($value)) {
                    throw new InvalidArgument(__('Invalid city user.'), 'Invalid_city_user');
                }
                break;
        }
        return true;
    }
}
