<?php
/**
 * Main class file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use Monolog;
use Aura\Di\ContainerBuilder;

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
        $containerBuilder = new ContainerBuilder();
        $this->container = $containerBuilder->newConfiguredInstance([
            ContainerConfig::class,
        ]);
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
        // Register the logger as an exception handler, error handler and fatal error handler.
        Monolog\ErrorHandler::register($this->getLogger());
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
        $this->getTranslator()->register();
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
        // Try to establish the database connection.
        tryDatabaseConnection($this->getDBConnection());
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
        $routerContainer = $this->getRouter();

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

        $dispatcher = $this->getDispatcher();
        $dispatcher->setObjects($controllers);
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
                    $this->getContainer()->get('viewFactory')->displayView('error-db');
                    break;

                default:
                    $matchedRoute = $this->getRouter()->getMatcher()->getMatchedRoute();
                    if (empty($matchedRoute)) {
                        $this->getContainer()->get('viewFactory')->displayView('error-404');
                    } else {
                        try {
                            $dispatcher($matchedRoute->attributes, $matchedRoute->name);
                        } catch (\Aura\Dispatcher\Exception\ObjectNotDefined $ex) {
                            $this->getContainer()->get('viewFactory')->displayView('error-404');
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

            // Sets up the current locale.
            $instance->setupCurrentLocale();

            // Sets up the current theme.
            $instance->setupCurrentTheme();

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
