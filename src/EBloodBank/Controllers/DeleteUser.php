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
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container, $id)
    {
        parent::__construct($container);
        if (EBB\isValidID($id)) {
            $userRepository = $container->get('entity_manager')->getRepository('Entities:User');
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

        if (! $currentUser || ! $currentUser->canDeleteUsers()) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedUserExists()) {
            View::display('error-404');
            return;
        }

        $user = $this->getQueriedUser();

        if (! $currentUser->canDeleteUser($user)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        View::display('delete-user', [
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
        $session = $this->getContainer()->get('session');
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $user = $this->getQueriedUser();
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canDeleteUser($user)) {
            return;
        }

        $em = $this->getContainer()->get('entity_manager');
        $em->remove($user);
        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-deleted' => 1]
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
