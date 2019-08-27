<?php
/**
 * District entity class file
 *
 * @package    EBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Models;

use InvalidArgumentException;
use EBloodBank as EBB;

/**
 * District entity class
 *
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\DistrictRepository")
 * @Table(name="district")
 */
class District extends Entity
{
    /**
     * @var int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="district_id")
     */
    protected $id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="district_name")
     */
    protected $name;

    /**
     * @var City
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\City", inversedBy="districts")
     * @JoinColumn(name="district_city_id", referencedColumnName="city_id")
     */
    protected $city;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="datetime", name="district_created_at")
     */
    protected $created_at;

    /**
     * @var User
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\User")
     * @JoinColumn(name="district_created_by", referencedColumnName="user_id")
     */
    protected $created_by;

    /**
     * @var Donor[]
     * @since 1.0
     *
     * @OneToMany(targetEntity="EBloodBank\Models\Donor", mappedBy="district")
     */
    protected $donors = [];

    /**
     * @return bool
     * @since 1.0
     */
    public function isExists()
    {
        $id = (int) $this->get('id');
        return ! empty($id);
    }

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'id':
                $value = EBB\sanitizeInteger($value);
                break;
            case 'name':
                $value = EBB\sanitizeTitle($value);
                break;
            case 'city':
                break;
            case 'created_at':
                break;
            case 'created_by':
                break;
        }
        return $value;
    }

    /**
     * @throws \InvalidArgumentException
     * @return bool
     * @since 1.0
     * @static
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! EBB\isValidID($value)) {
                    throw new InvalidArgumentException(__('Invalid district ID.'));
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid district name.'));
                }
                break;
            case 'city':
                if (! $value instanceof City || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid district city.'));
                }
                break;
            case 'created_at':
                break;
            case 'created_by':
                if (! $value instanceof User || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid district originator.'));
                }
                break;
        }
        return true;
    }
}
