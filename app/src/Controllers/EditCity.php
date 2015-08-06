<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

/**
 * @since 1.0
 */
class EditCity extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('edit_city')) {

            try {

                $cityID = (int) $_GET['id'];
                $city = EntityManager::getCityReference($cityID);

                if (isset($_POST['city_name'])) {
                    $city->set('city_name', $_POST['city_name'], true);
                }

                EntityManager::getInstance()->flush();

                redirect(
                    getPageURL('edit-city', array(
                        'id' => $cityID,
                        'flag-submitted' => true
                    ))
                );

            } catch (Exceptions\InvaildProperty $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! empty($_POST['action'])) {
            switch ($_POST['action']) {
                case 'submit_city':
                    $this->action_submit();
                    break;
            }
        }

        if (isCurrentUserCan('edit_city')) {
            $city = EntityManager::getCityRepository()->find((int) $_GET['id']);
            if (! empty($city)) {
                $view = new View('edit-city', array( 'city' => $city ));
            } else {
                $view = new View('error-404');
            }
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
