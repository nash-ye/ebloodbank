<?php
/**
 * Entity Manager
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

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
     * @static
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
     * @return \EBloodBank\Models\CityRepository
     * @since 1.0
     * @static
     */
    public static function getCityRepository()
    {
        return self::getInstance()->getRepository('EBloodBank\Models\City');
    }

    /**
     * @return \EBloodBank\Models\DistrictRepository
     * @since 1.0
     * @static
     */
    public static function getDistrictRepository()
    {
        return self::getInstance()->getRepository('EBloodBank\Models\District');
    }

    /**
     * @return \EBloodBank\Models\DonorRepository
     * @since 1.0
     * @static
     */
    public static function getDonorRepository()
    {
        return self::getInstance()->getRepository('EBloodBank\Models\Donor');
    }

    /**
     * @return \EBloodBank\Models\CityRepository
     * @since 1.0
     * @static
     */
    public static function getUserRepository()
    {
        return self::getInstance()->getRepository('EBloodBank\Models\User');
    }

    /**
     * @return \EBloodBank\Models\City
     * @since 1.0
     * @static
     */
    public static function getCityReference($id)
    {
        return self::getInstance()->getReference('EBloodBank\Models\City', $id);
    }

    /**
     * @return \EBloodBank\Models\District
     * @since 1.0
     * @static
     */
    public static function getDistrictReference($id)
    {
        return self::getInstance()->getReference('EBloodBank\Models\District', $id);
    }

    /**
     * @return \EBloodBank\Models\Donor
     * @since 1.0
     * @static
     */
    public static function getDonorReference($id)
    {
        return self::getInstance()->getReference('EBloodBank\Models\Donor', $id);
    }

    /**
     * @return \EBloodBank\Models\User
     * @since 1.0
     * @static
     */
    public static function getUserReference($id)
    {
        return self::getInstance()->getReference('EBloodBank\Models\User', $id);
    }
}
