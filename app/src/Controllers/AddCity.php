<?php
/**
 * Add City Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Models\City;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class AddCity extends Controller
{
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
    protected function doSubmitAction()
    {
        if (isCurrentUserCan('add_city')) {

            try {

                $city = new City();

                // Set the city name.
                $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

                $em = EntityManager::getInstance();
                $em->persist($city);
                $em->flush();

                $submitted = isVaildID($city->get('id'));

                redirect(
                    addQueryArgs(
                        getAddCityURL(),
                        array('flag-submitted' => $submitted)
                    )
                );

            } catch (Exceptions\InvaildArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('add_city')) {
            $this->doActions();
            $view = View::instance('add-city');
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
