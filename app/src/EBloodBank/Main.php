<?php
/**
 * EBloodBank Main Class
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

use Monolog;
use Gettext;
use Doctrine\ORM;
use Doctrine\DBAL;
use Aura\Router\RouterFactory;

/**
 * @since 1.0
 */
class Main
{
    /**
     * @var \Monolog\Logger
     * @since 1.0
     */
    protected $logger;

    /**
     * @var \Aura\Router\Router
     * @since 1.0
     */
    protected $router;

    /**
     * @var \Gettext\Translator
     * @since 1.0
     */
    protected $translator;

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
    private function setupLogger()
    {
        $this->logger = new Monolog\Logger('Main Logger');

        if (EBB_DEV_MODE) {
            $debugHandler = new Monolog\Handler\StreamHandler(EBB_APP_DIR . '/debug.log', Monolog\Logger::DEBUG);
            $this->logger->pushHandler($debugHandler);
        }

        $warningsHandler = new Monolog\Handler\StreamHandler(EBB_APP_DIR . '/warnings.log', Monolog\Logger::WARNING);
        $this->logger->pushHandler($warningsHandler);

        // Register the logger as an exception handler, error handler and fatal error handler.
        Monolog\ErrorHandler::register($this->logger);
    }

    /**
     * @return \Monolog\Logger
     * @since 1.0
     */
    public function getLogger()
    {
        return $this->logger;
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

        $this->router->add('settings', '/settings/');

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
    private function setupTranslator()
    {
        $this->translator = NEW Gettext\Translator();
        $this->translator->register();
    }

    /**
     * @return \Gettext\Translator
     * @since 1.0
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupDBConnection()
    {
        $this->DBConnection = DBAL\DriverManager::getConnection(array(
            'dbname'    => EBB_DB_NAME,
            'user'      => EBB_DB_USER,
            'password'  => EBB_DB_PASS,
            'host'      => EBB_DB_HOST,
            'driver'    => 'pdo_mysql',
            'charset'   => 'utf8',
        ));
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
        $config = ORM\Tools\Setup::createConfiguration((bool) EBB_DEV_MODE);
        $entitiesPaths = array(trimTrailingSlash(EBB_APP_DIR) . '/src/Models/');
        $driverImpl = $config->newDefaultAnnotationDriver($entitiesPaths, true);
        $config->addEntityNamespace('Entities', 'EBloodBank\Models');
        $config->setMetadataDriverImpl($driverImpl);

        $this->entityManager = ORM\EntityManager::create($this->getDBConnection(), $config);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     * @since 1.0
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return void
     * @since 1.0
     */
    private function checkInstallation()
    {
        $connection = $this->getDBConnection();

        if (! isDatabaseSelected($connection)) {
            if (! isInstaller()) {
                redirect(getInstallerURL());
            }
        } elseif (! isDatabaseConnected($connection)) {
            Views\View::display('error-db');
            die();
        } elseif (! isAllTablesExists($connection)) {
            if (! isInstaller()) {
                redirect(getInstallerURL());
            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupCurrentLocale()
    {
        $locales = Locales::getAvailableLocales();

        if (isset($locales[EBB_DEFAULT_LOCALE])) {
            Locales::setDefaultLocale($locales[EBB_DEFAULT_LOCALE]);
        }

        if (! isInstaller()) {
            $siteLocale = Options::getOption('site_locale');
            if (! empty($siteLocale) && isset($locales[$siteLocale])) {
                Locales::setCurrentLocale($locales[$siteLocale]);
            }
        }

        $currentLocale = Locales::getCurrentLocale();

        if (! empty($currentLocale)) {
            $this->getTranslator()->loadTranslations($currentLocale->getTranslations());

        }
    }

    /**
     * @return Locale
     * @since 1.0
     */
    private function getCurrentLocale()
    {
        return Locales::getCurrentLocale();
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupUserSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(
                    3600,
                    parse_url(getHomeURL(), PHP_URL_PATH),
                    parse_url(getHomeURL(), PHP_URL_HOST),
                    isHTTPS(),
                    true
            );
            session_name('EBB_SESSION_ID');
            session_start();
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    private function setupUserRoles()
    {
        Roles::addRole(new Role('subscriber', __('Subscriber'), array(

            // Donors
            'view_donors'           => true,

            // Cities
            'view_cities'           => true,

            // Districts
            'view_districts'        => true,

        ) ) );

        Roles::addRole(new Role('contributor', __('Contributor'), array(

            // Donors
            'add_donor'             => true,
            'view_donors'           => true,

            // Cities
            'view_cities'           => true,

            // Districts
            'view_districts'        => true,

        ) ) );

        Roles::addRole(new Role('editor', __('Editor'), array(

            // Users
            'view_users'            => true,

            // Donors
            'add_donor'             => true,
            'edit_donor'            => true,
            'edit_donors'           => true,
            'view_donors'           => true,
            'delete_donor'          => true,
            'approve_donor'         => true,

            // Cities
            'add_city'              => true,
            'edit_city'             => true,
            'edit_cities'           => true,
            'view_cities'           => true,
            'delete_city'           => true,

            // Districts
            'add_district'          => true,
            'edit_district'         => true,
            'edit_districts'        => true,
            'view_districts'        => true,
            'delete_district'       => true,

        ) ) );

        Roles::addRole(new Role('administrator', __('Administrator'), array(

            // Users
            'add_user'              => true,
            'edit_user'             => true,
            'edit_users'            => true,
            'view_users'            => true,
            'delete_user'           => true,
            'activate_user'         => true,

            // Donors
            'add_donor'             => true,
            'edit_donor'            => true,
            'edit_donors'           => true,
            'view_donors'           => true,
            'delete_donor'          => true,
            'approve_donor'         => true,

            // Cities
            'add_city'              => true,
            'edit_city'             => true,
            'edit_cities'           => true,
            'view_cities'           => true,
            'delete_city'           => true,

            // Districts
            'add_district'          => true,
            'edit_district'         => true,
            'edit_districts'        => true,
            'view_districts'        => true,
            'delete_district'       => true,

            // Settings
            'edit_settings'         => true,

        ) ) );
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

            // Sets up the logger.
            $instance->setupLogger();

            // Sets up the router.
            $instance->setupRouter();

            // Sets up the translator.
            $instance->setupTranslator();

            // Sets up the DB connection.
            $instance->setupDBConnection();

            // Sets up the ORM entity manager.
            $instance->setupEntityManager();

            $instance->checkInstallation();

            // Sets up the current locale.
            $instance->setupCurrentLocale();

            // Sets up the user session.
            $instance->setupUserSession();

            // Sets up the user roles.
            $instance->setupUserRoles();

        }

        return $instance;
    }
}
