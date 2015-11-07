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
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

/**
 * Edit donor page controller class
 *
 * @since 1.0
 */
class EditDonor extends Controller
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
    public function __construct(ContainerInterface $container, $id)
    {
        parent::__construct($container);
        if (EBB\isValidID($id)) {
            $donorRepository = $container->get('entity_manager')->getRepository('Entities:Donor');
            $this->donor = $donorRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canEditDonors()) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedDonorExists()) {
            View::display('error-404');
            return;
        }

        $donor = $this->getQueriedDonor();

        if (! $currentUser->canEditDonor($donor)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        View::display('edit-donor', [
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
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            $session = $this->getContainer()->get('session');
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $currentUser = EBB\getCurrentUser();
            $donor = $this->getQueriedDonor();

            if (! $currentUser || ! $currentUser->canEditDonor($donor)) {
                return;
            }

            $em = $this->getContainer()->get('entity_manager');
            $districtRepository = $em->getRepository('Entities:District');

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

            // Set the donor weight.
            $donor->updateMeta('weight', filter_input(INPUT_POST, 'donor_weight'), $donor->getMeta('weight'), true);

            // Set the donor email address.
            $donor->updateMeta('email', filter_input(INPUT_POST, 'donor_email'), $donor->getMeta('email'), true);

            // Set the donor phone number.
            $donor->updateMeta('phone', filter_input(INPUT_POST, 'donor_phone'), $donor->getMeta('phone'), true);

            // Set the donor address.
            $donor->updateMeta('address', filter_input(INPUT_POST, 'donor_address'), $donor->getMeta('address'), true);

            // Set the donor status.
            if ($donor->isApproved() && ! $currentUser->canApproveDonors()) {
                $donor->set('status', 'pending');
            }

            $em->flush($donor);

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

    /**
     * @return \EBloodBank\Models\Donor
     * @since 1.0
     */
    protected function getQueriedDonor()
    {
        return $this->donor;
    }

    /**
     * @return bool
     * @since 1.2
     */
    protected function isQueriedDonorExists()
    {
        $donor = $this->getQueriedDonor();
        return ($donor && $donor->isExists());
    }
}
