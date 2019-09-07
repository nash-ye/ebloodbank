<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @since 1.6
 */
class EventManager
{
    /**
     * @var   \Symfony\Component\EventDispatcher\EventDispatcher
     * @since 1.6
     */
    protected $eventDispatcher;

    /**
     * @since 1.6
     */
    public function __construct()
    {
        $this->eventDispatcher = new EventDispatcher();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     * @since  1.6
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}
