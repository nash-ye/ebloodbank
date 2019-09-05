<?php
/**
 * Edit donor page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use Psr\Container\ContainerInterface;

/**
 * Edit donor page controller class
 *
 * @since 1.0
 */
class EditDonor extends Controller
{
    /**
     * @var   int
     * @since 1.6
     */
    protected $donorId = 0;

    /**
     * @var \EBloodBank\Models\Donor|null
     * @since 1.0
     */
    protected $donor;

    /**
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $donorId)
    {
        parent::__construct($container);
        if (EBB\isValidID($donorId)) {
            $this->donorId = (int) $donorId;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'edit')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->donorId) {
            $this->donor = $this->getDonorRepository()->find($this->donorId);
        }

        if (! $this->donor) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $donor = $this->donor;

        if (! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $donor)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView('edit-donor', [
            'donor' => $donor,
        ]);
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
        if (filter_has_var(INPUT_GET, 'flag-edited')) {
            Notices::addNotice('edited', __('Donor edited.'), 'success');
        }
        if ($this->donor && $this->donor->isPending()) {
            Notices::addNotice('pending', __('This donor is pendng moderation.'), 'warning');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $donor = $this->donor;

            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $donor)) {
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
            $donor->set('district', $this->getDistrictRepository()->find(filter_input(INPUT_POST, 'donor_district_id')));

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

            // Set the donor status.
            if ($donor->isApproved() && ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
                $donor->set('status', 'pending');
            }

            $this->getEntityManager()->flush($donor);

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditDonorURL($donor->get('id')),
                    ['flag-edited' => true]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_donor_argument', $ex->getMessage());
        }
    }
}
