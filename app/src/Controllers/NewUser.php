<?php
/**
 * New User Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Models\User;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class NewUser extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('add_user')) {

            try {

                $user = new User();

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

                if (isset($_POST['user_role'])) {
                    $user->set('role', $_POST['user_role'], true);
                }

                if (isCurrentUserCan('approve_user')) {
                    $user->set('status', 'activated');
                } else {
                    $user->set('status', 'pending');
                }

                $user->set('rtime', gmdate('Y-m-d H:i:s'));

                $em = EntityManager::getInstance();
                $em->persist($user);
                $em->flush();

                $submitted = isVaildID($user->get('id'));

                redirect(
                    getPageURL('new-user', array(
                        'flag-submitted' => $submitted
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

        if (isCurrentUserCan('add_user')) {
            $view = new View('new-user');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
