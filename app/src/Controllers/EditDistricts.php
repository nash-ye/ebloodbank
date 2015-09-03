<?php
/**
 * Edit Districts Controller
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
class EditDistricts extends ViewDistricts
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('edit_districts')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('edit-districts', array(
                'districts' => $this->getQueriedDistricts(),
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
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            $deleted = (int) filter_input(INPUT_GET, 'flag-deleted', FILTER_SANITIZE_NUMBER_INT);
            Notices::addNotice('deleted', sprintf(_n('%s district permanently deleted.', '%s districts permanently deleted.', $deleted), $deleted), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function doDeleteAction()
    {
        if (isCurrentUserCan('delete_district')) {

            $districtID = filter_input(INPUT_GET, 'id');

            if (! isValidID($districtID)) {
                return;
            }

            $em = main()->getEntityManager();
            $district = $em->getReference('Entities:District', $districtID);
            $em->remove($district);
            $em->flush();

            redirect(
                addQueryArgs(
                    getEditDistrictsURL(),
                    array('flag-deleted' => 1)
                )
            );

        }
    }
}
