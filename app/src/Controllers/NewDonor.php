<?php
/**
 * New Donor Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Models\Donor;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class NewDonor extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('add_donor')) {

            try {

                $donor = new Donor();

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

                if (isCurrentUserCan('approve_donor')) {
                    $donor->set('status', 'published');
                } else {
                    $donor->set('status', 'pending');
                }

                $donor->set('rtime', gmdate('Y-m-d H:i:s'));

                $em = EntityManager::getInstance();
                $em->persist($donor);
                $em->flush();

                $submitted = isVaildID($donor->get('id'));

                redirect(
                    getPageURL('new-donor', array(
                        'flag-submitted' => $submitted
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

        if (isCurrentUserCan('add_donor')) {
            $view = new View('new-donor');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
