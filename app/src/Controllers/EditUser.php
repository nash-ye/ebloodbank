<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

/**
 * @since 1.0
 */
class EditUser extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('edit_user')) {

            try {

                $userID = (int) $_GET['id'];
                $user = EntityManager::getUserReference($userID);

                if (isset($_POST['user_logon'])) {
                    $user->set('user_logon', $_POST['user_logon'], true);
                }

                if (isset($_POST['user_pass_1'])) {
                    if (isset($_POST['user_pass_2']) && $_POST['user_pass_1'] === $_POST['user_pass_2']) {
                        $user->set('user_pass', password_hash($_POST['user_pass_1'], PASSWORD_BCRYPT));
                    } else {
                        Notices::addNotice('confirm_user_pass', __('Please confirm the new password.'), 'warning');
                    }
                }

                if (isset($_POST['user_role'])) {
                    $user->set('user_role', $_POST['user_role'], true);
                }

                EntityManager::getInstance()->flush();

                redirect(
                    getPageURL('edit-user', array(
                        'id' => $userID,
                        'flag-submitted' => true
                    ))
                );

            } catch (Exceptions\InvaildProperty $ex) {
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
        if (! empty($_POST['action'])) {
            switch ($_POST['action']) {
                case 'submit_user':
                    $this->action_submit();
                    break;
            }
        }

        if (isCurrentUserCan('edit_user')) {
            $user = EntityManager::getUserRepository()->find((int) $_GET['id']);
            if (! empty($user)) {
                $view = new View('edit-user', array( 'user' => $user ));
            } else {
                $view = new View('error-404');
            }
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
