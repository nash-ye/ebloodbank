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
use EBloodBank\Traits\SessionTrait;
use EBloodBank\Traits\EntityManagerTrait;

/**
 * Abstract controller class
 *
 * @since 1.0
 */
abstract class Controller
{
    use AclTrait;
    use SessionTrait;
    use EntityManagerTrait;

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
        $this->setSession($container->get('session'));
        $this->setEntityManager($container->get('entity_manager'));
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
