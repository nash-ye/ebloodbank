<?php
/**
 * Edit Donors Controller
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
class EditDonors extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_GET, 'action')) {
            case 'delete_donor':
                $this->doDeleteAction();
                break;
            case 'approve_donor':
                $this->doApproveAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            Notices::addNotice('deleted', __('The donor permanently deleted.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        if (isCurrentUserCan('delete_donor')) {

            $donorID = (int) $_GET['id'];
            $donor = EntityManager::getDonorReference($donorID);

            $em = EntityManager::getInstance();
            $em->remove($donor);
            $em->flush();

            redirect(
                addQueryArgs(
                    getEditDonorsURL(),
                    array('flag-deleted' => true)
                )
            );

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doApproveAction()
    {
        if (isCurrentUserCan('approve_donor')) {

            $donorID = (int) $_GET['id'];
            $donor = EntityManager::getDonorReference($donorID);

            if (! empty($donor) && $donor->isPending()) {

                $donor->set('status', 'published');

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditDonorsURL(),
                        array('flag-approved' => true)
                    )
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
        if (isCurrentUserCan('edit_donors')) {
            $this->doActions();
            $this->addNotices();
            $view = View::instance('edit-donors');
            $view->set('page', filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
