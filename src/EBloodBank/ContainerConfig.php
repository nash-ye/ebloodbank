<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Redis;
use Monolog;
use Gettext;
use Doctrine;
use Doctrine\DBAL;
use Swift_Mailer;
use Swift_SmtpTransport;
use Aura\Di\Container;
use Aura\Dispatcher\Dispatcher;
use Aura\Router\RouterContainer;
use Aura\Session\SessionFactory;
use Zend\Diactoros\ServerRequestFactory;

/**
 * @since 1.6
 */
class ContainerConfig extends \Aura\Di\ContainerConfig
{
    /**
     * @return void
     * @since 1.6
     */
    public function define(Container $container) : void
    {
        $this->setupLogger($container);
        $this->setupServerRequest($container);
        $this->setupCacheDriver($container);
        $this->setupTranslator($container);
        $this->setupDBConnection($container);
        $this->setupEntityManager($container);
        $this->setupRouter($container);
        $this->setupMailer($container);
        $this->setupSession($container);
        $this->setupAcl($container);
        $this->setupDispatcher($container);
    }

    /**
     * @return void
     * @since 1.6
     */
    public function modify(Container $container) : void
    {
    }


    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupLogger(Container $container)
    {
        $logger = new Monolog\Logger('Main Logger');

        if (EBB_DEV_MODE) {
            $debugHandler = new Monolog\Handler\StreamHandler(EBB_LOGS_DIR . '/debug.log', Monolog\Logger::DEBUG);
            $logger->pushHandler($debugHandler);
        }

        $warningsHandler = new Monolog\Handler\StreamHandler(EBB_LOGS_DIR . '/warnings.log', Monolog\Logger::WARNING);
        $logger->pushHandler($warningsHandler);

        $container->set('logger', $logger);
    }

    /**
     * @access private
     * @return void
     * @since 1.3
     */
    private function setupServerRequest(Container $container)
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $container->set('request', $request);
    }

    /**
     * @access private
     * @return void
     * @since 1.3
     */
    private function setupCacheDriver(Container $container)
    {
        if (EBB_DEV_MODE) {
            $cacheDriver = new Doctrine\Common\Cache\ArrayCache();
        } else {
            if (EBB_REDIS_CACHE && extension_loaded('redis')) {
                $redis = new Redis();
                $redis->connect(EBB_REDIS_HOST, EBB_REDIS_PORT);
                if (EBB_REDIS_PASS) {
                    $redis->auth(EBB_REDIS_PASS);
                }
                if (EBB_REDIS_DB) {
                    $redis->select(EBB_REDIS_DB);
                }
                $cacheDriver = new Doctrine\Common\Cache\RedisCache();
                $cacheDriver->setRedis($redis);
            } elseif (EBB_APCU_CACHE && extension_loaded('apcu')) {
                $cacheDriver = new Doctrine\Common\Cache\ApcuCache();
            } elseif (EBB_FS_CACHE && is_writable(EBB_CACHE_DIR)) {
                $cacheDriver = new Doctrine\Common\Cache\FilesystemCache(EBB_CACHE_DIR, '.ebb.data');
            }
        }

        $container->set('cache_driver', $cacheDriver ?: new Doctrine\Common\Cache\ArrayCache());
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupTranslator(Container $container)
    {
        $translator = new Gettext\Translator();

        $container->set('translator', $translator);
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupDBConnection(Container $container)
    {
        $DBConnection = DBAL\DriverManager::getConnection([
            'dbname'    => EBB_DB_NAME,
            'user'      => EBB_DB_USER,
            'password'  => EBB_DB_PASS,
            'host'      => EBB_DB_HOST,
            'driver'    => EBB_DB_DRIVER,
            'charset'   => 'utf8',
        ]);

        $container->set('db_connection', $DBConnection);
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupEntityManager(Container $container)
    {
        $container->set('entity_manager', $container->lazy(new EntityManagerFactory(), $container));
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupRouter(Container $container)
    {
        $basepath = trimTrailingSlash(getHomeURL('relative'));

        $routerContainer = new RouterContainer($basepath);

        $container->set('router', $routerContainer);
    }

    /**
     * @access private
     * @return void
     * @since 1.1
     */
    private function setupMailer(Container $container)
    {
        $transport = Swift_SmtpTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);
        $container->set('mailer', $mailer);
    }

    /**
     * @access private
     * @return void
     * @since 1.0.1
     */
    private function setupSession(Container $container)
    {
        $sessionFactory = new SessionFactory();
        $session = $sessionFactory->newInstance($_COOKIE);

        $container->set('session', $session);
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function setupAcl(Container $container)
    {
        $container->set('acl', $container->lazy(new AclFactory(), $container));
    }

    /**
     * @access private
     * @return void
     * @since 1.0
     */
    public function setupDispatcher(Container $container)
    {
        $dispatcher = new Dispatcher();
        $container->set('dispatcher', $dispatcher);
    }

}
