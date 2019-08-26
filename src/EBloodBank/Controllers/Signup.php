<?php
/**
 * Sign-up page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Notices;
use EBloodBank\Models\User;
use EBloodBank\Models\Donor;
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

            $entityManager = $this->getContainer()->get('entity_manager');
            $userRepository = $entityManager->getRepository('Entities:User');

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

            $entityManager->persist($user);
            $entityManager->flush();

            $signedup = $user->isExists();

            if ($signedup) {
                $mailer = $this->getContainer()->get('mailer');
                $message = $mailer->createMessage();

                $message->setSubject(sprintf(__('[%s] New User Registration'), Options::getOption('site_name')));
                $message->setFrom(Options::getOption('site_email'));
                $message->setTo(Options::getOption('site_email'));

                $messageBody  = sprintf(__('New user registration on %s:'), Options::getOption('site_name')) . "\r\n\r\n";
                $messageBody .= sprintf(__('Username: %s'), $user->get('name')) . "\r\n";
                $messageBody .= sprintf(__('E-mail: %s'), $user->get('email')) . "\r\n";

                $message->setBody($messageBody, 'text/plain');

                $mailer->send($message);
            }

            $addDonor = filter_input(INPUT_POST, 'add_as_a_donor');

            if ($addDonor) {
                $donor = new Donor();
                $districtRepository = $entityManager->getRepository('Entities:District');

                // Set the donor name.
                $donor->set('name', filter_input(INPUT_POST, 'user_name'), true);

                // Set the donor gender.
                $donor->set('gender', filter_input(INPUT_POST, 'donor_gender'), true);

                // Set the donor birthdate.
                $donor->set('birthdate', filter_input(INPUT_POST, 'donor_birthdate'), true);

                // Set the donor blood group.
                $donor->set('blood_group', filter_input(INPUT_POST, 'donor_blood_group'), true);

                // Set the donor district ID.
                $donor->set('district', $districtRepository->find(filter_input(INPUT_POST, 'donor_district_id')));

                // Set the creation date.
                $donor->set('created_at', new DateTime('now', new DateTimeZone('UTC')));

                // Set the originator user.
                $donor->set('created_by', $user);

                // Set the donor status.
                $donor->set('status', 'pending');

                $entityManager->persist($donor);
                $entityManager->flush();

                // Set the donor weight.
                $donor->addMeta('weight', filter_input(INPUT_POST, 'donor_weight'), true);

                // Set the donor email address.
                $donor->addMeta('email', filter_input(INPUT_POST, 'user_email'), true);

                // Set the donor email address visibility.
                $donor->addMeta('email_visibility', Options::getOption('default_donor_email_visibility'), true);

                // Set the donor phone number.
                $donor->addMeta('phone', filter_input(INPUT_POST, 'donor_phone'), true);

                // Set the donor phone number visibility.
                $donor->addMeta('phone_visibility', Options::getOption('default_donor_phone_visibility'), true);

                // Set the donor address.
                $donor->addMeta('address', filter_input(INPUT_POST, 'donor_address'), true);

                $donorAdded = $donor->isExists();

                if ($donorAdded) {
                    $mailer = $this->getContainer()->get('mailer');
                    $message = $mailer->createMessage();

                    $message->setSubject(sprintf(__('[%s] New Donor'), Options::getOption('site_name')));
                    $message->setFrom(Options::getOption('site_email'));
                    $message->setTo(Options::getOption('site_email'));

                    $messageBody  = sprintf(__('New donor addition on %s:'), Options::getOption('site_name')) . "\r\n\r\n";
                    $messageBody .= sprintf(__('Name: %s'), $donor->get('name')) . "\r\n";
                    $messageBody .= sprintf(__('Gender: %s'), $donor->getGenderTitle()) . "\r\n";
                    $messageBody .= sprintf(__('Blood Group: %s'), $donor->get('blood_group')) . "\r\n";
                    $messageBody .= sprintf(__('City\District: %1$s\%2$s'), $donor->get('district')->get('city')->get('name'), $donor->get('district')->get('name'));

                    $message->setBody($messageBody, 'text/plain');

                    $mailer->send($message);
                }
            }

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getLoginURL(),
                    ['flag-signedup' => $signedup]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_user_argument', $ex->getMessage());
        }
    }
}
