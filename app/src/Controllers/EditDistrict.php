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
use EBloodBank\RouterManager;
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
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'submit_district':
                $this->doSubmitAction();
                break;
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getTargetID()
    {
        $targetID = 0;
        $route = RouterManager::getMatchedRoute();

        if ($route && isset($route->params['id'])) {
            if (isVaildID($route->params['id'])) {
                $targetID = (int) $route->params['id'];
            }
        }

        return $targetID;
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (isCurrentUserCan('edit_district')) {

            try {

                $districtID = $this->getTargetID();
                $district = EntityManager::getDistrictReference($districtID);

                // Set the district name.
                $district->set('name', filter_input(INPUT_POST, 'distr_name'), true);

                // Set the district city ID.
                $district->set('city', filter_input(INPUT_POST, 'distr_city_id'), true);

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditDistrictURL($districtID),
                        array('flag-submitted' => true)
                    )
                );

            } catch (Exceptions\InvaildArgument $ex) {
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
        $districtID = $this->getTargetID();

        if (! $districtID) {
            redirect(getHomeURL());
        }

        if (isCurrentUserCan('edit_district')) {
            $this->doActions();
            $districtRepository = EntityManager::getDistrictRepository();
            $district = $districtRepository->find($districtID);
            if (! empty($district)) {
                $view = View::instance('edit-district', array( 'district' => $district ));
            } else {
                $view = View::instance('error-404');
            }
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
