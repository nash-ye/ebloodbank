<?php
/**
 * Main class file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use Redis;
use Monolog;
use Gettext;
use Doctrine;
use Doctrine\ORM;
use Doctrine\DBAL;
use Swift_Mailer;
use Swift_SmtpTransport;
use Aura\Di\Factory;
use Aura\Di\Container;
use Aura\Dispatcher\Dispatcher;
use Aura\Router\RouterContainer;
use Aura\Session\SessionFactory;
use Zend\Diactoros\ServerRequestFactory;

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
     * @since 1.3
     */
    private function setupServerRequest()
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $this->getContainer()->set('request', $request);
    }

    /**
     * @return \Zend\Diactoros\ServerRequest
     * @since 1.3
     */
    public function getServerRequest()
    {
        return $this->getContainer()->get('request');
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
        $DBConnection = DBAL\DriverManager::getConnection([
            'dbname'    => EBB_DB_NAME,
            'user'      => EBB_DB_USER,
            'password'  => EBB_DB_PASS,
            'host'      => EBB_DB_HOST,
            'driver'    => EBB_DB_DRIVER,
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
        $config->addEntityNamespace('Entities', 'EBloodBank\Models');
        $config->setMetadataDriverImpl($driverImpl);

        $config->setProxyDir(trimTrailingSlash(EBB_APP_DIR) . '/src/EBloodBank/Proxies/');
        $config->setAutoGenerateProxyClasses((bool) EBB_DEV_MODE);
        $config->setProxyNamespace('EBloodBank\Proxies');

        if (! EBB_DEV_MODE && EBB_REDIS_CACHE && extension_loaded('redis')) {
            $redis = new Redis();
            $redis->connect(EBB_REDIS_HOST, EBB_REDIS_PORT);
            if (EBB_REDIS_PASS !== '') {
                $redis->auth(EBB_REDIS_PASS);
            }
            if (EBB_REDIS_DB !== '') {
                $redis->select(EBB_REDIS_DB);
            }
            $cacheDriver = new Doctrine\Common\Cache\RedisCache();
            $cacheDriver->setRedis($redis);
        } elseif (! EBB_DEV_MODE && EBB_APC_CACHE && extension_loaded('apc')) {
            $cacheDriver = new Doctrine\Common\Cache\ApcCache();
        } else {
            $cacheDriver = new Doctrine\Common\Cache\ArrayCache();
        }

        $config->setMetadataCacheImpl($cacheDriver);
        $config->setResultCacheImpl($cacheDriver);
        $config->setQueryCacheImpl($cacheDriver);

        $entityManager = ORM\EntityManager::create($this->getDBConnection(), $config);

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

        $routerContainer = new RouterContainer($basepath);
        $this->getContainer()->set('router', $routerContainer);

        $routerMap = $routerContainer->getMap();

        /*
         * The application accepts the trailing slash for now
         * but this behavior may be changed in the near future.
         *
         * Please use one version of URLs in your content and templates.
         */

        $routerMap->route('home', '(/)?');
        $routerMap->route('login', '/login(/)?');
        $routerMap->route('logout', '/logout(/)?');
        $routerMap->route('signup', '/signup(/)?');

        $routerMap->route('settings', '/settings(/)?');

        $routerMap->route('view-donors', '/donors(/)?');
        $routerMap->route('add-donor', '/add/donor(/)?');
        $routerMap->route('view-donor', '/donor/{id}(/)?');
        $routerMap->route('edit-donors', '/edit/donors(/)?');
        $routerMap->route('edit-donor', '/edit/donor/{id}(/)?');
        $routerMap->route('delete-donors', '/delete/donors(/)?');
        $routerMap->route('delete-donor', '/delete/donor/{id}(/)?');
        $routerMap->route('approve-donors', '/approve/donors(/)?');
        $routerMap->route('approve-donor', '/approve/donor/{id}(/)?');

        $routerMap->route('view-users', '/users(/)?');
        $routerMap->route('add-user', '/add/user(/)?');
        $routerMap->route('view-user', '/user/{id}(/)?');
        $routerMap->route('edit-users', '/edit/users(/)?');
        $routerMap->route('edit-user', '/edit/user/{id}(/)?');
        $routerMap->route('delete-users', '/delete/users(/)?');
        $routerMap->route('delete-user', '/delete/user/{id}(/)?');
        $routerMap->route('activate-users', '/activate/users(/)?');
        $routerMap->route('activate-user', '/activate/user/{id}(/)?');

        $routerMap->route('view-cities', '/cities(/)?');
        $routerMap->route('add-city', '/add/city(/)?');
        $routerMap->route('view-city', '/city/{id}(/)?');
        $routerMap->route('edit-cities', '/edit/cities(/)?');
        $routerMap->route('edit-city', '/edit/city/{id}(/)?');
        $routerMap->route('delete-cities', '/delete/cities(/)?');
        $routerMap->route('delete-city', '/delete/city/{id}(/)?');

        $routerMap->route('view-districts', '/districts(/)?');
        $routerMap->route('add-district', '/add/district(/)?');
        $routerMap->route('view-district', '/district/{id}(/)?');
        $routerMap->route('edit-districts', '/edit/districts(/)?');
        $routerMap->route('edit-district', '/edit/district/{id}(/)?');
        $routerMap->route('delete-districts', '/delete/districts(/)?');
        $routerMap->route('delete-district', '/delete/district/{id}(/)?');

        $routerMatcher = $routerContainer->getMatcher();
        $routerMatcher->match($this->getServerRequest());
    }

    /**
     * @return \Aura\Router\RouterContainer
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
        $defaultLocale = Locales::findLocale(EBB_DEFAULT_LOCALE);
        if (! empty($defaultLocale)) {
            Locales::setDefaultLocale($defaultLocale);
        }

        $siteLocale = Locales::findLocale(Options::getOption('site_locale'));
        if (! empty($siteLocale)) {
            Locales::setCurrentLocale($siteLocale);
        }

        $currentLocale = Locales::getCurrentLocale();

        if (! empty($currentLocale)) {
            $this->getTranslator()->loadTranslations($currentLocale->getTranslations());
        }
    }

    /**
     * @access private
     * @return void
     * @since 1.3
     */
    private function setupCurrentTheme()
    {
        $defaultTheme = Themes::findTheme(EBB_DEFAULT_THEME);
        if (! empty($defaultTheme)) {
            Themes::setDefaultTheme($defaultTheme);
        }

        $siteTheme = Themes::findTheme(Options::getOption('site_theme'));
        if (! empty($siteTheme)) {
            Themes::setCurrentTheme($siteTheme);
        }

        $currentLocale = Locales::getCurrentLocale();

        if (! empty($currentLocale)) {
            $currentTheme = Themes::getCurrentTheme();
            $themeLocale = $currentTheme->findLocale($currentLocale->getCode());
            if (! empty($themeLocale)) {
                $themeTranslations = $themeLocale->getTranslations();
                $themeDomain = $currentTheme->getData('Textdomain', $currentTheme->getName());
                $themeTranslations->setDomain($themeDomain);
                $this->getTranslator()->loadTranslations($themeTranslations);
            }
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
                    $matchedRoute = $this->getRouter()->getMatcher()->getMatchedRoute();
                    if (empty($matchedRoute)) {
                        Views\View::display('error-404');
                    } else {
                        try {
                            $dispatcher($matchedRoute->attributes, $matchedRoute->name);
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

            // Sets up the server request.
            $instance->setupServerRequest();

            // Sets up the translator.
            $instance->setupTranslator();

            // Sets up the DB connection.
            $instance->setupDBConnection();

            // Sets up the ORM entity manager.
            $instance->setupEntityManager();

            // Sets up the current locale.
            $instance->setupCurrentLocale();

            // Sets up the current theme.
            $instance->setupCurrentTheme();

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
