<?php
/**
 * Delete city page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container, $id)
    {
        parent::__construct($container);
        if (EBB\isValidID($id)) {
            $cityRepository = $container->get('entity_manager')->getRepository('Entities:City');
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

        if (! $currentUser || ! $currentUser->canDeleteCities()) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedCityExists()) {
            View::display('error-404');
            return;
        }

        $city = $this->getQueriedCity();

        if (! $currentUser->canDeleteCity($city)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        View::display('delete-city', [
            'city' => $city,
        ]);
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
        $session = $this->getContainer()->get('session');
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

        $em = $this->getContainer()->get('entity_manager');
        $districtRepository = $em->getRepository('Entities:District');
        $districtsCount = $districtRepository->countBy(['city' => $city]);

        if ($districtsCount > 0) {
            Notices::addNotice('linked_districts_exists', __('At first, delete any linked districts with this city.'));
            return;
        }

        $em->remove($city);
        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditCitiesURL(),
                ['flag-deleted' => 1]
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
     * @return bool
     * @since 1.2
     */
    protected function isQueriedCityExists()
    {
        $city = $this->getQueriedCity();
        return ($city && $city->isExists());
    }
}
