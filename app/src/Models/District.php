<?php
/**
 * District Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions\InvaildArgument;

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
     * @Column(type="integer", name="distr_id")
     */
    protected $id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="distr_name")
     */
    protected $name;

    /**
     * @var City
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\City")
     * @JoinColumn(name="distr_city_id", referencedColumnName="city_id")
     */
    protected $city;

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
                $value = (int) $value;
                break;
            case 'name':
                $value = trim(filter_var($value, FILTER_SANITIZE_STRING));
                break;
            case 'city':
                if (is_numeric($value)) {
                    $value = EntityManager::getCityRepository()->find($value);
                }
                break;
        }
        return $value;
    }

    /**
     * @throws \EBloodBank\Exceptions\InvaildArgument
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! isVaildID($value)) {
                    throw new InvaildArgument(__('Invaild district ID.'), 'invaild_distr_id');
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvaildArgument(__('Invaild district name.'), 'invaild_distr_name');
                }
                break;
            case 'city':
                if (! $value instanceof City || ! isVaildID($value->get('id'))) {
                    throw new InvaildArgument(__('Invaild district city object.'), 'invaild_distr_city');
                }
                break;
        }
        return true;
    }
}
