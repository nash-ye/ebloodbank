<?php
/**
 * Edit Donor Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Views\View;

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

                if (! isVaildID($donorID)) {
                    die(__('Invalid donor ID'));
                }

                $donor = EntityManager::getDonorReference($donorID);

                if (isset($_POST['donor_name'])) {
                    $donor->set('name', $_POST['donor_name'], true);
                }

                if (isset($_POST['donor_gender'])) {
                    $donor->set('gender', $_POST['donor_gender'], true);
                }

                if (isset($_POST['donor_weight'])) {
                    $donor->set('weight', $_POST['donor_weight'], true);
                }

                if (isset($_POST['donor_birthdate'])) {
                    $donor->set('birthdate', $_POST['donor_birthdate'], true);
                }

                if (isset($_POST['donor_blood_group'])) {
                    $donor->set('blood_group', $_POST['donor_blood_group'], true);
                }

                if (isset($_POST['donor_phone'])) {
                    $donor->set('phone', $_POST['donor_phone'], true);
                }

                if (isset($_POST['donor_email'])) {
                    $donor->set('email', $_POST['donor_email'], true);
                }

                if (isset($_POST['donor_address'])) {
                    $donor->set('address', $_POST['donor_address'], true);
                }

                if (isset($_POST['donor_distr_id'])) {
                    $donor->set('district', $_POST['donor_distr_id'], true);
                }

                $em = EntityManager::getInstance();
                $em->flush();

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
            $donorID = (int) $_GET['id'];
            $donor = EntityManager::getDonorRepository()->find($donorID);
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
