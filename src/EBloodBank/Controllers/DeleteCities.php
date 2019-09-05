<?php
/**
 * Delete cities page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;

/**
 * Delete cities page controller class
 *
 * @since 1.1
 */
class DeleteCities extends Controller
{
    /**
     * @var \EBloodBank\Models\City[]
     * @since 1.1
     */
    protected $cities = [];

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'delete')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if (filter_has_var(INPUT_POST, 'cities')) {
            $citiesIDs = filter_input(INPUT_POST, 'cities', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($citiesIDs) && is_array($citiesIDs)) {
                $this->cities = $this->getCityRepository()->findBy(['id' => $citiesIDs]);
            }
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-cities',
            [
                'cities' => $this->cities,
            ]
        );
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'delete_cities':
                $this->doDeleteAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doDeleteAction()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'delete')) {
            return;
        }

        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $cities = $this->cities;

        if (! $cities || ! is_array($cities)) {
            return;
        }

        $deletedCitiesCount = 0;

        foreach ($cities as $city) {
            if ($this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $city)) {
                $districtsCount = $this->getDistrictRepository()->countBy(['city' => $city]);

                if ($districtsCount > 0) {
                    Notices::addNotice('linked_districts_exists', sprintf(__('At first, delete any linked districts with city "%s".'), $city->get('name')));
                    return;
                }

                $this->getEntityManager()->remove($city);
                $deletedCitiesCount++;
            }
        }

        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditCitiesURL(),
                ['flag-deleted' => $deletedCitiesCount]
            )
        );
    }
}
