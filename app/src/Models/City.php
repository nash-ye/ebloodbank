<?php
namespace eBloodBank\Models;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\Model;
use eBloodBank\Exceptions\InvaildProperty;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="eBloodBank\Models\CityRepository")
 * @Table(name="city")
 */
class City extends Model
{
    /**
     * @var int
     * @since 1.0
     *
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $city_id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $city_name;

    /**
     * @return District[]
     * @since 1.0
     */
    public function getChildDistricts($orderBy = null, $limit = null, $offset = null)
    {
        $cityID = (int) $this->get('city_id');
        $districtRepository = EntityManager::getDistrictRepository();
        return $districtRepository->findByCityID($cityID, $orderBy, $limit, $offset);
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'city_id':
                $value = (int) $value;
                break;
            case 'city_name':
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;
        }
        return $value;
    }

    /**
     * @throws \eBloodBank\Exceptions\InvaildProperty
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'city_id':
                if (! isVaildID($value)) {
                    throw new InvaildProperty(__('Invaild City ID'), 'invaild_city_id');
                }
                break;
            case 'city_name':
                if (empty($value) || ! is_string($value)) {
                    throw new InvaildProperty(__('Invaild City name'), 'invaild_city_name');
                }
                break;
        }
        return true;
    }
}
