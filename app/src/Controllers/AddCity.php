<?php
/**
 * Add City Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Models\City;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
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
        if (isCurrentUserCan('add_city')) {
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
        if (isCurrentUserCan('add_city')) {

            try {

                $city = new City();

                // Set the city name.
                $city->set('name', filter_input(INPUT_POST, 'city_name'), true);

                // Set the creation date.
                $city->set('created_at', gmdate('Y-m-d H:i:s'));

                // Set the creator ID.
                $city->set('created_by', getCurrentUserID());

                $em = main()->getEntityManager();
                $em->persist($city);
                $em->flush();

                $added = isValidID($city->get('id'));

                redirect(
                    addQueryArgs(
                        getAddCityURL(),
                        array('flag-added' => $added)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }
}
