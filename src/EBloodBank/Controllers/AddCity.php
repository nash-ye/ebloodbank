<?php
/**
 * Add city page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Models\City;

/**
 * Add city page controller class
 *
 * @since 1.0
 */
class AddCity extends Controller
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
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'add')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->city = new City();

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView(
            'add-city',
            [
                'city' => $this->city,
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
        if (filter_has_var(INPUT_GET, 'flag-added')) {
            Notices::addNotice('added', __('City added.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'add')) {
                return;
            }

            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $city = $this->city;

            // Set the city name.
            $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

            $duplicateCity = $this->getCityRepository()->findOneBy(['name' => $city->get('name')]);

            if (! empty($duplicateCity)) {
                throw new InvalidArgumentException(__('Please enter a unique city name.'));
            }

            // Set the creation date.
            $city->set('created_at', new DateTime('now', new DateTimeZone('UTC')));

            // Set the originator user.
            $city->set('created_by', $this->getAuthenticatedUser());

            $this->getEntityManager()->persist($city);
            $this->getEntityManager()->flush();

            $added = $city->isExists();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getAddCityURL(),
                    ['flag-added' => $added]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_city_argument', $ex->getMessage());
        }
    }
}
