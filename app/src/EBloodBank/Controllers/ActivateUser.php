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
            $userRepository = $container->get('entity_manager')->getRepository('Entities:User');
            $this->user = $userRepository->find($userID);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canActivateUsers()) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedUserExists()) {
            View::display('error-404');
            return;
        }

        $user = $this->getQueriedUser();

        if (! $currentUser->canActivateUser($user)) {
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
        $session = $this->getContainer()->get('session');
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

        $em = $this->getContainer()->get('entity_manager');
        $user->set('status', 'activated');
        $em->flush($user);

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
