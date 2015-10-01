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
    public function __construct($id)
    {
        if (EBB\isValidID($id)) {
            $donorRepository = main()->getEntityManager()->getRepository('Entities:Donor');
            $this->donor = $donorRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('edit_donor')) {
            $donor = $this->getQueriedDonor();
            if (! empty($donor)) {
                $this->doActions();
                $this->addNotices();
                $view = View::forge('edit-donor', array(
                    'donor' => $donor,
                ));
            } else {
                $view = View::forge('error-404');
            }
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
        if (EBB\isCurrentUserCan('edit_donor')) {
            try {
                $donor = $this->getQueriedDonor();
                $donorID = $this->getQueriedDonorID();

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

                // Set the donor weight.
                $donor->updateMeta('weight', filter_input(INPUT_POST, 'donor_weight'), $donor->getMeta('weight'), true);

                // Set the donor email address.
                $donor->updateMeta('email', filter_input(INPUT_POST, 'donor_email'), $donor->getMeta('email'), true);

                // Set the donor phone number.
                $donor->updateMeta('phone', filter_input(INPUT_POST, 'donor_phone'), $donor->getMeta('phone'), true);

                // Set the donor address.
                $donor->updateMeta('address', filter_input(INPUT_POST, 'donor_address'), $donor->getMeta('address'), true);

                $em = main()->getEntityManager();
                $em->flush($donor);

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getEditDonorURL($donorID),
                        array('flag-edited' => true)
                    )
                );
            } catch (InvalidArgumentException $ex) {
                Notices::addNotice('invalid_donor_argument', $ex->getMessage());
            }
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
     * @return int
     * @since 1.0
     */
    protected function getQueriedDonorID()
    {
        $donor = $this->getQueriedDonor();
        return ($donor) ? (int) $donor->get('id') : 0;
    }
}
