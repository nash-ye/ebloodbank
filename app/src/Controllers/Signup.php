<?php
/**
 * Sign-up Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Models\User;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class Signup extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'signup':
                $this->doSignupAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSignupAction()
    {
        try {

            $user = new User();

            // Set the user logon.
            $user->set('logon', filter_input(INPUT_POST, 'user_logon'), true);

            $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
            $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

            if (empty($userPass1)) {
                throw new Exceptions\InvaildArgument(__('Please enter your password.'), 'user_pass');
            }

            if (empty($userPass2)) {
                throw new Exceptions\InvaildArgument(__('Please confirm your password.'), 'user_pass');
            }

            if ($userPass1 !== $userPass2) {
                throw new Exceptions\InvaildArgument(__('Please enter the same password.'), 'user_pass');
            }

            // Set the user password.
            $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);

            // Set the user role.
            $user->set('role', 'default');

            // Set the user time.
            $user->set('rtime', gmdate('Y-m-d H:i:s'));

            // Set the user status.
            $user->set('status', 'pending');

            $em = EntityManager::getInstance();
            $em->persist($user);
            $em->flush();

            $submitted = isVaildID($user->get('id'));

            redirect(
                 addQueryArgs(
                    getLoginURL(),
                    array('flag-signedup' => $submitted)
                 )
             );

        } catch (Exceptions\InvaildArgument $ex) {
            Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $this->doActions();
        $view = View::instance('signup');
        $view();
    }
}
