<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Sessions;
use eBloodBank\Kernal\Controller;

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

            if (isCurrentUserCan('add_donor') || (! Sessions::isSignedIn() && isAnonymousCan('add_donor'))) {
                $donor_data = array();

                if (isset($_POST['donor_name'])) {
                    $donor_data['donor_name'] = filter_var($_POST['donor_name'], FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['donor_gender'])) {
                    if (in_array($_POST['donor_gender'], array_keys(Models\Donor::$genders), true)) {
                        $donor_data['donor_gender'] = $_POST['donor_gender'];
                    }
                }

                if (isset($_POST['donor_weight'])) {
                    $donor_data['donor_weight'] = filter_var($_POST['donor_weight'], FILTER_SANITIZE_NUMBER_FLOAT);
                }

                if (isset($_POST['donor_birthdate'])) {
                    $donor_data['donor_birthdate'] = filter_var($_POST['donor_birthdate'], FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['donor_blood_group'])) {
                    if (in_array($_POST['donor_blood_group'], Models\Donor::$blood_groups, true)) {
                        $donor_data['donor_blood_group'] = $_POST['donor_blood_group'];
                    }
                }

                if (isset($_POST['donor_phone'])) {
                    $donor_data['donor_phone'] = filter_var($_POST['donor_phone'], FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['donor_email'])) {
                    $donor_data['donor_email'] = filter_var($_POST['donor_email'], FILTER_SANITIZE_EMAIL);
                }

                if (isset($_POST['donor_address'])) {
                    $donor_data['donor_address'] = filter_var($_POST['donor_address'], FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['donor_distr_id'])) {
                    $donor_data['donor_distr_id'] = (int) $_POST['donor_distr_id'];
                }

                if (isCurrentUserCan('approve_donor')) {
                    $donor_data['donor_status'] = 'approved';
                }

                $donor_id = Models\Donors::insert($donor_data);
                $submitted = isVaildID($donor_id);

                redirect(getSiteURL(array(
                    'page' => 'new-donor',
                    'flag-submitted' => $submitted,
                )));
            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('add_donor') || (! Sessions::isSignedIn() && isAnonymousCan('add_donor'))) {
            $view = new View('new-donor');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
