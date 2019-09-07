<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class EventManagerFactory
{
    /**
     * @param  ContainerInterface $container
     * @return EventManager
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $eventManager = new EventManager();
        $this->addEventListener($eventManager, $container);

        return $eventManager;
    }

    /**
     * @param  EventManager $eventManager
     * @param  ContainerInterface $container
     * @return void
     * @since  1.6
     */
    protected function addEventListener(EventManager $eventManager, ContainerInterface $container)
    {
        $emailNotificationListener = Listeners\EmailNotificationListener::factory($container);
        $eventManager->getEventDispatcher()->addListener('user.created', $emailNotificationListener);
        $eventManager->getEventDispatcher()->addListener('donor.created', $emailNotificationListener);
    }
}
