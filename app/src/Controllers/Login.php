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
    protected function doActions()
    {
        switch (filter_input(INPUT_REQUEST, 'action')) {
            case 'login':
                $this->doLoginAction();
                break;
            case 'logout':
                $this->doLogoutAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-loggedout')) {
            Notices::addNotice('loggedout', __('You are now logged out.'), 'message');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doLoginAction()
    {
        $userName = filter_input(INPUT_POST, 'user_logon');
        $userPass  = filter_input(INPUT_POST, 'user_pass');

        if (empty($userName) || empty($userPass)) {
            Notices::addNotice('empty_login_details', __('Please enter your login details.'), 'warning');
            return;
        }

        $userRepository = EntityManager::getUserRepository();
        $user = $userRepository->findOneBy(array( 'logon' => $userName, 'status' => 'any' ));

        if (empty($user) || ! password_verify($userPass, $user->get('pass'))) {
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
    protected function doLogoutAction()
    {
        if (isUserLoggedIn()) {
            session_destroy();
            $_SESSION = array();
            redirect(
                addQueryArgs(
                    getLoginURL(),
                    array('flag-loggedout' => true)
                )
            );
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $this->doActions();
        $this->addNotices();
        $view = View::instance('login');
        $view();
    }
}
