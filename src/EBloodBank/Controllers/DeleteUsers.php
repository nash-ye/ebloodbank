<?php
/**
 * Delete users page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;

/**
 * Delete users page controller class
 *
 * @since 1.1
 */
class DeleteUsers extends Controller
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
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'delete')) {
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
            'delete-users',
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
            case 'delete_users':
                $this->doDeleteAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doDeleteAction()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'delete')) {
            return;
        }

        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $users = $this->users;

        if (! $users || ! is_array($users)) {
            return;
        }

        $deletedUsersCount = 0;

        foreach ($users as $user) {
            if ($this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $user)) {
                $this->getEntityManager()->remove($user);
                $deletedUsersCount++;
            }
        }

        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-deleted' => $deletedUsersCount]
            )
        );
    }
}
