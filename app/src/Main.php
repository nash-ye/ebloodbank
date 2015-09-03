<?php
/**
 * EBloodBank Main Class
 *
 * @package EBloodBank
 * @since 1.0
 */

namespace EBloodBank;

use Doctrine\ORM;
use Doctrine\DBAL;
use Aura\Router\RouterFactory;

/**
 * @since 1.0
 */
class Main
{
    /**
     * @var \Aura\Router\Router
     * @since 1.0
     */
    protected $router;

    /**
     * @var \Doctrine\DBAL\Connection
     * @since 1.0
     */
    protected $DBConnection;

    /**
     * @var \Doctrine\ORM\EntityManager
     * @since 1.0
     */
    protected $entityManager;

    /**
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupRouter()
    {
        $basepath = getHomeURL('relative');

        $routerFactory = new RouterFactory($basepath);
        $this->router = $routerFactory->newInstance();

        $this->router->add('home', '/');
        $this->router->add('login', '/login/');
        $this->router->add('logout', '/logout/');
        $this->router->add('signup', '/signup/');

        $this->router->add('view-donors', '/donors/');
        $this->router->add('add-donor', '/add/donor/');
        $this->router->add('view-donor', '/donor/{id}/');
        $this->router->add('edit-donors', '/edit/donors/');
        $this->router->add('edit-donor', '/edit/donor/{id}/');

        $this->router->add('view-users', '/users/');
        $this->router->add('add-user', '/add/user/');
        $this->router->add('view-user', '/user/{id}/');
        $this->router->add('edit-users', '/edit/users/');
        $this->router->add('edit-user', '/edit/user/{id}/');

        $this->router->add('view-cities', '/cities/');
        $this->router->add('add-city', '/add/city/');
        $this->router->add('view-city', '/city/{id}/');
        $this->router->add('edit-cities', '/edit/cities/');
        $this->router->add('edit-city', '/edit/city/{id}/');

        $this->router->add('view-districts', '/districts/');
        $this->router->add('add-district', '/add/district/');
        $this->router->add('view-district', '/district/{id}/');
        $this->router->add('edit-districts', '/edit/districts/');
        $this->router->add('edit-district', '/edit/district/{id}/');

        $this->router->match(
            trimTrailingSlash(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) . '/',
            $_SERVER
        );

    }

    /**
     * @return \Aura\Router\Router
     * @since 1.0
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupDBConnection()
    {
        try {

            $this->DBConnection = DBAL\DriverManager::getConnection(array(
                'dbname'    => EBB_DB_NAME,
                'user'      => EBB_DB_USER,
                'password'  => EBB_DB_PASS,
                'host'      => EBB_DB_HOST,
                'driver'    => 'pdo_mysql',
                'charset'   => 'utf8',
            ));

            $this->DBConnection->connect(); // Establishes the connection.

        } catch (\Exception $ex) {
            die('Error establishing a database connection.');
        }
    }

    /**
     * @return \Doctrine\DBAL\Connection
     * @since 1.0
     */
    public function getDBConnection()
    {
        return $this->DBConnection;
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupEntityManager()
    {
        try {

            $config = ORM\Tools\Setup::createConfiguration(EBB_DEV_MODE);

            $entityPaths = array(trimTrailingSlash(EBB_DIR) . '/app/src/Models/');
            $driverImpl = $config->newDefaultAnnotationDriver($entityPaths, true);
            $config->setMetadataDriverImpl($driverImpl);

            $config->addEntityNamespace('Entities', 'EBloodBank\Models');

            $this->entityManager = ORM\EntityManager::create(self::getDBConnection(), $config);

        } catch (\Exception $ex) {
            die('Error establishing a database connection.');
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     * @since 1.0
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

	/** Singleton *************************************************************/

    /**
     * @return Main
     * @since 1.0
     * @static
     */
    public static function getInstance()
    {
        static $instance;

        if (is_null($instance)) {

            $instance = new self();

            // Setup the router.
            $instance->setupRouter();

            // Setup the DB connection.
            $instance->setupDBConnection();

            // Setup the ORM entity manager.
            $instance->setupEntityManager();

        }

        return $instance;
    }
}
