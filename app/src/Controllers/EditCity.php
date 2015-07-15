<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class EditCity extends Controller
{
    /**
     * @var int
     * @since 1.0
     */
    protected $id = 0;

    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        $this->id = (int) $_GET['id'];

        if (! isVaildID($this->id)) {
            die('Invaild city ID');
        }

        if (isset($_POST['action']) && 'submit_city' === $_POST['action']) {

            if (isCurrentUserCan('edit_city')) {

                $em = EntityManager::getInstance();
                $city = $em->getCityReference($this->id);

                if (isset($_POST['city_name'])) {
                    $city->set('city_name', $_POST['city_name'], true);
                }

                $em->flush();
                $submitted = isVaildID($city->get('city_id'));

                redirect(getPageURL('edit-city', array( 'id' => $this->id, 'flag-submitted' => $submitted )));

            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('edit_city')) {
            $view = new View('edit-city');
            $view(array( 'id' => $this->id ));
        } else {
            $view = new View('error-401');
            $view();
        }
    }
}
