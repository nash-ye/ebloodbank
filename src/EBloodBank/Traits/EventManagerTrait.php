<?php
/**
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.6
 */
namespace EBloodBank\Traits;

use EBloodBank\EventManager;

/**
 * @since 1.6
 */
trait EventManagerTrait
{
    /**
     * @var   \EBloodBank\EventManager
     * @since 1.6
     */
    protected $eventManager;

    /**
     * @return \EBloodBank\EventManager
     * @since  1.6
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @return void
     * @since  1.6
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}
