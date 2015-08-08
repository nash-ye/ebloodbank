<?php
/**
 * Edit City Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Views\View;

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

                if (! isVaildID($cityID)) {
                    die(__('Invalid city ID'));
                }

                $city = EntityManager::getCityReference($cityID);

                if (isset($_POST['city_name'])) {
                    $city->set('name', $_POST['city_name'], true);
                }

                $em = EntityManager::getInstance();
                $em->flush();

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
            $cityID = (int) $_GET['id'];
            $city = EntityManager::getCityRepository()->find($cityID);
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
