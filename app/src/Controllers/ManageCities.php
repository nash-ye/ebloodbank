<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

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

            $em = EntityManager::getInstance();

            $city_id = (int) $_GET['id'];
            $city = EntityManager::getCityReference($city_id);

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

        if (isset($_GET['flag-deleted'])) {
            Notices::addNotice('deleted-city', __('The city permanently deleted.'), 'success');
        }

        $view = new View('manage-cities');
        $view();
    }
}
