<?php
/**
 * Edit District Controller
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

                $districtID = (int) $_GET['id'];

                if (! isVaildID($districtID)) {
                    die(__('Invalid district ID'));
                }

                $district = EntityManager::getDistrictReference($districtID);

                if (isset($_POST['distr_name'])) {
                    $district->set('name', $_POST['distr_name'], true);
                }

                if (isset($_POST['distr_city_id'])) {
                    $district->set('city', $_POST['distr_city_id'], true);
                }

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    getPageURL('edit-district', array(
                        'id' => $districtID,
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
            $districtID = (int) $_GET['id'];
            $districtRepository = EntityManager::getDistrictRepository();
            $district = $districtRepository->find($districtID);
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
