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
class EditDistrict extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_submit()
    {
        if (isCurrentUserCan('edit_district')) {

            try {

                $distrID = (int) $_GET['id'];
                $distr = EntityManager::getDistrictReference($distrID);

                if (isset($_POST['distr_name'])) {
                    $distr->set('distr_name', $_POST['distr_name'], true);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr->set('distr_city_id', $_POST['distr_city_id'], true);
                }

                EntityManager::getInstance()->flush();

                redirect(
                    getPageURL('edit-district', array(
                        'id' => $distrID,
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
                case 'submit_district':
                    $this->action_submit();
                    break;
            }
        }

        if (isCurrentUserCan('edit_district')) {
            $district = EntityManager::getDistrictRepository()->find((int) $_GET['id']);
            if (! empty($district)) {
                $view = new View('edit-district', array( 'district' => $district ));
            } else {
                $view = new View('error-404');
            }
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
