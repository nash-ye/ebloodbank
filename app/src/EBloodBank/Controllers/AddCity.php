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

/**
 * Add city page controller class
 *
 * @since 1.0
 */
class AddCity extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('add_city')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('add-city');
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
        if (EBB\isCurrentUserCan('add_city')) {
            try {
                $city = new City();

                $session = main()->getSession();
                $sessionToken = $session->getCsrfToken();
                $actionToken = filter_input(INPUT_POST, 'token');

                $em = main()->getEntityManager();
                $cityRepository = $em->getRepository('Entities:City');

                if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                    return;
                }

                // Set the city name.
                $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

                $duplicateCity = $cityRepository->findOneBy(array('name' => $city->get('name')));

                if (! empty($duplicateCity)) {
                    throw new InvalidArgumentException(__('Please enter a unique city name.'));
                }

                // Set the creation date.
                $city->set('created_at', new DateTime('now', new DateTimeZone('UTC')), true);

                // Set the creator ID.
                $city->set('created_by', EBB\getCurrentUserID(), true);

                $em->persist($city);
                $em->flush();

                $added = $city->isExists();

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getAddCityURL(),
                        array('flag-added' => $added)
                    )
                );
            } catch (InvalidArgumentException $ex) {
                Notices::addNotice('invalid_city_argument', $ex->getMessage());
            }
        }
    }
}
