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
use Psr\Container\ContainerInterface;

/**
 * Delete city page controller class
 *
 * @since 1.0
 */
class DeleteCity extends Controller
{
    /**
     * @var   int
     * @since 1.6
     */
    protected $cityId = 0;

    /**
     * @var \EBloodBank\Models\City|null
     * @since 1.0
     */
    protected $city;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $cityId)
    {
        parent::__construct($container);
        if (EBB\isValidID($cityId)) {
            $this->cityId = $cityId;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'delete')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->cityId) {
            $this->city = $this->getCityRepository()->find($this->cityId);
        }

        if (! $this->city) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $city = $this->city;

        if (! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $city)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-city',
            [
                'city' => $city,
            ]
        );
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
        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $city = $this->city;

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $city)) {
            return;
        }

        $districtsCount = $this->getDistrictRepository()->countBy(['city' => $city]);

        if ($districtsCount > 0) {
            Notices::addNotice('linked_districts_exists', __('At first, delete any linked districts with this city.'));
            return;
        }

        $this->getEntityManager()->remove($city);
        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditCitiesURL(),
                ['flag-deleted' => 1]
            )
        );
    }
}
