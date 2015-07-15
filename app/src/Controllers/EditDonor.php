<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;
use eBloodBank\Kernal\Options;

/**
 * @since 1.0
 */
class EditDonor extends Controller
{
    /**
     * @var int
     * @since 1.0
     */
    protected $id = 0;

    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        $this->id = (int) $_GET['id'];

        if (! isVaildID($this->id)) {
            die('Invaild donor ID');
        }

        if (isset($_POST['action']) && 'submit_donor' === $_POST['action']) {

            if (isCurrentUserCan('edit_donor')) {

                $em = EntityManager::getInstance();
                $donor = $em->getDonorReference($this->id);

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
                    $donor->set('donor_distr_id', (int) $_POST['donor_distr_id']);
                }

                if (isCurrentUserCan('approve_donor')) {
                    $donor->set('donor_status', 'approved');
                }

                $em->flush();
                $submitted = isVaildID($donor->get('donor_id'));

                redirect(getPageURL('edit-donor', array( 'id' => $this->id, 'flag-submitted' => $submitted )));

            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('edit_donor')) {
            $view = new View('edit-donor');
            $view(array( 'id' => $this->id ));
        } else {
            $view = new View('error-401');
            $view();
        }
    }
}
