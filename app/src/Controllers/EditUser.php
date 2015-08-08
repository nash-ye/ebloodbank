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
    protected function action_submit()
    {
        if (isCurrentUserCan('edit_user')) {

            try {

                $userID = (int) $_GET['id'];

                if (! isVaildID($userID)) {
                    die(__('Invalid user ID'));
                }

                $user = EntityManager::getUserReference($userID);

                if (isset($_POST['user_logon'])) {
                    $user->set('logon', $_POST['user_logon'], true);
                }

                if (isset($_POST['user_pass_1'])) {
                    if (isset($_POST['user_pass_2']) && $_POST['user_pass_1'] === $_POST['user_pass_2']) {
                        $user->set('pass', password_hash($_POST['user_pass_1'], PASSWORD_BCRYPT));
                    } else {
                        Notices::addNotice('confirm_user_pass', __('Please confirm the password.'), 'warning');
                    }
                }

                if (isset($_POST['user_role']) && $userID != getCurrentUserID()) {
                    $user->set('role', $_POST['user_role'], true);
                }

                $em = EntityManager::getInstance();
                $em->flush();

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
            $userID = (int) $_GET['id'];
            $user = EntityManager::getUserRepository()->find($userID);
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
