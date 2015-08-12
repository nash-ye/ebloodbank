<?php
/**
 * Add Donor Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Models\Donor;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class AddDonor extends Controller
{
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
    protected function doSubmitAction()
    {
        if (isCurrentUserCan('add_donor')) {

            try {

                $donor = new Donor();

                // Set the donor name.
                $donor->set('name', filter_input(INPUT_POST, 'donor_name'), true);

                // Set the donor gender.
                $donor->set('gender', filter_input(INPUT_POST, 'donor_gender'), true);

                // Set the donor weight.
                $donor->set('weight', filter_input(INPUT_POST, 'donor_weight'), true);

                // Set the donor birthdate.
                $donor->set('birthdate', filter_input(INPUT_POST, 'donor_birthdate'), true);

                // Set the donor blood group.
                $donor->set('blood_group', filter_input(INPUT_POST, 'donor_blood_group'), true);

                // Set the donor phone number.
                $donor->set('phone', filter_input(INPUT_POST, 'donor_phone'), true);

                // Set the donor email address.
                $donor->set('email', filter_input(INPUT_POST, 'donor_email'), true);

                // Set the donor address.
                $donor->set('address', filter_input(INPUT_POST, 'donor_address'), true);

                // Set the donor district ID.
                $donor->set('district', filter_input(INPUT_POST, 'donor_distr_id'), true);

                // Set the donor time.
                $donor->set('rtime', gmdate('Y-m-d H:i:s'));

                // Set the donor status.
                if (isCurrentUserCan('approve_donor')) {
                    $donor->set('status', 'published');
                } else {
                    $donor->set('status', 'pending');
                }

                $em = EntityManager::getInstance();
                $em->persist($donor);
                $em->flush();

                $submitted = isVaildID($donor->get('id'));

                redirect(
                    addQueryArgs(
                        getAddDonorURL(),
                        array('flag-submitted' => $submitted)
                    )
                );

            } catch (Exceptions\InvaildArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('add_donor')) {
            $this->doActions();
            $view = View::instance('add-donor');
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
