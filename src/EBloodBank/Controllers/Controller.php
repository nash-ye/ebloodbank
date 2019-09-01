<?php
/**
 * Abstract controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Models\User;
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
     * @var \EBloodBank\Models\User|null
     * @since 1.2
     */
    protected $authenticatedUser;

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

    /**
     * @return void
     * @since  1.6
     */
    protected function setAuthenticatedUser(User $user)
    {
        $this->authenticatedUser = $user;
    }

    /**
     * @return \EBloodBank\Models\User|null
     * @since  1.6
     */
    protected function findAuthenticatedUser()
    {
        $segment = $this->getSession()->getSegment('EBloodBank');
        $userID = (int) $segment->get('user_id', 0);

        if (empty($userID)) {
            return;
        }

        $userRepository = $this->getEntityManager()->getRepository('Entities:User');
        $user = $userRepository->find($userID);

        return $user;
    }

    /**
     * @return \EBloodBank\Models\User|null
     * @since  1.6
     */
    protected function getAuthenticatedUser()
    {
        if (is_null($this->authenticatedUser)) {
            $this->setAuthenticatedUser($this->findAuthenticatedUser());
        }

        return $this->authenticatedUser;
    }

    /**
     * @return bool
     * @since  1.6
     */
    protected function hasAuthenticatedUser()
    {
        return ($this->getAuthenticatedUser() != null);
    }
}
