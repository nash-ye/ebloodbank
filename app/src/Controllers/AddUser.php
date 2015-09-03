<?php
/**
 * Add User Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Models\User;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 */
class AddUser extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('add_user')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('add-user');
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
        if (isCurrentUserCan('add_user')) {

            try {

                $user = new User();

                // Set the user name.
                $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

                // Set the user email.
                $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

                $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
                $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

                if (empty($userPass1)) {
                    throw new InvalidArgument(__('Please enter the password.'), 'user_pass');
                }

                if (empty($userPass2)) {
                    throw new InvalidArgument(__('Please confirm the password.'), 'user_pass');
                }

                if ($userPass1 !== $userPass2) {
                    throw new InvalidArgument(__('Please enter the same password.'), 'user_pass');
                }

                // Set the user password.
                $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);

                // Set the user role.
                $user->set('role', filter_input(INPUT_POST, 'user_role'), true);

                // Set the user time.
                $user->set('created_at', gmdate('Y-m-d H:i:s'));

                // Set the user status.
                if (isCurrentUserCan('activate_user')) {
                    $user->set('status', 'activated');
                } else {
                    $user->set('status', 'pending');
                }

                $em = main()->getEntityManager();
                $em->persist($user);
                $em->flush();

                $added = isValidID($user->get('id'));

                redirect(
                    addQueryArgs(
                        getAddUserURL(),
                        array('flag-added' => $added)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }
}
