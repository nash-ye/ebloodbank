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
use EBloodBank\RouterManager;
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
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'submit_city':
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
        if (isCurrentUserCan('edit_city')) {

            try {

                $cityID = $this->getTargetID();
                $city = EntityManager::getCityReference($cityID);

                // Set the city name.
                $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditCityURL($cityID),
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
        $cityID = $this->getTargetID();

        if (! $cityID) {
            redirect(getHomeURL());
        }

        if (isCurrentUserCan('edit_city')) {
            $this->doActions();
            $cityRepository = EntityManager::getCityRepository();
            $city = $cityRepository->find($cityID);
            if (! empty($city)) {
                $view = View::instance('edit-city', array( 'city' => $city ));
            } else {
                $view = View::instance('error-404');
            }
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
