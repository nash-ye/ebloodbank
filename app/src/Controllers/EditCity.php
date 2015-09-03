<?php
/**
 * Edit City Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

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
        if (isCurrentUserCan('edit_city')) {
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
        if (filter_has_var(INPUT_GET, 'flag-updated')) {
            Notices::addNotice('submitted', __('City updated.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (isCurrentUserCan('edit_city')) {

            try {

                $city = $this->getQueriedCity();
                $cityID = $this->getQueriedCityID();

                // Set the city name.
                $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

                $em = main()->getEntityManager();
                $em->flush($city);

                redirect(
                    addQueryArgs(
                        getEditCityURL($cityID),
                        array('flag-updated' => true)
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

        if (! isset($route->params['id']) || ! isValidID($route->params['id'])) {
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
