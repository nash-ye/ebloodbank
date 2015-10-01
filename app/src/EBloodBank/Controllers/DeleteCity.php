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
        if (EBB\isCurrentUserCan('delete_city')) {
            $this->doActions();
            $view = View::forge('delete-city', [
                'city' => $this->getQueriedCity(),
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
        if (EBB\isCurrentUserCan('delete_city')) {
            $session = main()->getSession();
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $em = main()->getEntityManager();
            $city = $this->getQueriedCity();

            $districtRepository = $em->getRepository('Entities:District');
            $districtsCount = $districtRepository->countBy(array('city' => $city));

            if ($districtsCount > 0) {
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
