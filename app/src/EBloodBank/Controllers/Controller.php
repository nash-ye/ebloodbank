<?php
/**
 * Abstract controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use Aura\Di\ContainerInterface;

/**
 * Abstract controller class
 *
 * @since 1.0
 */
abstract class Controller
{
    /**
     * @var \Aura\Di\ContainerInterface
     * @since 1.2
     */
    protected $container;

    /**
     * @return void
     * @since 1.2
     */
    public function __construct(ContainerInterface $container)
    {
        $this->getContainer = $container;
    }

    /**
     * @var \Aura\Di\ContainerInterface
     * @since 1.2
     */
    public function getContainer()
    {
        return $this->getContainer;
    }
}
