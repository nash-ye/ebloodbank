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
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container, $id)
    {
        parent::__construct($container);
        if (EBB\isValidID($id)) {
            $cityRepository = $this->getEntityManager()->getRepository('Entities:City');
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

        if (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'City', 'edit')) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedCityExists()) {
            View::display('error-404');
            return;
        }

        $city = $this->getQueriedCity();

        if (! $this->getAcl()->canEditEntity($currentUser, $city)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        View::display('edit-city', [
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
            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $currentUser = EBB\getCurrentUser();
            $city = $this->getQueriedCity();

            if (! $currentUser || ! $this->getAcl()->canEditEntity($currentUser, $city)) {
                return;
            }

            $cityRepository = $this->getEntityManager()->getRepository('Entities:City');

            // Set the city name.
            $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

            $duplicateCity = $cityRepository->findOneBy(['name' => $city->get('name')]);

            if (! empty($duplicateCity) && $duplicateCity->get('id') != $city->get('id')) {
                throw new InvalidArgumentException(__('Please enter a unique city name.'));
            }

            $this->getEntityManager()->flush($city);

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditCityURL($city->get('id')),
                    ['flag-edited' => true]
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
     * @return bool
     * @since 1.2
     */
    protected function isQueriedCityExists()
    {
        $city = $this->getQueriedCity();
        return ($city && $city->isExists());
    }
}
