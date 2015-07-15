<?php
namespace eBloodBank;

use Doctrine\ORM;

/**
 * @since 1.0
 */
class EntityManager
{

    /**
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     * @since 1.0
     */
    public static function getInstance()
    {
        static $em;

        if (is_null($em)) {

            $paths = array(ABSPATH . '/app/src/Models/');
            $config = ORM\Tools\Setup::createConfiguration(DEBUG_MODE);
            $driverImpl = $config->newDefaultAnnotationDriver($paths);
            $config->setMetadataDriverImpl($driverImpl);

            $em = ORM\EntityManager::create(array(
                'dbname'    => DB_NAME,
                'user'      => DB_USER,
                'password'  => DB_PASS,
                'host'      => DB_HOST,
                'driver'    => 'pdo_mysql',
                'charset'   => 'utf8',
            ), $config);

        }

        return $em;
    }

    /**
     * @return \eBloodBank\Models\CityRepository
     * @since 1.0
     */
    public static function getCityRepository()
    {
        return self::getInstance()->getRepository('eBloodBank\Models\City');
    }

    /**
     * @return \eBloodBank\Models\DistrictRepository
     * @since 1.0
     */
    public static function getDistrictRepository()
    {
        return self::getInstance()->getRepository('eBloodBank\Models\District');
    }

    /**
     * @return \eBloodBank\Models\DonorRepository
     * @since 1.0
     */
    public static function getDonorRepository()
    {
        return self::getInstance()->getRepository('eBloodBank\Models\Donor');
    }

    /**
     * @return \eBloodBank\Models\CityRepository
     * @since 1.0
     */
    public static function getUserRepository()
    {
        return self::getInstance()->getRepository('eBloodBank\Models\User');
    }

    /**
     * @return \eBloodBank\Models\City
     * @since 1.0
     */
    public static function getCityReference($id)
    {
        return self::getInstance()->getReference('eBloodBank\Models\User', $id);
    }

    /**
     * @return \eBloodBank\Models\District
     * @since 1.0
     */
    public static function getDistrictReference($id)
    {
        return self::getInstance()->getReference('eBloodBank\Models\District', $id);
    }

    /**
     * @return \eBloodBank\Models\Donor
     * @since 1.0
     */
    public static function getDonorReference($id)
    {
        return self::getInstance()->getReference('eBloodBank\Models\Donor', $id);
    }

    /**
     * @return \eBloodBank\Models\User
     * @since 1.0
     */
    public static function getUserReference($id)
    {
        return self::getInstance()->getReference('eBloodBank\Models\User', $id);
    }
}
