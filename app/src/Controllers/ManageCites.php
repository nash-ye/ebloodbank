<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
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
                $deleted = Models\Cites::delete($city_id);

                redirect(getSiteURL(array(
                    'page' => 'manage-cites',
                    'flag-deleted' => $deleted,
                )));
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
