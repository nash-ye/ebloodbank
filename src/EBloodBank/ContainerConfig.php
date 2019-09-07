<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Gettext;
use Aura\Di\Container;
use Aura\Dispatcher\Dispatcher;

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
        $this->setupTranslator($container);
        $this->setupDBConnection($container);
        $this->setupEntityManager($container);
        $this->setupRouter($container);
        $this->setupMailer($container);
        $this->setupSession($container);
        $this->setupAcl($container);
        $this->setupDispatcher($container);
        $container->set('viewFactory', $container->lazyNew(Views\ViewFactory::class, [$container]));
        $container->set('eventManager', $container->lazy(new EventManagerFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    public function modify(Container $container) : void
    {
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupLogger(Container $container)
    {
        $container->set('logger', $container->lazy(new LoggerFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupServerRequest(Container $container)
    {
        $container->set('request', $container->lazy(new ServerRequestFactory(), $container));
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function setupTranslator(Container $container)
    {
        $container->set('translator', $container->lazyNew(Gettext\Translator::class));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupDBConnection(Container $container)
    {
        $container->set('db_connection', $container->lazy(new DbConnectionFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupEntityManager(Container $container)
    {
        $container->set('entity_manager', $container->lazy(new EntityManagerFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupRouter(Container $container)
    {
        $container->set('router', $container->lazy(new RouterFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupMailer(Container $container)
    {
        $container->set('mailer', $container->lazy(new SwiftMailerFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupSession(Container $container)
    {
        $container->set('session', $container->lazy(new SessionFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupAcl(Container $container)
    {
        $container->set('acl', $container->lazy(new AclFactory(), $container));
    }

    /**
     * @return void
     * @since 1.6
     */
    protected function setupDispatcher(Container $container)
    {
        $container->set('dispatcher', $container->lazyNew(Dispatcher::class));
    }
}
