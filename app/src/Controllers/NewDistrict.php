<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class NewDistrict extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_POST['action']) && 'submit_distr' === $_POST['action']) {
            if (isCurrentUserCan('add_distr')) {
                $distr_data = array();

                if (isset($_POST['distr_name'])) {
                    $distr_data['distr_name'] = filter_input(INPUT_POST, 'distr_name', FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr_data['distr_city_id'] = (int) $_POST['distr_city_id'];
                }

                $distr_id = Models\Districts::insert($distr_data);
                $submitted = isVaildID($distr_id);

                redirect(getSiteURL(array(
                    'page' => 'new-distr',
                    'flag-submitted' => $submitted,
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
        if (isCurrentUserCan('add_distr')) {
            $view = new View('new-district');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
