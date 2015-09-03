<?php
/**
 * Add District Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Models\District;
use EBloodBank\Views\View;
use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 */
class AddDistrict extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('add_district')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('add-district');
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
            case 'submit_district':
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
            Notices::addNotice('added', __('District added.'), 'success');
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
                $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

                // Set the district city ID.
                $district->set('city', filter_input(INPUT_POST, 'district_city_id'), true);

                $district->set('created_at', gmdate('Y-m-d H:i:s'));
                $district->set('created_by', getCurrentUserID());

                $em = main()->getEntityManager();
                $em->persist($district);
                $em->flush();

                $added = isValidID($district->get('id'));

                redirect(
                    addQueryArgs(
                        getAddDistrictURL(),
                        array('flag-added' => $added)
                    )
                );

            } catch (InvalidArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }
}
