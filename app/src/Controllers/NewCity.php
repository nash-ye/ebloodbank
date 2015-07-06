<?php
namespace eBloodBank\Controllers;

use eBloodBank\Kernal\Controller;
use eBloodBank\Kernal\View;
use eBloodBank\Models\Cites;

/**
 * @since 1.0
 */
class NewCity extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_POST['action']) && 'submit_city' === $_POST['action']) {
            if (isCurrentUserCan('add_city')) {
                $city_data = array();

                if (isset($_POST['city_name'])) {
                    $city_data['city_name'] = filter_var($_POST['city_name'], FILTER_SANITIZE_STRING);
                }

                $city_id = Cites::insert($city_data);
                $submitted = isVaildID($city_id);

                redirect(getSiteURL(array(
                    'page' => 'new-city',
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
        if (isCurrentUserCan('add_city')) {
            $view = new View('new-city');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
