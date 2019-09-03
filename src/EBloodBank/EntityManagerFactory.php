<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Doctrine\ORM;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class EntityManagerFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Doctrine\ORM\EntityManager
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = ORM\Tools\Setup::createConfiguration((bool) EBB_DEV_MODE);

        $entitiesPaths = [trimTrailingSlash(EBB_DIR) . '/src/EBloodBank/Models/'];
        $driverImpl = $config->newDefaultAnnotationDriver($entitiesPaths, true);
        $config->addEntityNamespace('Entities', 'EBloodBank\Models');
        $config->setMetadataDriverImpl($driverImpl);

        $config->setProxyDir(trimTrailingSlash(EBB_DIR) . '/src/EBloodBank/Proxies/');
        $config->setAutoGenerateProxyClasses((bool) EBB_DEV_MODE);
        $config->setProxyNamespace('EBloodBank\Proxies');
        
        $config->setMetadataCacheImpl($container->get('cache_driver'));
        $config->setResultCacheImpl($container->get('cache_driver'));
        $config->setQueryCacheImpl($container->get('cache_driver'));

        $entityManager = ORM\EntityManager::create($container->get('db_connection'), $config);

        return $entityManager;
    }
}
