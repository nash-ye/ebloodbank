<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class EditUser extends Controller
{
    /**
     * @var int
     * @since 1.0
     */
    protected $id = 0;

    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        $this->id = (int) $_GET['id'];

        if (! isVaildID($this->id)) {
            die('Invaild user ID');
        }

        if (isset($_POST['action']) && 'submit_user' === $_POST['action']) {
            if (isCurrentUserCan('edit_user')) {

                $em = EntityManager::getInstance();
                $user = $em->getUserReference($this->id);

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
                    $user->set('user_role', $_POST['user_role'], true);
                }

                $em->flush();

                $submitted = isVaildID($user->get('user_id'));

                redirect(getPageURL('edit-user', array( 'id' => $this->id, 'flag-submitted' => $submitted )));

            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('edit_user')) {
            $view = new View('edit-user');
            $view(array( 'id' => $this->id ));
        } else {
            $view = new View('error-401');
            $view();
        }
    }
}
