<?php
/**
 * Add district page controller class file
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
use EBloodBank\Models\District;
use EBloodBank\Views\View;

/**
 * Add district page controller class
 *
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
        if (EBB\isCurrentUserCan('add_district')) {
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
        if (EBB\isCurrentUserCan('add_district')) {
            try {
                $district = new District();

                $session = main()->getSession();
                $sessionToken = $session->getCsrfToken();
                $actionToken = filter_input(INPUT_POST, 'token');

                if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                    return;
                }

                // Set the district name.
                $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

                // Set the district city ID.
                $district->set('city', filter_input(INPUT_POST, 'district_city_id'), true);

                $district->set('created_at', new DateTime('now', new DateTimeZone('UTC')), true);
                $district->set('created_by', EBB\getCurrentUserID(), true);

                $em = main()->getEntityManager();
                $em->persist($district);
                $em->flush();

                $added = $district->isExists();

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getAddDistrictURL(),
                        array('flag-added' => $added)
                    )
                );
            } catch (InvalidArgumentException $ex) {
                Notices::addNotice('invalid_district_argument', $ex->getMessage());
            }
        }
    }
}
