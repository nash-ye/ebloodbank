<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Doctrine\DBAL;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class DbConnectionFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Doctrine\DBAL\Connection
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $DbConnection = DBAL\DriverManager::getConnection([
            'dbname'    => EBB_DB_NAME,
            'user'      => EBB_DB_USER,
            'password'  => EBB_DB_PASS,
            'host'      => EBB_DB_HOST,
            'driver'    => EBB_DB_DRIVER,
            'charset'   => 'utf8',
        ]);

        return $DbConnection;
    }
}
