<?php
/**
 * Log-in page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * Log-in page controller class
 *
 * @since 1.0
 */
class Login extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $this->doActions();
        $this->addNotices();
        $view = View::forge('login');
        $view();
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
            case 'GET':
                if ('logout' === filter_input(INPUT_GET, 'action')) {
                    $this->doLogoutAction();
                }
                break;
            case 'POST':
                if ('login' === filter_input(INPUT_POST, 'action')) {
                    $this->doLoginAction();
                }
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
            Notices::addNotice('loggedout', __('You are now logged out.'), 'info');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doLoginAction()
    {
        $userEmail = filter_input(INPUT_POST, 'user_email');
        $userPass  = filter_input(INPUT_POST, 'user_pass');

        if (empty($userEmail) || empty($userPass)) {
            Notices::addNotice('empty_login_details', __('Please enter your login details.'), 'warning');
            return;
        }

        $em = $this->getContainer()->get('entity_manager');
        $userRepository = $em->getRepository('Entities:User');
        $user = $userRepository->findOneBy(array('email' => $userEmail, 'status' => 'any'));

        if (empty($user) || ! password_verify($userPass, $user->get('pass'))) {
            Notices::addNotice('wrong_login_details', __('No match for user e-mail and/or password.'), 'warning');
            return;
        }

        if ($user->isPending()) {
            Notices::addNotice('account_pending_moderation', __('Your account is pending moderation.'), 'warning');
            return;
        }

        $session = $this->getContainer()->get('session');
        $segment = $session->getSegment('EBloodBank');
        $segment->set('user_id', (int) $user->get('id'));
        $session->regenerateId();

        EBB\redirect(EBB\getHomeURL());
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doLogoutAction()
    {
        if (EBB\isUserLoggedIn()) {
            $session = $this->getContainer()->get('session');
            $session->destroy();
            $session->start();
            $session->regenerateId();
            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getLoginURL(),
                    array('flag-loggedout' => true)
                )
            );
        }
    }
}
