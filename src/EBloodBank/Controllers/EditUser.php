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
use Psr\Container\ContainerInterface;

/**
 * Edit user page controller class
 *
 * @since 1.0
 */
class EditUser extends Controller
{
    /**
     * @var   int
     * @since 1.6
     */
    protected $userId = 0;

    /**
     * @var \EBloodBank\Models\User|null
     * @since 1.0
     */
    protected $user;

    /**
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $userId)
    {
        parent::__construct($container);
        if (EBB\isValidID($userId)) {
            $this->userId = (int) $userId;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser()) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->userId) {
            $this->user = $this->getUserRepository()->find($this->userId);
        }

        if (! $this->user) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $user = $this->user;

        if (! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $user)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView(
            'edit-user',
            [
                'user' => $user,
            ]
        );
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
            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $user = $this->user;

            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $user)) {
                return;
            }

            // Set the user name.
            $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

            // Set the user email.
            $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

            $duplicateUser = $this->getUserRepository()->findOneBy(['email' => $user->get('email'), 'status' => 'any']);

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
            if ($user->get('id') != $this->getAuthenticatedUser()->get('id')) {
                $user->set('role', filter_input(INPUT_POST, 'user_role'), true);
            }

            $this->getEntityManager()->flush($user);

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
}
