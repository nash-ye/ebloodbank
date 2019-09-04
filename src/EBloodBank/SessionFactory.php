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

        if (! $session->isStarted()) {
            $session->setName('EBB_SESSION_ID');
            $session->setCookieParams(
                [
                    'lifetime' => 3600,
                    'path'     => parse_url(getHomeURL(), PHP_URL_PATH),
                    'secure'   => isHTTPS(),
                    'httponly' => true,
                ]
            );
            $session->start();
        }

        return $session;
    }
}
