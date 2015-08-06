<?php
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Models\User;

/**
 * @since 1.0
 */
class Signup extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_signup()
    {
        try {

            $user = new User();

            if (isset($_POST['user_logon'])) {
                $user->set('user_logon', $_POST['user_logon'], true);
            }

            if (isset($_POST['user_pass_1'])) {
                if (isset($_POST['user_pass_2']) && $_POST['user_pass_1'] === $_POST['user_pass_2']) {
                    $user->set('user_pass', password_hash($_POST['user_pass_1'], PASSWORD_BCRYPT));
                } else {
                    Notices::addNotice('confirm_user_pass', __('Please confirm your new password.'), 'warning');
                }
            }

            $user->set('user_role', 'default');
            $user->set('user_rtime', gmdate('Y-m-d H:i:s'));
            $user->set('user_status', 'pending');

            $em = EntityManager::getInstance();
            $em->persist($user);
            $em->flush();

            $submitted = isVaildID($user->get('user_id'));

            redirect(
                getPageURL('login', array(
                    'flag-signedup' => $submitted
                ))
            );

        } catch (Exceptions\InvaildProperty $ex) {
            Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
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
                case 'signup':
                    $this->action_signup();
                    break;
            }
        }

        $view = new View('signup');
        $view();
    }
}
