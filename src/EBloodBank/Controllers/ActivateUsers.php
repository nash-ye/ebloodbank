<?php
/**
 * Activate users page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;

/**
 * Activate users page controller class
 *
 * @since 1.1
 */
class ActivateUsers extends Controller
{
    /**
     * @var \EBloodBank\Models\User[]
     * @since 1.1
     */
    protected $users = [];

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'activate')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if (filter_has_var(INPUT_POST, 'users')) {
            $usersIDs = filter_input(INPUT_POST, 'users', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($usersIDs) && is_array($usersIDs)) {
                $this->users = $this->getUserRepository()->findBy(['id' => $usersIDs]);
            }
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'activate-users',
            [
                'users' => $this->users,
            ]
        );
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'activate_users':
                $this->doActivateAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doActivateAction()
    {
        // Double check
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'activate')) {
            return;
        }

        $actionToken = filter_input(INPUT_POST, 'token');
        $sessionToken = $this->getSession()->getCsrfToken();

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        if (empty($this->users)) {
            return;
        }

        $activatedUsersCount = 0;

        foreach ($this->users as $user) {
            if (! $user->isPending()) {
                continue;
            }
            if ($this->getAcl()->canActivateUser($this->getAuthenticatedUser(), $user)) {
                $user->set('status', 'activated');
                $activatedUsersCount++;
            }
        }

        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-activated' => $activatedUsersCount]
            )
        );
    }
}
