<?php
/**
 * Edit Districts Controller
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
class EditDistricts extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_GET, 'action')) {
            case 'delete_district':
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

            $districtID = (int) $_GET['id'];
            $district = EntityManager::getDistrictReference($districtID);

            $em = EntityManager::getInstance();
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

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('edit_districts')) {
            $this->doActions();
            $this->addNotices();
            $view = View::instance('edit-districts');
            $view->set('page', filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
