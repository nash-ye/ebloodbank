<?php
namespace eBloodBank\Models;

use eBloodBank\Kernal\Model;
use eBloodBank\EntityManager;
use eBloodBank\Exceptions\InvaildProperty;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="eBloodBank\Models\DistrictRepository")
 * @Table(name="district")
 */
class District extends Model
{
    /**
     * @var int
     * @since 1.0
     *
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $distr_id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $distr_name;

    /**
     * @var int
     * @since 1.0
     *
     * @Column(type="integer")
     * @ManyToOne(targetEntity="eBloodBank\Models\City")
     */
    protected $distr_city_id = 0;

    /**
     * @return City
     * @since 1.0
     */
    public function getParentCity()
    {
        $cityRepository = EntityManager::getCityRepository();
        return $cityRepository->find((int) $this->get('distr_city_id'));
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'distr_id':
            case 'distr_city_id':
                $value = (int) $value;
                break;
            case 'distr_name':
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;
        }
        return $value;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'distr_id':
                if (! isVaildID($value)) {
                    throw new InvaildProperty(__('Invaild district ID'), 'invaild_distr_id');
                }
                break;
            case 'distr_name':
                if (empty($value) || ! is_string($value)) {
                    throw new InvaildProperty(__('Invaild district name'), 'invaild_distr_name');
                }
                break;
            case 'distr_city_id':
                if (! isVaildID($value)) {
                    throw new InvaildProperty(__('Invaild district city ID'), 'invaild_distr_city_id');
                }
                break;
        }
        return true;
    }
}
