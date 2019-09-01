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
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container, $userID)
    {
        parent::__construct($container);
        if (EBB\isValidID($userID)) {
            $userRepository = $this->getEntityManager()->getRepository('Entities:User');
            $this->user = $userRepository->find($userID);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'activate')) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedUserExists()) {
            View::display('error-404');
            return;
        }

        $user = $this->getQueriedUser();

        if (! $this->getAcl()->canActivateUser($this->getAuthenticatedUser(), $user)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        View::display('activate-user', [
            'user' => $user,
        ]);
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
        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $user = $this->getQueriedUser();

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canActivateUser($this->getAuthenticatedUser(), $user)) {
            return;
        }

        if (! $user->isPending()) {
            return;
        }

        $user->set('status', 'activated');
        $this->getEntityManager()->flush($user);

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-activated' => 1]
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
     * @return bool
     * @since 1.2
     */
    protected function isQueriedUserExists()
    {
        $user = $this->getQueriedUser();
        return ($user && $user->isExists());
    }
}
