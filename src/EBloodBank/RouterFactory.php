<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Aura\Router\RouterContainer;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class RouterFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Aura\Router\RouterContainer
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $basepath = trimTrailingSlash(getHomeURL('relative'));
        $routerContainer = new RouterContainer($basepath);

        return $routerContainer;
    }
}
