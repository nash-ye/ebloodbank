<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;
use eBloodBank\Models\District;

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
                $distr = new District();

                if (isset($_POST['distr_name'])) {
                    $distr->set('distr_name', $_POST['distr_name'], true);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr->set('distr_city_id', $_POST['distr_city_id'], true);
                }

                $em = EntityManager::getInstance();
                $em->persist($distr);
                $em->flush();

                $submitted = isVaildID($distr->get('distr_id'));

                redirect(getPageURL('new-distr', array( 'flag-submitted' => $submitted )));

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
