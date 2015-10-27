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
use EBloodBank\Views\View;

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
    protected $users;

    /**
     * @return void
     * @since 1.1
     */
    public function __construct()
    {
        $this->users = [];
        if (filter_has_var(INPUT_POST, 'users')) {
            $usersIDs = filter_input(INPUT_POST, 'users', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($usersIDs) && is_array($usersIDs)) {
                $userRepository = main()->getEntityManager()->getRepository('Entities:User');
                foreach($usersIDs as $userID) {
                    if (EBB\isValidID($userID)) {
                        $user = $userRepository->find($userID);
                        if (! empty($user)) {
                            $this->users[$userID] = $user;
                        }
                    }
                }
            }
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('activate_user')) {
            $this->doActions();
            $view = View::forge('activate-users', [
                'users' => $this->getQueriedUsers(),
            ]);
        } else {
            $view = View::forge('error-403');
        }
        $view();
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
        if (EBB\isCurrentUserCan('activate_user')) {
            $session = main()->getSession();
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $users = $this->getQueriedUsers();

            if (empty($users)) {
                return;
            }

            $activatedUsersCount = 0;
            $em = main()->getEntityManager();

            foreach($users as $user) {
                if (empty($user) || ! $user->isPending()) {
                    continue;
                }
                $user->set('status', 'activated');
                $activatedUsersCount++;
            }

            $em->flush();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditUsersURL(),
                    array('flag-activated' => $activatedUsersCount)
                )
            );
        }
    }

    /**
     * @return \EBloodBank\Models\User[]
     * @since 1.1
     */
    protected function getQueriedUsers()
    {
        return $this->users;
    }
}
