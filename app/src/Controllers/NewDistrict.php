<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Exceptions;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Models\District;

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
                    $distr->set('distr_name', $_POST['distr_name'], true);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr->set('distr_city_id', $_POST['distr_city_id'], true);
                }

                $em = EntityManager::getInstance();
                $em->persist($distr);
                $em->flush();

                $submitted = isVaildID($distr->get('distr_id'));

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
