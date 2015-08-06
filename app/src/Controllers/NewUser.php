<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Models\User;

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

                if (isCurrentUserCan('approve_user')) {
                    $user->set('user_status', 'activated');
                } else {
                    $user->set('user_status', 'pending');
                }

                $user->set('user_rtime', gmdate('Y-m-d H:i:s'));

                $em = EntityManager::getInstance();
                $em->persist($user);
                $em->flush();

                $submitted = isVaildID($user->get('user_id'));

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
        if (isCurrentUserCan('add_user')) {

            if (! empty($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'submit_user':
                        $this->action_submit();
                        break;
                }
            }

            $view = new View('new-user');

        } else {

            $view = new View('error-401');

        }

        $view();
    }
}
