<?php
/**
 * Edit Cities Controller
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
class EditCities extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_GET, 'action')) {
            case 'delete_city':
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
            Notices::addNotice('deleted', sprintf(_n('%s city permanently deleted.', '%s cities permanently deleted.', $deleted), $deleted), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        if (isCurrentUserCan('delete_city')) {

            $cityID = (int) $_GET['id'];
            $city = EntityManager::getCityReference($cityID);

            $em = EntityManager::getInstance();
            $em->remove($city);
            $em->flush();

            redirect(
                addQueryArgs(
                    getEditCitiesURL(),
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
        if (isCurrentUserCan('edit_cities')) {
            $this->doActions();
            $this->addNotices();
            $view = View::instance('edit-cities');
            $view->set('page', filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
