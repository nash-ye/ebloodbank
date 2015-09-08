<?php
/**
 * Edit District Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 */
class EditDistrict extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('edit_district')) {
            $district = $this->getQueriedDistrict();
            if (! empty($district)) {
                $this->doActions();
                $this->addNotices();
                $view = View::forge('edit-district', array(
                    'district' => $district,
                ));
            } else {
                $view = View::forge('error-404');
            }
        } else {
            $view = View::forge('error-403');
        }
        $view();
    }

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
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-edited')) {
            Notices::addNotice('edited', __('District edited.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (EBB\isCurrentUserCan('edit_district')) {

            try {

                $district = $this->getQueriedDistrict();
                $districtID = $this->getQueriedDistrictID();

                // Set the district name.
                $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

                // Set the district city ID.
                $district->set('city', filter_input(INPUT_POST, 'district_city_id'), true);

                $em = main()->getEntityManager();
                $em->flush($district);

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getEditDistrictURL($districtID),
                        array('flag-edited' => true)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }

    }

    /**
     * @return \EBloodBank\Models\District
     * @since 1.0
     */
    protected function getQueriedDistrict()
    {
        $route = main()->getRouter()->getMatchedRoute();

        if (empty($route)) {
            return;
        }

        if (! isset($route->params['id']) || ! EBB\isValidID($route->params['id'])) {
            return;
        }

        $districtRepository = main()->getEntityManager()->getRepository('Entities:District');
        $district = $districtRepository->find((int) $route->params['id']);

        return $district;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedDistrictID()
    {
        return ($district = $this->getQueriedDistrict()) ? (int) $district->get('id') : 0;
    }
}
