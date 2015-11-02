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
use EBloodBank\Views\View;

/**
 * Activate user page controller class
 *
 * @since 1.0
 */
class ActivateUser extends Controller
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
        $currentUser = EBB\getCurrentUser();
        $user = $this->getQueriedUser();
        if ($currentUser && $currentUser->canActivateUser($user)) {
            $this->doActions();
            $view = View::forge('activate-user', [
                'user' => $user,
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
        $session = main()->getSession();
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $user = $this->getQueriedUser();
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canActivateUser($user)) {
            return;
        }

        if (! $user->isPending()) {
            return;
        }

        $em = main()->getEntityManager();
        $user->set('status', 'activated');
        $em->flush($user);

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                array('flag-activated' => 1)
            )
        );
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
