<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

/**
 * @since 1.0
 */
class ManageDonors extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_delete()
    {
        if (isCurrentUserCan('delete_donor')) {

            $em = EntityManager::getInstance();

            $donor_id = (int) $_GET['id'];
            $donor = EntityManager::getDonorReference($donor_id);

            $em->remove($donor);
            $em->flush();

            redirect(
                getPageURL('manage-donors', array(
                    'flag-deleted' => true
                ))
            );

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function action_approve()
    {
        if (isCurrentUserCan('approve_donor')) {

            $donor_id = (int) $_GET['id'];
            $donor = EntityManager::getDonorReference($donor_id);

            if (! empty($donor) && $donor->isPending()) {

                $donor->set('donor_status', 'published');

                EntityManager::getInstance()->flush();

                redirect(
                    getPageURL('manage-donors', array(
                        'flag-approved' => true
                    ))
                );

            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! empty($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete_donor':
                    $this->action_delete();
                    break;
                case 'approve_donor':
                    $this->action_approve();
                    break;
            }
        }

        if (isset($_GET['flag-deleted'])) {
            Notices::addNotice('deleted-donor', __('The donor permanently deleted.'), 'success');
        }

        $fetchingArgs = array();

        if (isCurrentUserCan('approve_donor')) {
            $fetchingArgs['status']  = 'all';
        } else {
            $fetchingArgs['status']  = 'published';
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

        $view = new View('manage-donors', array( 'fetchingArgs' => $fetchingArgs ));
        $view();
    }
}
