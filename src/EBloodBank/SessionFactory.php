<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Aura\Session;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class SessionFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Aura\Session\Session
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $sessionFactory = new Session\SessionFactory();
        $session = $sessionFactory->newInstance($_COOKIE);

        return $session;
    }
}
