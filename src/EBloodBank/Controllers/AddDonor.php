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
use EBloodBank\Options;
use EBloodBank\Notices;
use EBloodBank\Models\Donor;
use EBloodBank\Views\View;
use Psr\Container\ContainerInterface;

/**
 * Add donor page controller class
 *
 * @since 1.0
 */
class AddDonor extends Controller
{
    /**
     * @var \EBloodBank\Models\Donor
     * @since 1.0
     */
    protected $donor;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct(ContainerInterface $container)
    {
        $this->donor = new Donor();
        parent::__construct($container);
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if ($this->hasAuthenticatedUser() && $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'add')) {
            $this->doActions();
            $this->addNotices();
            $donor = $this->getQueriedDonor();
            $view = View::forge('add-donor', [
                'donor' => $donor,
            ]);
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
        try {
            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'add')) {
                return;
            }

            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $donor = $this->getQueriedDonor();
            $districtRepository = $this->getEntityManager()->getRepository('Entities:District');

            // Set the donor name.
            $donor->set('name', filter_input(INPUT_POST, 'donor_name'), true);

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
            $donor->set('created_by', $this->getAuthenticatedUser());

            // Set the donor status.
            if ($this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
                $donor->set('status', 'approved');
            } else {
                $donor->set('status', 'pending');
            }

            // Set the donor weight.
            $donor->setMeta('weight', filter_input(INPUT_POST, 'donor_weight'), true);

            // Set the donor email address.
            $donor->setMeta('email', filter_input(INPUT_POST, 'donor_email'), true);

            // Set the donor email address visibility.
            $donor->setMeta('email_visibility', filter_input(INPUT_POST, 'donor_email_visibility'), true);

            // Set the donor phone number.
            $donor->setMeta('phone', filter_input(INPUT_POST, 'donor_phone'), true);

            // Set the donor phone number visibility.
            $donor->setMeta('phone_visibility', filter_input(INPUT_POST, 'donor_phone_visibility'), true);

            // Set the donor address.
            $donor->setMeta('address', filter_input(INPUT_POST, 'donor_address'), true);

            $this->getEntityManager()->persist($donor);
            $this->getEntityManager()->flush();

            $added = $donor->isExists();

            if ($added) {
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

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getAddDonorURL(),
                    ['flag-added' => $added]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_donor_argument', $ex->getMessage());
        }
    }

    /**
     * @return \EBloodBank\Models\Donor
     * @since 1.0
     */
    protected function getQueriedDonor()
    {
        return $this->donor;
    }
}
