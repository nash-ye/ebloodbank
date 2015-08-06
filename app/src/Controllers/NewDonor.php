<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Models\Donor;

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
                    $donor->set('donor_distr_id', $_POST['donor_distr_id'], true);
                }

                if (isCurrentUserCan('approve_donor')) {
                    $donor->set('donor_status', 'published');
                } else {
                    $donor->set('donor_status', 'pending');
                }

                $donor->set('donor_rtime', gmdate('Y-m-d H:i:s'));

                $em = EntityManager::getInstance();
                $em->persist($donor);
                $em->flush();

                $submitted = isVaildID($donor->get('donor_id'));

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
        if (isCurrentUserCan('add_donor')) {

            if (! empty($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'submit_donor':
                        $this->action_submit();
                        break;
                }
            }

            $view = new View('new-donor');

        } else {

            $view = new View('error-401');

        }

        $view();
    }
}
