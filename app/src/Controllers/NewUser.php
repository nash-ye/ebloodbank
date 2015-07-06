<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models\Users;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

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
                $user_data = array();

                if (isset($_POST['user_logon'])) {
                    $user_data['user_logon'] = filter_var($_POST['user_logon'], FILTER_SANITIZE_STRING);
                }

                if (! empty($_POST['user_pass'])) {
                    $user_data['user_pass'] = password_hash($_POST['user_pass'], PASSWORD_BCRYPT);
                }

                if (isset($_POST['user_role'])) {
                    $user_data['user_role'] = filter_var($_POST['user_role'], FILTER_SANITIZE_STRING);
                }

                $user_id = Users::insert($user_data);
                $submitted = isVaildID($user_id);

                redirect(getSiteURL(array(
                    'page' => 'new-user',
                    'flag-submitted' => $submitted,
                )));
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
