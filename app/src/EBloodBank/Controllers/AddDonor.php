<?php
/**
 * Add donor page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Models\Donor;
use EBloodBank\Views\View;

/**
 * Add donor page controller class
 *
 * @since 1.0
 */
class AddDonor extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('add_donor')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('add-donor');
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
            case 'submit_donor':
                $this->doSubmitAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-added')) {
            Notices::addNotice('added', __('Donor added.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (EBB\isCurrentUserCan('add_donor')) {
            try {
                $donor = new Donor();

                $session = main()->getSession();
                $sessionToken = $session->getCsrfToken();
                $actionToken = filter_input(INPUT_POST, 'token');

                if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                    return;
                }

                // Set the donor name.
                $donor->set('name', filter_input(INPUT_POST, 'donor_name'), true);

                // Set the donor gender.
                $donor->set('gender', filter_input(INPUT_POST, 'donor_gender'), true);

                // Set the donor birthdate.
                $donor->set('birthdate', filter_input(INPUT_POST, 'donor_birthdate'), true);

                // Set the donor blood group.
                $donor->set('blood_group', filter_input(INPUT_POST, 'donor_blood_group'), true);

                // Set the donor district ID.
                $donor->set('district', filter_input(INPUT_POST, 'donor_district_id'), true);

                $donor->set('created_at', new DateTime('now', new DateTimeZone('UTC')), true);
                $donor->set('created_by', EBB\getCurrentUserID(), true);

                // Set the donor status.
                if (EBB\isCurrentUserCan('approve_donor')) {
                    $donor->set('status', 'approved');
                } else {
                    $donor->set('status', 'pending');
                }

                $em = main()->getEntityManager();
                $em->persist($donor);
                $em->flush();

                // Set the donor weight.
                $donor->addMeta('weight', filter_input(INPUT_POST, 'donor_weight'), true);

                // Set the donor email address.
                $donor->addMeta('email', filter_input(INPUT_POST, 'donor_email'), true);

                // Set the donor phone number.
                $donor->addMeta('phone', filter_input(INPUT_POST, 'donor_phone'), true);

                // Set the donor address.
                $donor->addMeta('address', filter_input(INPUT_POST, 'donor_address'), true);

                $added = $donor->isExists();

                if ($added) {
                    $mailer = main()->getMailer();
                    $message = Swift_Message::newInstance();

                    $message->setSubject(sprintf(__('[%s] New Donor'), Options::getOption('site_name')));
                    $message->setTo(Options::getOption('site_email'));

                    $messageBody  = sprintf(__('New donor addition on %s:'), Options::getOption('site_name')) . "\r\n\r\n";
                    $messageBody .= sprintf(__('Name: %s'), $donor->get('name')) . "\r\n";
                    $messageBody .= sprintf(__('Gender: %s'), $donor->getGenderTitle()) . "\r\n";
                    $messageBody .= sprintf(__('Blood Group: %s'), $donor->get('blood_group')) . "\r\n";
                    $messageBody .= sprintf(__('City\District: %s\%s'), $donor->get('district')->get('city')->get('name'), $donor->get('district')->get('name'));

                    $message->setBody($messageBody, 'text/plain');

                    $mailer->send($message);
                }

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getAddDonorURL(),
                        array('flag-added' => $added)
                    )
                );
            } catch (InvalidArgumentException $ex) {
                Notices::addNotice('invalid_donor_argument', $ex->getMessage());
            }
        }
    }
}
