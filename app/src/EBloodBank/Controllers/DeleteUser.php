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
use EBloodBank\Views\View;

/**
 * Delete user page controller class
 *
 * @since 1.0
 */
class DeleteUser extends Controller
{
    /**
     * @var \EBloodBank\Models\User
     * @since 1.0
     */
    protected $user;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($id)
    {
        if (EBB\isValidID($id)) {
            $userRepository = main()->getEntityManager()->getRepository('Entities:User');
            $this->user = $userRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('delete_user')) {
            $this->doActions();
            $view = View::forge('delete-user', [
                'user' => $this->getQueriedUser(),
            ]);
        } else {
            $view = View::forge('error-403');
        }
        $view();
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
        if (EBB\isCurrentUserCan('delete_user')) {
            $session = main()->getSession();
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $em = main()->getEntityManager();
            $user = $this->getQueriedUser();

            if ($user->get('id') == EBB\getCurrentUserID()) {
                return;
            }

            $em->remove($user);
            $em->flush();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditUsersURL(),
                    array('flag-deleted' => 1)
                )
            );
        }
    }

    /**
     * @return \EBloodBank\Models\User
     * @since 1.0
     */
    protected function getQueriedUser()
    {
        return $this->user;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedUserID()
    {
        $user = $this->getQueriedUser();
        return ($user) ? (int) $user->get('id') : 0;
    }
}
