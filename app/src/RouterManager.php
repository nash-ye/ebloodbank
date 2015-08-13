<?php
/**
 * Router Manager
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

use Aura\Router\RouterFactory;

/**
 * @since 1.0
 */
class RouterManager
{
    /**
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @return \Aura\Router\Router
     * @since 1.0
     * @static
     */
    public static function getInstance()
    {
        static $router;

        if (is_null($router)) {
            $routerFactory = new RouterFactory(getHomeURL('relative'));
            $router = $routerFactory->newInstance();
        }

        return $router;
    }

    /**
     * @since 1.0
     * @static
     */
    public static function __callStatic($name, $params)
    {
        return call_user_func_array(array( self::getInstance(), $name ), $params);
    }
}
