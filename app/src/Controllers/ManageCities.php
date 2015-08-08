<?php
/**
 * Manage Cities Controller
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
class ManageCities extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_delete()
    {
        if (isCurrentUserCan('delete_city')) {

            $cityID = (int) $_GET['id'];
            $city = EntityManager::getCityReference($cityID);

            $em = EntityManager::getInstance();
            $em->remove($city);
            $em->flush();

            redirect(
                getPageURL('manage-cities', array(
                    'flag-deleted' => true
                ))
            );

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
                case 'delete_city':
                    $this->action_delete();
                    break;
            }
        }

        if (isCurrentUserCan('manage_cities')) {
            if (isset($_GET['flag-deleted']) && $_GET['flag-deleted']) {
                Notices::addNotice('city_deleted', __('The city permanently deleted.'), 'success');
            }
            $view = new View('manage-cities');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
