<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
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
		$this->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

		if (! isVaildID($this->id)) {
			die('Invaild user ID');
		}

        if (isset($_POST['action']) && 'submit_user' === $_POST['action']) {
            if (isCurrentUserCan('edit_user')) {
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

                $user_id = Models\Users::update($this->id, $user_data);
                $submitted = isVaildID($user_id);

                redirect(getSiteURL(array(
                    'page' => 'edit-user',
					'id' => $this->id,
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
        if (isCurrentUserCan('edit_user')) {
            $view = new View('edit-user');
			$view(array( 'id' => $this->id ));
        } else {
            $view = new View('error-401');
			$view();
        }
    }
}
