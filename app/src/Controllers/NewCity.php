<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\Controller;
use eBloodBank\Kernal\View;
use eBloodBank\Models\City;

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
                $city = new City();

                if (isset($_POST['city_name'])) {
                    $city->set('city_name', $_POST['city_name'], true);
                }

                $em = EntityManager::getInstance();
                $em->persist($city);
                $em->flush();

                $submitted = isVaildID($city->get('city_id'));

                redirect(getPageURL('new-city', array( 'flag-submitted' => $submitted )));

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
