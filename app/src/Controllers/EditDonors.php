<?php
/**
 * Edit Donors Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class EditDonors extends ViewDonors
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('edit_donors')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('edit-donors', array(
                'donors' => $this->getQueriedDonors(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
            ));
        } else {
            $view = View::forge('error-403');
        }
        $view();
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_GET, 'action')) {
            case 'delete':
                $this->doDeleteAction();
                break;
            case 'approve':
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
        if (filter_has_var(INPUT_GET, 'flag-approved')) {
            $approved = (int) filter_input(INPUT_GET, 'flag-approved', FILTER_SANITIZE_NUMBER_INT);
            Notices::addNotice('approved', sprintf(_n('%s donor approved.', '%s donors approved.', $approved), $approved), 'success');
        }
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            $deleted = (int) filter_input(INPUT_GET, 'flag-deleted', FILTER_SANITIZE_NUMBER_INT);
            Notices::addNotice('deleted', sprintf(_n('%s donor permanently deleted.', '%s donors permanently deleted.', $deleted), $deleted), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        if (isCurrentUserCan('delete_donor')) {

            $donorID = filter_input(INPUT_GET, 'id');

            if (! isValidID($donorID)) {
                return;
            }

            $em = main()->getEntityManager();
            $donor = $em->getReference('Entities:Donor', $donorID);
            $em->remove($donor);
            $em->flush();

            redirect(
                addQueryArgs(
                    getEditDonorsURL(),
                    array('flag-deleted' => 1)
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

            $donorID = filter_input(INPUT_GET, 'id');

            if (! isValidID($donorID)) {
                return;
            }

            $em = main()->getEntityManager();

            $donor = $em->getReference('Entities:Donor', $donorID);

            if (! $donor->isPending()) {
                return;
            }

            $donor->set('status', 'approved');

            $em->flush();

            redirect(
                addQueryArgs(
                    getEditDonorsURL(),
                    array('flag-approved' => 1)
                )
            );

        }
    }
}
