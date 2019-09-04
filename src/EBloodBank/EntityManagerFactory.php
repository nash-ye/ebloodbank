<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Doctrine;
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
        
        $cacheDriver = $this->setupCacheDriver();
        $config->setMetadataCacheImpl($cacheDriver);
        $config->setResultCacheImpl($cacheDriver);
        $config->setQueryCacheImpl($cacheDriver);

        $entityManager = ORM\EntityManager::create($container->get('db_connection'), $config);

        return $entityManager;
    }

    /**
     * @return \Doctrine\Common\Cache\Cache
     * @since 1.6
     */
    protected function setupCacheDriver()
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
            } else {
                $cacheDriver = new Doctrine\Common\Cache\ArrayCache();
            }
        }

        return $cacheDriver;
    }
}
