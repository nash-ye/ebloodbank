<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;
use eBloodBank\Models\User;

/**
 * @since 1.0
 */
class NewUser extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_POST['action']) && 'submit_user' === $_POST['action']) {

            if (isCurrentUserCan('add_user')) {

                $user = new User();

                if (isset($_POST['user_logon'])) {
                    $user->set('user_logon', $_POST['user_logon'], true);
                }

                if (isset($_POST['user_pass_1'])) {
                    if (isset($_POST['user_pass_2']) && $_POST['user_pass_1'] === $_POST['user_pass_2']) {
                        $user->set('user_pass', password_hash($_POST['user_pass_1'], PASSWORD_BCRYPT));
                    } else {
                        // TODO: Display error "Please, Confirm your new password."
                    }
                }

                if (isset($_POST['user_role'])) {
                    $user->set('user_role', $_POST['user_role']);
                }

                $user->set('user_rtime', gmdate('Y-m-d H:i:s'));
                $user->set('user_status', 'activated');

                $em = EntityManager::getInstance();
                $em->persist($user);
                $em->flush();

                $submitted = isVaildID($user->get('user_id'));

                redirect(getPageURL('new-user', array( 'flag-submitted' => $submitted )));

            } else {
                // TODO: Display error "Sorry, You don't have the right capabilities."
            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('add_user')) {
            $view = new View('new-user');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
