<?php
/**
 * Edit City Controller
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
class EditCity extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('edit_city')) {
            $city = $this->getQueriedCity();
            if (! empty($city)) {
                $this->doActions();
                $this->addNotices();
                $view = View::forge('edit-city', array(
                    'city' => $city,
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
            case 'submit_city':
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
            Notices::addNotice('edited', __('City edited.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (EBB\isCurrentUserCan('edit_city')) {

            try {

                $city = $this->getQueriedCity();
                $cityID = $this->getQueriedCityID();

                $em = main()->getEntityManager();
                $cityRepository = $em->getRepository('Entities:City');

                // Set the city name.
                $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

                $duplicateCity = $cityRepository->findOneBy(array( 'name' => $city->get('name') ));

                if (! empty($duplicateCity) && $duplicateCity->get('id') != $cityID) {
                    throw new InvalidArgument(__('Please enter a unique name.'), 'city_name');
                }

                $em->flush($city);

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getEditCityURL($cityID),
                        array('flag-edited' => true)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return \EBloodBank\Models\City
     * @since 1.0
     */
    protected function getQueriedCity()
    {
        $route = main()->getRouter()->getMatchedRoute();

        if (empty($route)) {
            return;
        }

        if (! isset($route->params['id']) || ! EBB\isValidID($route->params['id'])) {
            return;
        }

        $cityRepository = main()->getEntityManager()->getRepository('Entities:City');
        $city = $cityRepository->find((int) $route->params['id']);

        return $city;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedCityID()
    {
        return ($city = $this->getQueriedCity()) ? (int) $city->get('id') : 0;
    }
}
