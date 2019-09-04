<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Zend\Diactoros;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class ServerRequestFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Zend\Diactoros\ServerRequest
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $request = Diactoros\ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        return $request;
    }
}
