<?php
/**
 * Delete city page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * Delete city page controller class
 *
 * @since 1.0
 */
class DeleteCity extends Controller
{
    /**
     * @var \EBloodBank\Models\City
     * @since 1.0
     */
    protected $city;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($id)
    {
        if (EBB\isValidID($id)) {
            $cityRepository = main()->getEntityManager()->getRepository('Entities:City');
            $this->city = $cityRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        $city = $this->getQueriedCity();
        if ($currentUser && $currentUser->canDeleteCity($city)) {
            $this->doActions();
            $view = View::forge('delete-city', [
                'city' => $city,
            ]);
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
            case 'delete_city':
                $this->doDeleteAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        $session = main()->getSession();
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $city = $this->getQueriedCity();
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canDeleteCity($city)) {
            return;
        }

        $em = main()->getEntityManager();
        $districtRepository = $em->getRepository('Entities:District');
        $districtsCount = $districtRepository->countBy(array('city' => $city));

        if ($districtsCount > 0) {
            Notices::addNotice('linked_districts_exists', __('At first, delete any linked districts with this city.'));
            return;
        }

        $em->remove($city);
        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditCitiesURL(),
                array('flag-deleted' => 1)
            )
        );
    }

    /**
     * @return \EBloodBank\Models\City
     * @since 1.0
     */
    protected function getQueriedCity()
    {
        return $this->city;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedCityID()
    {
        $city = $this->getQueriedCity();
        return ($city) ? (int) $city->get('id') : 0;
    }
}
