<?php
/**
 * Delete cities page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

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
    protected $cities;

    /**
     * @return void
     * @since 1.1
     */
    public function __construct(ContainerInterface $container)
    {
        $this->cities = [];
        parent::__construct($container);
        if (filter_has_var(INPUT_POST, 'cities')) {
            $citiesIDs = filter_input(INPUT_POST, 'cities', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($citiesIDs) && is_array($citiesIDs)) {
                $cityRepository = $container->get('entity_manager')->getRepository('Entities:City');
                $this->cities = $cityRepository->findBy(['id' => $citiesIDs]);
            }
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        if (! $currentUser || ! $currentUser->canDeleteCities()) {
            $view = View::forge('error-403');
        } else {
            $this->doActions();
            $view = View::forge('delete-cities', [
                'cities' => $this->getQueriedCities(),
            ]);
        }
        $view();
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
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canDeleteCities()) {
            return;
        }

        $session = $this->getContainer()->get('session');
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $cities = $this->getQueriedCities();

        if (! $cities || ! is_array($cities)) {
            return;
        }

        $deletedCitiesCount = 0;
        $em = $this->getContainer()->get('entity_manager');
        $districtRepository = $em->getRepository('Entities:District');

        foreach ($cities as $city) {
            if ($currentUser->canDeleteCity($city)) {
                $districtsCount = $districtRepository->countBy(['city' => $city]);

                if ($districtsCount > 0) {
                    Notices::addNotice('linked_districts_exists', sprintf(__('At first, delete any linked districts with city "%s".'), $city->get('name')));
                    return;
                }

                $em->remove($city);
                $deletedCitiesCount++;
            }
        }

        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditCitiesURL(),
                ['flag-deleted' => $deletedCitiesCount]
            )
        );
    }

    /**
     * @return \EBloodBank\Models\City[]
     * @since 1.1
     */
    protected function getQueriedCities()
    {
        return $this->cities;
    }
}
