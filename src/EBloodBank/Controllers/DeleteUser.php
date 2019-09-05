<?php
/**
 * Delete user page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use Psr\Container\ContainerInterface;

/**
 * Delete user page controller class
 *
 * @since 1.0
 */
class DeleteUser extends Controller
{
    /**
     * @var   int
     * @since 1.6
     */
    protected $userId = 0;

    /**
     * @var \EBloodBank\Models\User|null
     * @since 1.0
     */
    protected $user;

    /**
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $userId)
    {
        parent::__construct($container);
        if (EBB\isValidID($userId)) {
            $this->userId = $userId;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'delete')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->userId) {
            $this->user = $this->getUserRepository()->find($this->userId);
        }

        if (! $this->user) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $user = $this->user;

        if (! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $user)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-user',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'delete_user':
                $this->doDeleteAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        $actionToken = filter_input(INPUT_POST, 'token');
        $sessionToken = $this->getSession()->getCsrfToken();

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $user = $this->user;

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $user)) {
            return;
        }

        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-deleted' => 1]
            )
        );
    }
}
