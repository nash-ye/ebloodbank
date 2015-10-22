<?php
/**
 * Sign-up page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use DateTime;
use DateTimeZone;
use Swift_Message;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Notices;
use EBloodBank\Models\User;
use EBloodBank\Views\View;

/**
 * Sign-up page controller class
 *
 * @since 1.0
 */
class Signup extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if ('on' === Options::getOption('self_registration')) {
            $this->doActions();
            $view = View::forge('signup');
        } else {
            $view = View::forge('error-403');
        }
        $view();
    }

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

            $em = main()->getEntityManager();
            $userRepository = $em->getRepository('Entities:User');

            // Set the user name.
            $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

            // Set the user email.
            $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

            $duplicateUser = $userRepository->findOneBy(['email' => $user->get('email'), 'status' => 'any']);

            if (! empty($duplicateUser)) {
                throw new InvalidArgumentException(__('Please enter another e-mail address.'));
            }

            $userPass1 = filter_input(INPUT_POST, 'user_pass_1', FILTER_UNSAFE_RAW);
            $userPass2 = filter_input(INPUT_POST, 'user_pass_2', FILTER_UNSAFE_RAW);

            if (empty($userPass1)) {
                throw new InvalidArgumentException(__('Please enter your password.'));
            }

            if (empty($userPass2)) {
                throw new InvalidArgumentException(__('Please confirm your password.'));
            }

            if ($userPass1 !== $userPass2) {
                throw new InvalidArgumentException(__('Please enter the same password.'));
            }

            // Set the user password.
            $user->set('pass', password_hash($userPass1, PASSWORD_BCRYPT), false);

            // Set the user role.
            $user->set('role', Options::getOption('new_user_role'), true);

            // Set the user time.
            $user->set('created_at', new DateTime('now', new DateTimeZone('UTC')), true);

            // Set the user status.
            $user->set('status', Options::getOption('new_user_status'), true);

            $em = main()->getEntityManager();
            $em->persist($user);
            $em->flush();

            $signedup = $user->isExists();

            if ($signedup) {
                $mailer = main()->getMailer();
                $message = Swift_Message::newInstance();

                $message->setSubject(sprintf(__('[%s] New User Registration'), Options::getOption('site_name')));
                $message->setFrom(Options::getOption('site_email'));
                $message->setTo(Options::getOption('site_email'));

                $messageBody  = sprintf(__('New user registration on %s:'), Options::getOption('site_name')) . "\r\n\r\n";
                $messageBody .= sprintf(__('Username: %s'), $user->get('name')) . "\r\n";
                $messageBody .= sprintf(__('E-mail: %s'), $user->get('email')) . "\r\n";

                $message->setBody($messageBody, 'text/plain');

                $mailer->send($message);
            }

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getLoginURL(),
                    array('flag-signedup' => $signedup)
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_user_argument', $ex->getMessage());
        }
    }
}
