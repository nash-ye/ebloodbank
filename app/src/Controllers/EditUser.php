<?php
/**
 * Edit User Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 */
class EditUser extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $userID = $this->getQueriedUserID();
        if (isCurrentUserCan('edit_user') || getCurrentUserID() === $userID) {
            $user = $this->getQueriedUser();
            if (! empty($user)) {
                $this->doActions();
                $this->addNotices();
                $view = View::forge('edit-user', array(
                    'user' => $user,
                ));
            } else {
                $view = View::forge('error-404');
            }
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
        if (filter_has_var(INPUT_GET, 'flag-updated')) {
            Notices::addNotice('updated', __('User updated.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        $userID = $this->getQueriedUserID();
        if (isCurrentUserCan('edit_user') || getCurrentUserID() === $userID) {

            try {

                $user = $this->getQueriedUser();

                // Set the user name.
                $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

                // Set the user email.
                $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

                $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
                $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

                if (! empty($userPass1) xor ! empty($userPass2)) {
                    throw new InvalidArgument(__('Please enter the password twice.'), 'user_pass');
                }

                if (! empty($userPass1) && ! empty($userPass2)) {

                    if ($userPass1 !== $userPass2) {
                        throw new InvalidArgument(__('Please enter the same password.'), 'user_pass');
                    }

                    // Set the user password.
                    $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);

                }

                // Set the user role.
                if ($userID != getCurrentUserID()) {
                    $user->set('role', filter_input(INPUT_POST, 'user_role'), true);
                }

                $em = main()->getEntityManager();
                $em->flush($user);

                redirect(
                    addQueryArgs(
                        getEditUserURL($userID),
                        array('flag-updated' => true)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return \EBloodBank\Models\User
     * @since 1.0
     */
    protected function getQueriedUser()
    {
        $route = main()->getRouter()->getMatchedRoute();

        if (empty($route)) {
            return;
        }

        if (! isset($route->params['id']) || ! isValidID($route->params['id'])) {
            return;
        }

        $userRepository = main()->getEntityManager()->getRepository('Entities:User');
        $user = $userRepository->find((int) $route->params['id']);

        return $user;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedUserID()
    {
        return ($user = $this->getQueriedUser()) ? (int) $user->get('id') : 0;
    }
}
