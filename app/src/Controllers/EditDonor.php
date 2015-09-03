<?php
/**
 * Edit Donor Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 */
class EditDonor extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('edit_donor')) {
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
        if (filter_has_var(INPUT_GET, 'flag-updated')) {
            Notices::addNotice('updated', __('Donor updated.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (isCurrentUserCan('edit_donor')) {

            try {

                $donor = $this->getQueriedDonor();
                $donorID = $this->getQueriedDonorID();

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
                $donor->updateMeta('weight', filter_input(INPUT_POST, 'donor_weight'));

                // Set the donor email address.
                $donor->updateMeta('email', filter_input(INPUT_POST, 'donor_email'));

                // Set the donor phone number.
                $donor->updateMeta('phone', filter_input(INPUT_POST, 'donor_phone'));

                // Set the donor address.
                $donor->updateMeta('address', filter_input(INPUT_POST, 'donor_address'));

                $em = main()->getEntityManager();
                $em->flush($donor);

                redirect(
                    addQueryArgs(
                        getEditDonorURL($donorID),
                        array('flag-updated' => true)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return \EBloodBank\Models\Donor
     * @since 1.0
     */
    protected function getQueriedDonor()
    {
        $route = main()->getRouter()->getMatchedRoute();

        if (empty($route)) {
            return;
        }

        if (! isset($route->params['id']) || ! isValidID($route->params['id'])) {
            return;
        }

        $donorRepository = main()->getEntityManager()->getRepository('Entities:Donor');
        $donor = $donorRepository->find((int) $route->params['id']);

        return $donor;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedDonorID()
    {
        return ($donor = $this->getQueriedDonor()) ? (int) $donor->get('id') : 0;
    }
}
