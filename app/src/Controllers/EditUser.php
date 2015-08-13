<?php
/**
 * Edit User Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\RouterManager;
use EBloodBank\Kernal\Notices;
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
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'submit_user':
                $this->doSubmitAction();
                break;
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getTargetID()
    {
        $targetID = 0;
        $route = RouterManager::getMatchedRoute();

        if ($route && isset($route->params['id'])) {
            if (isVaildID($route->params['id'])) {
                $targetID = (int) $route->params['id'];
            }
        }

        return $targetID;
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        $userID = $this->getTargetID();

        if (isCurrentUserCan('edit_user') || getCurrentUserID() === $userID) {

            try {

                $user = EntityManager::getUserReference($userID);

                // Set the user logon.
                $user->set('logon', filter_input(INPUT_POST, 'user_logon'), true);

                $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
                $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

                if (! empty($userPass1) xor ! empty($userPass2)) {
                    throw new Exceptions\InvaildArgument(__('Please enter the password twice.'), 'user_pass');
                }

                if (! empty($userPass1) && ! empty($userPass2)) {

                    if ($userPass1 !== $userPass2) {
                        throw new Exceptions\InvaildArgument(__('Please enter the same password.'), 'user_pass');
                    }

                    // Set the user password.
                    $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);

                }

                // Set the user role.
                if ($userID != getCurrentUserID()) {
                    $user->set('role', filter_input(INPUT_POST, 'user_role'), true);
                }

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditUserURL($userID),
                        array('flag-submitted' => true)
                    )
                );

            } catch (Exceptions\InvaildArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $userID = $this->getTargetID();

        if (! $userID) {
            redirect(getHomeURL());
        }

        if (isCurrentUserCan('edit_user') || getCurrentUserID() === $userID) {
            $this->doActions();
            $userRepository = EntityManager::getUserRepository();
            $user = $userRepository->find($userID);
            if (! empty($user)) {
                $view = View::instance('edit-user', array( 'user' => $user ));
            } else {
                $view = View::instance('error-404');
            }
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
