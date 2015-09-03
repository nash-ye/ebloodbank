<?php
/**
 * Add Donor Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Models\Donor;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
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
        if (isCurrentUserCan('add_donor')) {
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
        if (isCurrentUserCan('add_donor')) {

            try {

                $donor = new Donor();

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

                $donor->set('created_at', gmdate('Y-m-d H:i:s'));
                $donor->set('created_by', getCurrentUserID());

                // Set the donor status.
                if (isCurrentUserCan('approve_donor')) {
                    $donor->set('status', 'approved');
                } else {
                    $donor->set('status', 'pending');
                }

                $em = main()->getEntityManager();
                $em->persist($donor);
                $em->flush();

                // Set the donor weight.
                $donor->addMeta('weight', filter_input(INPUT_POST, 'donor_weight', FILTER_SANITIZE_NUMBER_FLOAT));

                // Set the donor email address.
                $donor->addMeta('email', filter_input(INPUT_POST, 'donor_email', FILTER_SANITIZE_EMAIL));

                // Set the donor phone number.
                $donor->addMeta('phone', filter_input(INPUT_POST, 'donor_phone', FILTER_SANITIZE_STRING));

                // Set the donor address.
                $donor->addMeta('address', filter_input(INPUT_POST, 'donor_address', FILTER_SANITIZE_STRING));

                $added = isValidID($donor->get('id'));

                redirect(
                    addQueryArgs(
                        getAddDonorURL(),
                        array('flag-added' => $added)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }
}
