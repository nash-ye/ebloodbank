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
use Psr\Container\ContainerInterface;

/**
 * Edit city page controller class
 *
 * @since 1.0
 */
class EditCity extends Controller
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
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'edit')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if (EBB\isValidID($this->cityId)) {
            $this->city = $this->getCityRepository()->find($this->cityId);
        }

        if (! $this->city) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $city = $this->city;

        if (! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $city)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView(
            'edit-city',
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

            $city = $this->city;

            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $city)) {
                return;
            }

            // Set the city name.
            $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

            $duplicateCity = $this->getCityRepository()->findOneBy(['name' => $city->get('name')]);

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
}
