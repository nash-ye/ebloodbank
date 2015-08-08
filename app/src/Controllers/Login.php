<?php
/**
 * Log-in Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class Login extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_login()
    {
        $user_name = '';
        $user_pass  = '';

        if (! empty($_POST['user_logon'])) {
            $user_name = $_POST['user_logon'];
        }

        if (! empty($_POST['user_pass'])) {
            $user_pass = $_POST['user_pass'];
        }

        if (empty($user_name) || empty($user_pass)) {
            Notices::addNotice('empty_login_details', __('Please enter your login details.'), 'warning');
            return;
        }

        $userRepository = EntityManager::getUserRepository();
        $user = $userRepository->findOneBy(array( 'logon' => $user_name, 'status' => 'any' ));

        if (empty($user) || ! password_verify($user_pass, $user->get('pass'))) {
            Notices::addNotice('wrong_login_details', __('No match for username and/or password.'), 'warning');
            return;
        }

        if ($user->isPending()) {
            Notices::addNotice('account_pending_moderation', __('Your account is pending moderation.'), 'warning');
            return;
        }

        $_SESSION['user_id'] = (int) $user->get('id');

        session_regenerate_id(true);

        redirect(getSiteURL('/'));
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function action_logout()
    {
        if (isUserLoggedIn()) {
            session_destroy();
            $_SESSION = array();
            redirect(
                getPageURL('login', array(
                    'flag-loggedout' => true,
                ))
            );
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! empty($_REQUEST['action'])) {
            switch ($_REQUEST['action']) {
                case 'login':
                    $this->action_login();
                    break;
                case 'logout':
                    $this->action_logout();
                    break;
            }
        }

        if (isset($_GET['flag-loggedout']) && $_GET['flag-loggedout']) {
            Notices::addNotice('loggedout', __('You are now logged out.'), 'message');
        }

        $view = new View('login');
        $view();
    }
}
