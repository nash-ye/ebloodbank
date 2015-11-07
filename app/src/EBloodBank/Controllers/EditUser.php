<?php
/**
 * Edit user page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

/**
 * Edit user page controller class
 *
 * @since 1.0
 */
class EditUser extends Controller
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

        if (! $currentUser || ! $currentUser->canEditUsers()) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedUserExists()) {
            View::display('error-404');
            return;
        }

        $user = $this->getQueriedUser();

        if (! $currentUser->canEditUser($user)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        View::display('edit-user', [
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
            case 'submit_user':
                $this->doSubmitAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-edited')) {
            Notices::addNotice('edited', __('User edited.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            $session = $this->getContainer()->get('session');
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $currentUser = EBB\getCurrentUser();
            $user = $this->getQueriedUser();

            if (! $currentUser || ! $currentUser->canEditUser($user)) {
                return;
            }

            $em = $this->getContainer()->get('entity_manager');
            $userRepository = $em->getRepository('Entities:User');

            // Set the user name.
            $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

            // Set the user email.
            $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

            $duplicateUser = $userRepository->findOneBy(['email' => $user->get('email'), 'status' => 'any']);

            if (! empty($duplicateUser) && $duplicateUser->get('id') != $user->get('id')) {
                throw new InvalidArgumentException(__('Please enter a unique user e-mail.'));
            }

            $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
            $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

            if (! empty($userPass1) xor ! empty($userPass2)) {
                throw new InvalidArgumentException(__('Please enter the password twice.'));
            }

            if (! empty($userPass1) && ! empty($userPass2)) {
                if ($userPass1 !== $userPass2) {
                    throw new InvalidArgumentException(__('Please enter the same password.'));
                }

                // Set the user password.
                $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);
            }

            // Set the user role.
            if ($user->get('id') != EBB\getCurrentUserID()) {
                $user->set('role', filter_input(INPUT_POST, 'user_role'), true);
            }

            $em->flush($user);

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditUserURL($user->get('id')),
                    ['flag-edited' => true]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_user_argument', $ex->getMessage());
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
     * @return bool
     * @since 1.2
     */
    protected function isQueriedUserExists()
    {
        $user = $this->getQueriedUser();
        return ($user && $user->isExists());
    }
}
