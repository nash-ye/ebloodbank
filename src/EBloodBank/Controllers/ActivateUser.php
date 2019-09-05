<?php
/**
 * Activate user page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use Psr\Container\ContainerInterface;

/**
 * Activate user page controller class
 *
 * @since 1.0
 */
class ActivateUser extends Controller
{
    /**
     * @var int
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
    public function __construct(ContainerInterface $container, $userID)
    {
        parent::__construct($container);
        if (EBB\isValidID($userID)) {
            $this->userId = (int) $userID;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'activate')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->userID) {
            $this->user = $this->getUserRepository()->find($this->userID);
        }

        if (! $this->user) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        if (! $this->getAcl()->canActivateUser($this->getAuthenticatedUser(), $this->user)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'activate-user',
            [
                'user' => $this->user,
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
            case 'activate_user':
                $this->doActivateAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActivateAction()
    {
        $actionToken = filter_input(INPUT_POST, 'token');
        $sessionToken = $this->getSession()->getCsrfToken();

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canActivateUser($this->getAuthenticatedUser(), $this->user)) {
            return;
        }

        if (! $this->user->isPending()) {
            return;
        }

        $this->user->set('status', 'activated');
        $this->getEntityManager()->flush($this->user);

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-activated' => 1]
            )
        );
    }
}
