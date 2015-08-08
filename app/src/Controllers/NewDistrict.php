<?php
/**
 * New District Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Models\District;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class NewDistrict extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('add_district')) {

            try {

                $distr = new District();

                if (isset($_POST['distr_name'])) {
                    $distr->set('name', $_POST['distr_name'], true);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr->set('city', $_POST['distr_city_id'], true);
                }

                $em = EntityManager::getInstance();
                $em->persist($distr);
                $em->flush();

                $submitted = isVaildID($distr->get('id'));

                redirect(
                    getPageURL('new-district', array(
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
                case 'submit_district':
                    $this->action_submit();
                    break;
            }
        }

        if (isCurrentUserCan('add_district')) {
            $view = new View('new-district');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
