<?php
/**
 * Manage Donors Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Views\View;

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

            $donorID = (int) $_GET['id'];
            $donor = EntityManager::getDonorReference($donorID);

            $em = EntityManager::getInstance();
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

            $donorID = (int) $_GET['id'];
            $donor = EntityManager::getDonorReference($donorID);

            if (! empty($donor) && $donor->isPending()) {

                $donor->set('status', 'published');

                $em = EntityManager::getInstance();
                $em->flush();

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

        if (isCurrentUserCan('manage_donors')) {
            if (isset($_GET['flag-deleted']) && $_GET['flag-deleted']) {
                Notices::addNotice('donor_deleted', __('The donor permanently deleted.'), 'success');
            }
            $view = new View('manage-donors');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
