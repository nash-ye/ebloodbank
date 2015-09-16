<?php
/**
 * Edit User Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;

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
        if (EBB\isCurrentUserCan('edit_user') || EBB\getCurrentUserID() === $userID) {
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
        $userID = $this->getQueriedUserID();

        if (EBB\isCurrentUserCan('edit_user') || EBB\getCurrentUserID() === $userID) {

            try {

                $user = $this->getQueriedUser();

                $em = main()->getEntityManager();
                $userRepository = $em->getRepository('Entities:User');

                // Set the user name.
                $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

                // Set the user email.
                $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

                $duplicateUser = $userRepository->findOneBy(array('email' => $user->get('email')));

                if (! empty($duplicateUser) && $duplicateUser->get('id') != $userID) {
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
                if ($userID != EBB\getCurrentUserID()) {
                    $user->set('role', filter_input(INPUT_POST, 'user_role'), true);
                }

                $em = main()->getEntityManager();
                $em->flush($user);

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getEditUserURL($userID),
                        array('flag-edited' => true)
                    )
                );

            } catch (InvalidArgumentException $ex) {
                Notices::addNotice('invalid_user_argument', $ex->getMessage());
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

        if (! isset($route->params['id']) || ! EBB\isValidID($route->params['id'])) {
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
