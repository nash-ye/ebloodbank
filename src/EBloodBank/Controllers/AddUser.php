<?php
/**
 * Add user page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Models\User;

/**
 * Add user page controller class
 *
 * @since 1.0
 */
class AddUser extends Controller
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
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'add')) {
            $this->viewFactory->displayView('error-403');
            return; 
        }

        $this->user = new User();

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView(
            'add-user',
            [
                'user' => $this->user,
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
        if (filter_has_var(INPUT_GET, 'flag-added')) {
            Notices::addNotice('added', __('User added.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'add')) {
                return;
            }

            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $user = $this->user;

            // Set the user name.
            $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

            // Set the user email.
            $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

            $duplicateUser = $this->getUserRepository()->findOneBy(['email' => $user->get('email'), 'status' => 'any']);

            if (! empty($duplicateUser)) {
                throw new InvalidArgumentException(__('Please enter a unique user e-mail.'));
            }

            $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
            $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

            if (empty($userPass1)) {
                throw new InvalidArgumentException(__('Please enter the password.'));
            }

            if (empty($userPass2)) {
                throw new InvalidArgumentException(__('Please confirm the password.'));
            }

            if ($userPass1 !== $userPass2) {
                throw new InvalidArgumentException(__('Please enter the same password.'));
            }

            // Set the user password.
            $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);

            // Set the user role.
            $user->set('role', filter_input(INPUT_POST, 'user_role'), true);

            // Set the user time.
            $user->set('created_at', new DateTime('now', new DateTimeZone('UTC')), true);

            // Set the user status.
            if ($this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'activate')) {
                $user->set('status', 'activated');
            } else {
                $user->set('status', Options::getOption('new_user_status'), true);
            }

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            $added = $user->isExists();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getAddUserURL(),
                    ['flag-added' => $added]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_user_argument', $ex->getMessage());
        }
    }
}
