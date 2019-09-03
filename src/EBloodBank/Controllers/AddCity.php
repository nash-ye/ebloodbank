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
use EBloodBank\Views\View;
use Psr\Container\ContainerInterface;

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
    public function __construct(ContainerInterface $container)
    {
        $this->city = new City();
        parent::__construct($container);
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if ($this->hasAuthenticatedUser() && $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'add')) {
            $this->doActions();
            $this->addNotices();
            $city = $this->getQueriedCity();
            $view = View::forge('add-city', [
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

            $city = $this->getQueriedCity();
            $cityRepository = $this->getEntityManager()->getRepository('Entities:City');

            // Set the city name.
            $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

            $duplicateCity = $cityRepository->findOneBy(['name' => $city->get('name')]);

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

    /**
     * @return \EBloodBank\Models\City
     * @since 1.0
     */
    protected function getQueriedCity()
    {
        return $this->city;
    }
}
