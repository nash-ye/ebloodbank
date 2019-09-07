<?php
/**
 * Sign-up page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Notices;
use EBloodBank\Models\User;
use EBloodBank\Models\Donor;
use Symfony\Component\EventDispatcher\GenericEvent;

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
            $view = $this->viewFactory->forgeView('signup');
        } else {
            $view = $this->viewFactory->forgeView('error-403');
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

            // Set the user name.
            $user->set('name', filter_input(INPUT_POST, 'user_name'), true);

            // Set the user email.
            $user->set('email', filter_input(INPUT_POST, 'user_email'), true);

            $duplicateUser = $this->getUserRepository()->findOneBy(['email' => $user->get('email'), 'status' => 'any']);

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

            // Set the user status.
            $user->set('status', Options::getOption('new_user_status'), true);

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            $signedup = $user->isExists();

            $this->getEventManager()->getEventDispatcher()->dispatch('user.created', new GenericEvent($user));

            $addDonor = filter_input(INPUT_POST, 'add_as_a_donor');

            if ($addDonor) {
                $donor = new Donor();

                // Set the donor name.
                $donor->set('name', filter_input(INPUT_POST, 'user_name'), true);

                // Set the donor gender.
                $donor->set('gender', filter_input(INPUT_POST, 'donor_gender'), true);

                // Set the donor birthdate.
                $donor->set('birthdate', filter_input(INPUT_POST, 'donor_birthdate'), true);

                // Set the donor blood group.
                $donor->set('blood_group', filter_input(INPUT_POST, 'donor_blood_group'), true);

                // Set the donor district ID.
                $donor->set('district', $this->getDistrictRepository()->find(filter_input(INPUT_POST, 'donor_district_id')));

                // Set the originator user.
                $donor->set('created_by', $user);

                // Set the donor status.
                $donor->set('status', 'pending');

                // Set the donor weight.
                $donor->setMeta('weight', filter_input(INPUT_POST, 'donor_weight'), true);

                // Set the donor email address.
                $donor->setMeta('email', filter_input(INPUT_POST, 'user_email'), true);

                // Set the donor email address visibility.
                $donor->setMeta('email_visibility', Options::getOption('default_donor_email_visibility'), true);

                // Set the donor phone number.
                $donor->setMeta('phone', filter_input(INPUT_POST, 'donor_phone'), true);

                // Set the donor phone number visibility.
                $donor->setMeta('phone_visibility', Options::getOption('default_donor_phone_visibility'), true);

                // Set the donor address.
                $donor->setMeta('address', filter_input(INPUT_POST, 'donor_address'), true);

                $this->getEntityManager()->persist($donor);
                $this->getEntityManager()->flush();

                $this->getEventManager()->getEventDispatcher()->dispatch('donor.created', new GenericEvent($donor));
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
