<?php
/**
 * Delete users page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container)
    {
        $this->users = [];
        parent::__construct($container);
        if (filter_has_var(INPUT_POST, 'users')) {
            $usersIDs = filter_input(INPUT_POST, 'users', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($usersIDs) && is_array($usersIDs)) {
                $userRepository = $container->get('entity_manager')->getRepository('Entities:User');
                $this->users = $userRepository->findBy(['id' => $usersIDs]);
            }
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        if (! $currentUser || ! $currentUser->canDeleteUsers()) {
            $view = View::forge('error-403');
        } else {
            $this->doActions();
            $view = View::forge('delete-users', [
                'users' => $this->getQueriedUsers(),
            ]);
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
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canDeleteUsers()) {
            return;
        }

        $session = $this->getContainer()->get('session');
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $users = $this->getQueriedUsers();

        if (! $users || ! is_array($users)) {
            return;
        }

        $deletedUsersCount = 0;
        $em = $this->getContainer()->get('entity_manager');

        foreach ($users as $user) {
            if ($currentUser->canDeleteUser($user)) {
                $em->remove($user);
                $deletedUsersCount++;
            }
        }

        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditUsersURL(),
                ['flag-deleted' => $deletedUsersCount]
            )
        );
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
