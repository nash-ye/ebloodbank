<?php
/**
 * District Model
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
     * @ManyToOne(targetEntity="EBloodBank\Models\City")
     * @JoinColumn(name="district_city_id", referencedColumnName="city_id")
     */
    protected $city;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="district_created_at")
     */
    protected $created_at;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="integer", name="district_created_by")
     */
    protected $created_by;

    /**
     * @var Donor[]
     * @since 1.0
     *
     * @OneToMany(targetEntity="EBloodBank\Models\Donor", mappedBy="district")
     */
    protected $donors = array();

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
            case 'city':
                if (isValidID($value)) {
                    $em = main()->getEntityManager();
                    $value = $em->find('Entities:City', $value);
                }
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
                    throw new InvalidArgument(__('Invalid district ID.'), 'Invalid_district_id');
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid district name.'), 'Invalid_district_name');
                }
                break;
            case 'city':
                if (! $value instanceof City || ! $value->isExists()) {
                    throw new InvalidArgument(__('Invalid district city.'), 'Invalid_district_city');
                }
                break;
            case 'created_at':
                // TODO: Checks the validity of DATETIME.
                break;
            case 'created_by':
                if (! isValidID($value)) {
                    throw new InvalidArgument(__('Invalid district user.'), 'Invalid_district_user');
                }
                break;
        }
        return true;
    }
}
