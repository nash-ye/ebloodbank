<?php
/**
 * New City Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Models\City;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class NewCity extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('add_city')) {

            try {

                $city = new City();

                if (isset($_POST['city_name'])) {
                    $city->set('name', $_POST['city_name'], true);
                }

                $em = EntityManager::getInstance();
                $em->persist($city);
                $em->flush();

                $submitted = isVaildID($city->get('id'));

                redirect(
                    getPageURL('new-city', array(
                        'flag-submitted' => $submitted
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

        if (isCurrentUserCan('add_city')) {
            $view = new View('new-city');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
