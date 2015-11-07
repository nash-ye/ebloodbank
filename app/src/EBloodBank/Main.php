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
use Aura\Di\Factory;
use Aura\Di\Container;
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
     * The main container.
     *
     * @var \Aura\Di\Container
     * @since 1.2
     */
    protected $container;

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
     * @since 1.2
     */
    private function setupContainer()
    {
        $this->container = new Container(new Factory());
    }

    /**
     * @return \Aura\Di\Container
     * @since 1.2
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupLogger()
    {
        $logger = new Monolog\Logger('Main Logger');
        $this->getContainer()->set('logger', $logger);

        if (EBB_DEV_MODE) {
            $debugHandler = new Monolog\Handler\StreamHandler(EBB_APP_DIR . '/debug.log', Monolog\Logger::DEBUG);
            $logger->pushHandler($debugHandler);
        }

        $warningsHandler = new Monolog\Handler\StreamHandler(EBB_APP_DIR . '/warnings.log', Monolog\Logger::WARNING);
        $logger->pushHandler($warningsHandler);

        // Register the logger as an exception handler, error handler and fatal error handler.
        Monolog\ErrorHandler::register($logger);
    }

    /**
     * @return \Monolog\Logger
     * @since 1.0
     */
    public function getLogger()
    {
        return $this->getContainer()->get('logger');
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupTranslator()
    {
        $translator = new Gettext\Translator();
        $translator->register();

        $this->getContainer()->set('translator', $translator);
    }

    /**
     * @return \Gettext\Translator
     * @since 1.0
     */
    public function getTranslator()
    {
        return $this->getContainer()->get('translator');
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupDBConnection()
    {
        $mysqlDriver = extension_loaded('pdo_mysql') ? 'pdo_mysql' : 'mysqli';

        $DBConnection = DBAL\DriverManager::getConnection([
            'dbname'    => EBB_DB_NAME,
            'user'      => EBB_DB_USER,
            'password'  => EBB_DB_PASS,
            'host'      => EBB_DB_HOST,
            'driver'    => $mysqlDriver,
            'charset'   => 'utf8',
        ]);

        $this->getContainer()->set('db_connection', $DBConnection);

        tryDatabaseConnection($DBConnection); // Try to establish the database connection.
    }

    /**
     * @return \Doctrine\DBAL\Connection
     * @since 1.0
     */
    public function getDBConnection()
    {
        return $this->getContainer()->get('db_connection');
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupEntityManager()
    {
        $config = ORM\Tools\Setup::createConfiguration((bool) EBB_DEV_MODE);

        $entitiesPaths = [trimTrailingSlash(EBB_APP_DIR) . '/src/EBloodBank/Models/'];
        $driverImpl = $config->newDefaultAnnotationDriver($entitiesPaths, true);
        $config->setMetadataDriverImpl($driverImpl);

        $config->addEntityNamespace('Entities', 'EBloodBank\Models');

        $config->setProxyDir(trimTrailingSlash(EBB_APP_DIR) . '/src/EBloodBank/Proxies/');
        $config->setProxyNamespace('EBloodBank\Proxies');
        $config->setAutoGenerateProxyClasses(true);

        $entityManager = ORM\EntityManager::create($this->getDBConnection(), $config);

        $eventManager = $entityManager->getEventManager();
        $eventManager->addEventListener(['postLoad'], new Models\EntityEventListener());

        $this->getContainer()->set('entity_manager', $entityManager);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     * @since 1.0
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('entity_manager');
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
        $router = $routerFactory->newInstance();

        $router->add('home', '/');
        $router->add('login', '/login/');
        $router->add('logout', '/logout/');
        $router->add('signup', '/signup/');

        $router->add('settings', '/settings/');

        $router->add('view-donors', '/donors/');
        $router->add('add-donor', '/add/donor/');
        $router->add('view-donor', '/donor/{id}/');
        $router->add('view-donor', '/donor/{id}/');
        $router->add('edit-donors', '/edit/donors/');
        $router->add('edit-donor', '/edit/donor/{id}/');
        $router->add('delete-donors', '/delete/donors/');
        $router->add('delete-donor', '/delete/donor/{id}/');
        $router->add('approve-donors', '/approve/donors/');
        $router->add('approve-donor', '/approve/donor/{id}/');

        $router->add('view-users', '/users/');
        $router->add('add-user', '/add/user/');
        $router->add('view-user', '/user/{id}/');
        $router->add('edit-users', '/edit/users/');
        $router->add('edit-user', '/edit/user/{id}/');
        $router->add('delete-users', '/delete/users/');
        $router->add('delete-user', '/delete/user/{id}/');
        $router->add('activate-users', '/activate/users/');
        $router->add('activate-user', '/activate/user/{id}/');

        $router->add('view-cities', '/cities/');
        $router->add('add-city', '/add/city/');
        $router->add('view-city', '/city/{id}/');
        $router->add('edit-cities', '/edit/cities/');
        $router->add('edit-city', '/edit/city/{id}/');
        $router->add('delete-cities', '/delete/cities/');
        $router->add('delete-city', '/delete/city/{id}/');

        $router->add('view-districts', '/districts/');
        $router->add('add-district', '/add/district/');
        $router->add('view-district', '/district/{id}/');
        $router->add('edit-districts', '/edit/districts/');
        $router->add('edit-district', '/edit/district/{id}/');
        $router->add('delete-districts', '/delete/districts/');
        $router->add('delete-district', '/delete/district/{id}/');

        $router->match(
            trimTrailingSlash(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) . '/',
            $_SERVER
        );

        $this->getContainer()->set('router', $router);
    }

    /**
     * @return \Aura\Router\Router
     * @since 1.0
     */
    public function getRouter()
    {
        return $this->getContainer()->get('router');
    }

    /**
     * @access private
     * @return void
     * @since 1.1
     */
    private function setupMailer()
    {
        $transport = Swift_SmtpTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);
        $this->getContainer()->set('mailer', $mailer);
    }

    /**
     * @return \Swift_Mailer
     * @since 1.0
     */
    public function getMailer()
    {
        return $this->getContainer()->get('mailer');
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
        $session = $sessionFactory->newInstance($_COOKIE);
        $this->getContainer()->set('session', $session);

        if (! $session->isStarted()) {
            $session->setName('EBB_SESSION_ID');
            $session->setCookieParams([
                'lifetime' => 3600,
                'path'     => parse_url(getHomeURL(), PHP_URL_PATH),
                'domain'   => parse_url(getHomeURL(), PHP_URL_HOST),
                'secure'   => isHTTPS(),
                'httponly' => true,
            ]);
            $session->start();
        }
    }

    /**
     * @return \Aura\Session\Session
     * @since 1.0.1
     */
    public function getSession()
    {
        return $this->getContainer()->get('session');
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupUserRoles()
    {
        Roles::addRole(new Role('subscriber', __('Subscriber'), [

            // Donors
            'view_donors'           => true,

            // Cities
            'view_cities'           => true,

            // Districts
            'view_districts'        => true,

        ]));

        Roles::addRole(new Role('contributor', __('Contributor'), [

            // Donors
            'add_donor'             => true,
            'edit_donors'           => true,
            'view_donors'           => true,
            'delete_donors'         => true,

            // Cities
            'view_cities'           => true,

            // Districts
            'view_districts'        => true,

        ]));

        Roles::addRole(new Role('editor', __('Editor'), [

            // Users
            'view_users'            => true,

            // Donors
            'add_donor'             => true,
            'edit_donors'           => true,
            'view_donors'           => true,
            'delete_donors'         => true,
            'approve_donors'        => true,
            'edit_others_donors'    => true,
            'delete_others_donors'  => true,

            // Cities
            'add_city'              => true,
            'edit_cities'           => true,
            'view_cities'           => true,
            'delete_cities'         => true,

            // Districts
            'add_district'          => true,
            'edit_districts'        => true,
            'view_districts'        => true,
            'delete_districts'      => true,

        ]));

        Roles::addRole(new Role('administrator', __('Administrator'), [

            // Users
            'add_user'              => true,
            'edit_users'            => true,
            'view_users'            => true,
            'delete_users'          => true,
            'activate_users'        => true,

            // Donors
            'add_donor'             => true,
            'edit_donors'           => true,
            'view_donors'           => true,
            'delete_donors'         => true,
            'approve_donors'        => true,
            'edit_others_donors'    => true,
            'delete_others_donors'  => true,

            // Cities
            'add_city'              => true,
            'edit_cities'           => true,
            'view_cities'           => true,
            'delete_cities'         => true,

            // Districts
            'add_district'          => true,
            'edit_districts'        => true,
            'view_districts'        => true,
            'delete_districts'      => true,

            // Settings
            'edit_settings'         => true,

        ]));
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    public function setupDispatcher()
    {
        $container = $this->getContainer();
        $controllers = [
            'home' => function () use ($container) {
                return new Controllers\Home($container);
            },
            'login' => function () use ($container) {
                return new Controllers\Login($container);
            },
            'signup' => function () use ($container) {
                return new Controllers\Signup($container);
            },
            'install' => function () use ($container) {
                return new Controllers\Install($container);
            },
            'settings' => function () use ($container) {
                return new Controllers\Settings($container);
            },
            'view-users' => function () use ($container) {
                return new Controllers\ViewUsers($container);
            },
            'view-donors' => function () use ($container) {
                return new Controllers\ViewDonors($container);
            },
            'view-cities' => function () use ($container) {
                return new Controllers\ViewCities($container);
            },
            'view-districts' => function () use ($container) {
                return new Controllers\ViewDistricts($container);
            },
            'view-donor' => function ($id) use ($container) {
                return new Controllers\ViewDonor($container, $id);
            },
            'add-user' => function () use ($container) {
                return new Controllers\AddUser($container);
            },
            'add-donor' => function () use ($container) {
                return new Controllers\AddDonor($container);
            },
            'add-city' => function () use ($container) {
                return new Controllers\AddCity($container);
            },
            'add-district' => function () use ($container) {
                return new Controllers\AddDistrict($container);
            },
            'edit-user' => function ($id) use ($container) {
                return new Controllers\EditUser($container, $id);
            },
            'edit-donor' => function ($id) use ($container) {
                return new Controllers\EditDonor($container, $id);
            },
            'edit-city' => function ($id) use ($container) {
                return new Controllers\EditCity($container, $id);
            },
            'edit-district' => function ($id) use ($container) {
                return new Controllers\EditDistrict($container, $id);
            },
            'delete-user' => function ($id) use ($container) {
                return new Controllers\DeleteUser($container, $id);
            },
            'delete-donor' => function ($id) use ($container) {
                return new Controllers\DeleteDonor($container, $id);
            },
            'delete-city' => function ($id) use ($container) {
                return new Controllers\DeleteCity($container, $id);
            },
            'delete-district' => function ($id) use ($container) {
                return new Controllers\DeleteDistrict($container, $id);
            },
            'activate-user' => function ($id) use ($container) {
                return new Controllers\ActivateUser($container, $id);
            },
            'approve-donor' => function ($id) use ($container) {
                return new Controllers\ApproveDonor($container, $id);
            },
            'edit-users' => function () use ($container) {
                return new Controllers\EditUsers($container);
            },
            'edit-donors' => function () use ($container) {
                return new Controllers\EditDonors($container);
            },
            'edit-cities' => function () use ($container) {
                return new Controllers\EditCities($container);
            },
            'edit-districts' => function () use ($container) {
                return new Controllers\EditDistricts($container);
            },
            'delete-users' => function () use ($container) {
                return new Controllers\DeleteUsers($container);
            },
            'delete-donors' => function () use ($container) {
                return new Controllers\DeleteDonors($container);
            },
            'delete-cities' => function () use ($container) {
                return new Controllers\DeleteCities($container);
            },
            'delete-districts' => function () use ($container) {
                return new Controllers\DeleteDistricts($container);
            },
            'activate-users' => function () use ($container) {
                return new Controllers\ActivateUsers($container);
            },
            'approve-donors' => function () use ($container) {
                return new Controllers\ApproveDonors($container);
            },
        ];

        $dispatcher = new Dispatcher($controllers);
        $this->getContainer()->set('dispatcher', $dispatcher);
    }

    /**
     * @return \Aura\Dispatcher\Dispatcher
     * @since 1.0
     */
    public function getDispatcher()
    {
        return $this->getContainer()->get('dispatcher');
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
            switch (getInstallationStatus($this->getDBConnection())) {
                case DATABASE_NOT_SELECTED:
                case DATABASE_TABLE_NOT_EXIST:
                    redirect(getInstallerURL());
                    break;

                case DATABASE_NOT_CONNECTED:
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

            // Sets up the container.
            $instance->setupContainer();

            // Sets up the logger.
            $instance->setupLogger();

            // Sets up the translator.
            $instance->setupTranslator();

            // Sets up the DB connection.
            $instance->setupDBConnection();

            // Sets up the ORM entity manager.
            $instance->setupEntityManager();

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
            if (! isCLI()) {
                $instance->dispatch();
            }
        }

        return $instance;
    }
}
