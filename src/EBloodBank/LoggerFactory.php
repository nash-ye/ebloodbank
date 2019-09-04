<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Monolog;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class LoggerFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Monolog\Logger
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $logger = new Monolog\Logger('Main Logger');

        if (EBB_DEV_MODE) {
            $debugHandler = new Monolog\Handler\StreamHandler(EBB_LOGS_DIR . '/debug.log', Monolog\Logger::DEBUG);
            $logger->pushHandler($debugHandler);
        }

        $warningsHandler = new Monolog\Handler\StreamHandler(EBB_LOGS_DIR . '/warnings.log', Monolog\Logger::WARNING);
        $logger->pushHandler($warningsHandler);

        return $logger;
    }
}
