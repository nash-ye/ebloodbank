<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ManageDonors extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_GET['action'])) {
            $donor_id = (int) $_GET['id'];

            if (empty($donor_id)) {
                die(-1);
            }

            $em = EntityManager::getInstance();

            if ('delete_donor' === $_GET['action']) {
                if (isCurrentUserCan('delete_donor')) {

                    $em->remove($em->getDonorReference($donor_id));
                    $em->flush();

                    redirect(getPageURL('manage-donors', array( 'flag-deleted' => true )));

                }
            } elseif ('approve_donor' === $_GET['action']) {
                if (isCurrentUserCan('approve_donor')) {

                    $donorRepository = EntityManager::getDonorRepository();
                    $donor = $donorRepository->find($donor_id);

                    if (! empty($donor) && $donor->isPending()) {

                        $donor->set('donor_status', 'approved');

                        $em->merge($donor);
                        $em->flush();

                        redirect(getPageURL('manage-donors', array( 'flag-deleted' => true )));

                    }
                }
            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        $fetchingArgs = array();
        $view = new View('manage-donors');

        if (isCurrentUserCan('approve_donor')) {
            $fetchingArgs['status']  = 'all';
        } else {
            $fetchingArgs['status']  = 'approved';
        }

        if (! empty($_POST['name'])) {
            $fetchingArgs['name'] = strip_tags($_POST['name']);
        }

        if (! empty($_POST['distr_id'])) {
            $fetchingArgs['distr_id'] = (int) $_POST['distr_id'];
        }

        if (! empty($_POST['city_id'])) {
            $fetchingArgs['city_id']  = (int) $_POST['city_id'];
        }

        if (! empty($_POST['blood_group'])) {
            $fetchingArgs['blood_group'] = strip_tags($_POST['blood_group']);
        }

        $view(array( 'fetchingArgs' => $fetchingArgs ));
    }
}
