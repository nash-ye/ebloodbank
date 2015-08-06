<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

/**
 * @since 1.0
 */
class EditDonor extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('edit_donor')) {

            try {

                $donorID = (int) $_GET['id'];
                $donor = EntityManager::getDonorReference($donorID);

                if (isset($_POST['donor_name'])) {
                    $donor->set('donor_name', $_POST['donor_name'], true);
                }

                if (isset($_POST['donor_gender'])) {
                    $donor->set('donor_gender', $_POST['donor_gender'], true);
                }

                if (isset($_POST['donor_weight'])) {
                    $donor->set('donor_weight', $_POST['donor_weight'], true);
                }

                if (isset($_POST['donor_birthdate'])) {
                    $donor->set('donor_birthdate', $_POST['donor_birthdate'], true);
                }

                if (isset($_POST['donor_blood_group'])) {
                    $donor->set('donor_blood_group', $_POST['donor_blood_group'], true);
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

                EntityManager::getInstance()->flush();

                redirect(
                    getPageURL('edit-donor', array(
                        'id' => $donorID,
                        'flag-submitted' => true
                    ))
                );

            } catch (Exceptions\InvaildProperty $ex) {
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
        if (! empty($_POST['action'])) {
            switch ($_POST['action']) {
                case 'submit_donor':
                    $this->action_submit();
                    break;
            }
        }

        if (isCurrentUserCan('edit_donor')) {
            $donor = EntityManager::getDonorRepository()->find((int) $_GET['id']);
            if (! empty($donor)) {
                $view = new View('edit-donor', array( 'donor' => $donor ));
            } else {
                $view = new View('error-404');
            }
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
