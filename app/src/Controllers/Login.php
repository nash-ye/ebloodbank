<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

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
            Notices::addNotice('error_login', __('Please enter your login details.'), 'warning');
            return;
        }

        $userRepository = EntityManager::getUserRepository();
        $user = $userRepository->findOneBy(array( 'user_logon' => $user_name ));

        if (empty($user) || ! password_verify($user_pass, $user->get('user_pass'))) {
            Notices::addNotice('invalid_combo', __('Invalid username or e-mail.'), 'warning');
            return;
        }

        if ($user->isPending()) {
            Notices::addNotice('check_email', __('Check your e-mail for the confirmation link.'), 'warning');
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user_id'] = (int) $user->get('user_id');

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
