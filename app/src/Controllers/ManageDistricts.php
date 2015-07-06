<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ManageDistricts extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_GET['action']) && 'delete_distr' === $_GET['action']) {
            if (isCurrentUserCan('delete_distr')) {
                $distr_id = (int) $_GET['id'];
                $deleted = Models\Districts::delete($distr_id);

                redirect(getSiteURL(array(
                    'page' => 'manage-distrs',
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
        $view = new View('manage-districts');
        $view();
    }
}
