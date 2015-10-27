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
use EBloodBank\Views\View;

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
        if (EBB\isCurrentUserCan('delete_user')) {
            $this->doActions();
            $view = View::forge('delete-users', [
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
        if (EBB\isCurrentUserCan('delete_user')) {
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

            $deletedUsersCount = 0;
            $em = main()->getEntityManager();
            $currentUserID = EBB\getCurrentUserID();

            foreach($users as $user) {
                if ($user->get('id') == $currentUserID) {
                    return;
                }

                $em->remove($user);
                $deletedUsersCount++;
            }

            $em->flush();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditUsersURL(),
                    array('flag-deleted' => $deletedUsersCount)
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
