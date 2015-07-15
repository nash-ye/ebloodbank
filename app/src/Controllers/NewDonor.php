<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\SessionManage;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Options;
use eBloodBank\Kernal\Controller;
use eBloodBank\Models\Donor;

/**
 * @since 1.0
 */
class NewDonor extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_POST['action']) && 'submit_donor' === $_POST['action']) {

            if (isCurrentUserCan('add_donor') || (! SessionManage::isSignedIn() && isAnonymousCan('add_donor'))) {
                $donor = new Donor();

                if (isset($_POST['donor_name'])) {
                    $donor->set('donor_name', $_POST['donor_name'], true);
                }

                if (isset($_POST['donor_gender'])) {
                    if (in_array($_POST['donor_gender'], array_keys(Options::get_option('genders')), true)) {
                        $donor->set('donor_gender', $_POST['donor_gender'], true);
                    }
                }

                if (isset($_POST['donor_weight'])) {
                    $donor->set('donor_weight', $_POST['donor_weight'], true);
                }

                if (isset($_POST['donor_birthdate'])) {
                    $donor->set('donor_birthdate', $_POST['donor_birthdate'], true);
                }

                if (isset($_POST['donor_blood_group'])) {
                    if (in_array($_POST['donor_blood_group'], Options::get_option('blood_groups'), true)) {
                        $donor->set('donor_blood_group', $_POST['donor_blood_group'], true);
                    }
                }

                if (isset($_POST['donor_phone'])) {
                    $donor->set('donor_phone', $_POST['donor_phone'], true);
                }

                if (isset($_POST['donor_email'])) {
                    $donor->set('donor_email', $_POST['donor_email'], true);
                }

                if (isset($_POST['donor_address'])) {
                    $donor->set('donor_address', $_POST['donor_address'], true);
                }

                if (isset($_POST['donor_distr_id'])) {
                    $donor->set('donor_distr_id', $_POST['donor_distr_id'], true);
                }

                if (isCurrentUserCan('approve_donor')) {
                    $donor->set('donor_status', 'approved');
                }

                $donor->set('donor_rtime', gmdate('Y-m-d H:i:s'));

                $em = EntityManager::getInstance();
                $em->persist($donor);
                $em->flush();

                $submitted = isVaildID($donor->get('donor_id'));

                redirect(getPageURL('new-donor', array( 'flag-submitted' => $submitted )));

            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('add_donor') || (! SessionManage::isSignedIn() && isAnonymousCan('add_donor'))) {
            $view = new View('new-donor');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
