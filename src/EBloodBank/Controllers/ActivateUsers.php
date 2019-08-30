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
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        if (filter_has_var(INPUT_POST, 'users')) {
            $usersIDs = filter_input(INPUT_POST, 'users', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($usersIDs) && is_array($usersIDs)) {
                $userRepository = $this->getEntityManager()->getRepository('Entities:User');
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
        if (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'User', 'activate')) {
            $view = View::forge('error-403');
        } else {
            $this->doActions();
            $view = View::forge('activate-users', [
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
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'User', 'activate')) {
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

        $activatedUsersCount = 0;

        foreach ($users as $user) {
            if (! $user->isPending()) {
                continue;
            }
            if ($this->getAcl()->canActivateUser($currentUser, $user)) {
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

    /**
     * @return \EBloodBank\Models\User[]
     * @since 1.1
     */
    protected function getQueriedUsers()
    {
        return $this->users;
    }
}
