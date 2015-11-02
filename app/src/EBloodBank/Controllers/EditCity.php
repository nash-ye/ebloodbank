<?php
/**
 * Edit city page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * Edit city page controller class
 *
 * @since 1.0
 */
class EditCity extends Controller
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
        if ($currentUser && $currentUser->canEditCity($city)) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('edit-city', [
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
        try {
            $session = main()->getSession();
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $currentUser = EBB\getCurrentUser();
            $city = $this->getQueriedCity();
            $cityID = $this->getQueriedCityID();

            if (! $currentUser || ! $currentUser->canEditCity($city)) {
                return;
            }

            $em = main()->getEntityManager();
            $cityRepository = $em->getRepository('Entities:City');

            // Set the city name.
            $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

            $duplicateCity = $cityRepository->findOneBy(['name' => $city->get('name')]);

            if (! empty($duplicateCity) && $duplicateCity->get('id') != $cityID) {
                throw new InvalidArgumentException(__('Please enter a unique city name.'));
            }

            $em->flush($city);

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditCityURL($cityID),
                    array('flag-edited' => true)
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_city_argument', $ex->getMessage());
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
