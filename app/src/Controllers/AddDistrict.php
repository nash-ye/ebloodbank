<?php
/**
 * Add District Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Models\District;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class AddDistrict extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'submit_district':
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
        if (isCurrentUserCan('add_district')) {

            try {

                $district = new District();

                // Set the district name.
                $district->set('name', filter_input(INPUT_POST, 'distr_name'), true);

                // Set the district city ID.
                $district->set('city', filter_input(INPUT_POST, 'distr_city_id'), true);

                $em = EntityManager::getInstance();
                $em->persist($district);
                $em->flush();

                $submitted = isVaildID($district->get('id'));

                redirect(
                    addQueryArgs(
                        getAddDistrictURL(),
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
        if (isCurrentUserCan('add_district')) {
            $this->doActions();
            $view = View::instance('add-district');
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
