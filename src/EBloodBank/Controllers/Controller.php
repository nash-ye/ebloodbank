<?php
/**
 * Abstract controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use Aura\Di\ContainerInterface;
use EBloodBank\Traits\AclTrait;

/**
 * Abstract controller class
 *
 * @since 1.0
 */
abstract class Controller
{
    use AclTrait;

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
        $this->container = $container;
        $this->setAcl($container->get('acl'));
    }

    /**
     * @var \Aura\Di\ContainerInterface
     * @since 1.2
     */
    public function getContainer()
    {
        return $this->container;
    }
}
