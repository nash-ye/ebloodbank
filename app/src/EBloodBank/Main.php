<?php
/**
 * Main class file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use Monolog;
use Gettext;
use Doctrine\ORM;
use Doctrine\DBAL;
use Swift_Mailer;
use Swift_SmtpTransport;
use Aura\Router\RouterFactory;
use Aura\Dispatcher\Dispatcher;
use Aura\Session\SessionFactory;

/**
 * Main class
 *
 * @since 1.0
 */
class Main
{
    /**
     * The main status.
     *
     * @var string
     * @since 1.0
     */
    protected $status;

    /**
     * The logger.
     *
     * @var \Monolog\Logger
     * @since 1.0
     */
    protected $logger;

    /**
     * The router.
     *
     * @var \Aura\Router\Router
     * @since 1.0
     */
    protected $router;

    /**
     * The session.
     *
     * @var \Aura\Session\Session
     * @since 1.0.1
     */
    protected $session;

    /**
     * The translator.
     *
     * @var \Gettext\Translator
     * @since 1.0
     */
    protected $translator;

    /**
     * The database connection.
     *
     * @var \Doctrine\DBAL\Connection
     * @since 1.0
     */
    protected $DBConnection;

    /**
     * The entity manager.
     *
     * @var \Doctrine\ORM\EntityManager
     * @since 1.0
     */
    protected $entityManager;

    /**
     * The dispatcher.
     *
     * @var \Aura\Dispatcher\Dispatcher
     * @since 1.0
     */
    protected $dispatcher;

    /**
     * The mailer.
     *
     * @var \Swift_Mailer
     * @since 1.1
     */
    protected $mailer;

    /**
     * @access private
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @access private
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
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupTranslator()
    {
        $this->translator = new Gettext\Translator();
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
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupDBConnection()
    {
        $mysqlDriver = extension_loaded('pdo_mysql') ? 'pdo_mysql' : 'mysqli';

        $this->DBConnection = DBAL\DriverManager::getConnection([
            'dbname'    => EBB_DB_NAME,
            'user'      => EBB_DB_USER,
            'password'  => EBB_DB_PASS,
            'host'      => EBB_DB_HOST,
            'driver'    => $mysqlDriver,
            'charset'   => 'utf8',
        ]);

        tryDatabaseConnection($this->DBConnection); // Try to establish the database connection.
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
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupEntityManager()
    {
        $config = ORM\Tools\Setup::createConfiguration((bool) EBB_DEV_MODE);

        $entitiesPaths = array(trimTrailingSlash(EBB_APP_DIR) . '/src/EBloodBank/Models/');
        $driverImpl = $config->newDefaultAnnotationDriver($entitiesPaths, true);
        $config->setMetadataDriverImpl($driverImpl);

        $config->addEntityNamespace('Entities', 'EBloodBank\Models');

        $config->setProxyDir(trimTrailingSlash(EBB_APP_DIR) . '/src/EBloodBank/Proxies/');
        $config->setProxyNamespace('EBloodBank\Proxies');
        $config->setAutoGenerateProxyClasses(true);

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
    public function checkInstallation()
    {
        $connection = $this->getDBConnection();

        if (! isDatabaseSelected($connection)) {
            $this->status = 'database_not_selected';
        } elseif (! isDatabaseConnected($connection)) {
            $this->status = 'database_not_connected';
        } elseif (! isAllTablesExists($connection)) {
            $this->status = 'some_tables_not_exists';
        } else {
            $this->status = 'installed';
        }
    }

    /**
     * @access private
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
        $this->router->add('delete-donor', '/delete/donor/{id}/');
        $this->router->add('approve-donor', '/approve/donor/{id}/');

        $this->router->add('view-users', '/users/');
        $this->router->add('add-user', '/add/user/');
        $this->router->add('view-user', '/user/{id}/');
        $this->router->add('edit-users', '/edit/users/');
        $this->router->add('edit-user', '/edit/user/{id}/');
        $this->router->add('delete-user', '/delete/user/{id}/');
        $this->router->add('activate-user', '/activate/user/{id}/');

        $this->router->add('view-cities', '/cities/');
        $this->router->add('add-city', '/add/city/');
        $this->router->add('view-city', '/city/{id}/');
        $this->router->add('edit-cities', '/edit/cities/');
        $this->router->add('edit-city', '/edit/city/{id}/');
        $this->router->add('delete-city', '/delete/city/{id}/');

        $this->router->add('view-districts', '/districts/');
        $this->router->add('add-district', '/add/district/');
        $this->router->add('view-district', '/district/{id}/');
        $this->router->add('edit-districts', '/edit/districts/');
        $this->router->add('edit-district', '/edit/district/{id}/');
        $this->router->add('delete-district', '/delete/district/{id}/');

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
     * @access private
     * @return void
     * @since 1.1
     */
    private function setupMailer()
    {
        $transport = Swift_SmtpTransport::newInstance();
        $this->mailer = Swift_Mailer::newInstance($transport);
    }

    /**
     * @return \Swift_Mailer
     * @since 1.0
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupCurrentLocale()
    {
        $locales = Locales::getAvailableLocales();

        $defaultLocale = EBB_DEFAULT_LOCALE;
        if (! empty($defaultLocale) && isset($locales[$defaultLocale])) {
            Locales::setDefaultLocale($locales[$defaultLocale]);
        }

        $siteLocale = Options::getOption('site_locale');
        if (! empty($siteLocale) && isset($locales[$siteLocale])) {
            Locales::setCurrentLocale($locales[$siteLocale]);
        }

        $currentLocale = Locales::getCurrentLocale();

        if (! empty($currentLocale)) {
            $this->getTranslator()->loadTranslations($currentLocale->getTranslations());
        }
    }

    /**
     * @access private
     * @return void
     * @since 1.0.1
     */
    private function setupSession()
    {
        $sessionFactory = new SessionFactory();
        $this->session = $sessionFactory->newInstance($_COOKIE);
        if (! $this->session->isStarted()) {
            $this->session->setName('EBB_SESSION_ID');
            $this->session->setCookieParams([
                'lifetime' => 3600,
                'path'     => parse_url(getHomeURL(), PHP_URL_PATH),
                'domain'   => parse_url(getHomeURL(), PHP_URL_HOST),
                'secure'   => isHTTPS(),
                'httponly' => true,
            ]);
            $this->session->start();
        }
    }

    /**
     * @return \Aura\Session\Session
     * @since 1.0.1
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @access private
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

        )));

        Roles::addRole(new Role('contributor', __('Contributor'), array(

            // Donors
            'add_donor'             => true,
            'view_donors'           => true,

            // Cities
            'view_cities'           => true,

            // Districts
            'view_districts'        => true,

        )));

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

        )));

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

        )));
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    public function setupDispatcher()
    {
        $controllers = [
            'home' => function () {
                return new Controllers\Home();
            },
            'login' => function () {
                return new Controllers\Login();
            },
            'signup' => function () {
                return new Controllers\Signup();
            },
            'install' => function () {
                return new Controllers\Install();
            },
            'settings' => function () {
                return new Controllers\Settings();
            },
            'view-users' => function () {
                return new Controllers\ViewUsers();
            },
            'view-donors' => function () {
                return new Controllers\ViewDonors();
            },
            'view-cities' => function () {
                return new Controllers\ViewCities();
            },
            'view-districts' => function () {
                return new Controllers\ViewDistricts();
            },
            'add-user' => function () {
                return new Controllers\AddUser();
            },
            'add-donor' => function () {
                return new Controllers\AddDonor();
            },
            'add-city' => function () {
                return new Controllers\AddCity();
            },
            'add-district' => function () {
                return new Controllers\AddDistrict();
            },
            'edit-user' => function ($id) {
                return new Controllers\EditUser($id);
            },
            'edit-donor' => function ($id) {
                return new Controllers\EditDonor($id);
            },
            'edit-city' => function ($id) {
                return new Controllers\EditCity($id);
            },
            'edit-district' => function ($id) {
                return new Controllers\EditDistrict($id);
            },
            'delete-user' => function ($id) {
                return new Controllers\DeleteUser($id);
            },
            'delete-donor' => function ($id) {
                return new Controllers\DeleteDonor($id);
            },
            'delete-city' => function ($id) {
                return new Controllers\DeleteCity($id);
            },
            'delete-district' => function ($id) {
                return new Controllers\DeleteDistrict($id);
            },
            'activate-user' => function ($id) {
                return new Controllers\ActivateUser($id);
            },
            'approve-donor' => function ($id) {
                return new Controllers\ApproveDonor($id);
            },
            'edit-users' => function () {
                return new Controllers\EditUsers();
            },
            'edit-donors' => function () {
                return new Controllers\EditDonors();
            },
            'edit-cities' => function () {
                return new Controllers\EditCities();
            },
            'edit-districts' => function () {
                return new Controllers\EditDistricts();
            },
        ];

        $this->dispatcher = new Dispatcher($controllers);
    }

    /**
     * @return \Aura\Dispatcher\Dispatcher
     * @since 1.0
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function dispatch()
    {
        $dispatcher = $this->getDispatcher();
        if (isInstaller()) {
            $dispatcher([], 'install');
        } else {
            switch ($this->getStatus()) {
                case 'database_not_selected':
                case 'some_tables_not_exists':
                    redirect(getInstallerURL());
                    break;

                case 'database_not_connected':
                    Views\View::display('error-db');
                    break;

                default:
                    $matchedRoute = $this->getRouter()->getMatchedRoute();
                    if (empty($matchedRoute)) {
                        Views\View::display('error-404');
                    } else {
                        try {
                            $dispatcher($matchedRoute->params, $matchedRoute->name);
                        } catch (\Aura\Dispatcher\Exception\ObjectNotDefined $ex) {
                            Views\View::display('error-404');
                        }
                    }
                    break;
            }
        }
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getStatus()
    {
        return $this->status;
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

            // Sets up the translator.
            $instance->setupTranslator();

            // Sets up the DB connection.
            $instance->setupDBConnection();

            // Sets up the ORM entity manager.
            $instance->setupEntityManager();

            // Checks the installation status.
            $instance->checkInstallation();

            // Sets up the current locale.
            $instance->setupCurrentLocale();

            // Sets up the mailer.
            $instance->setupMailer();

            // Sets up the user roles.
            $instance->setupUserRoles();

            // Sets up the session.
            $instance->setupSession();

            // Sets up the router.
            $instance->setupRouter();

            // Sets up the dispatcher.
            $instance->setupDispatcher();

            // Dispatch!
            $instance->dispatch();
        }

        return $instance;
    }
}
