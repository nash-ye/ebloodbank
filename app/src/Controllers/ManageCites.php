<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ManageCites extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_GET['action']) && 'delete_city' === $_GET['action']) {
            if (isCurrentUserCan('delete_city')) {

                $city_id = (int) $_GET['id'];

                if (! isVaildID($city_id)) {
                    die('Invaild city ID');
                }

                $em = EntityManager::getInstance();
                $em->remove($em->getCityReference($city_id));
                $em->flush();

                redirect(getPageURL('manage-cites', array( 'flag-deleted' => true )));

            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        $view = new View('manage-cites');
        $view();
    }
}
