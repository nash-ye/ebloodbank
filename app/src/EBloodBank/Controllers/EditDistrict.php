<?php
/**
 * Edit district page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * Edit district page controller class
 *
 * @since 1.0
 */
class EditDistrict extends Controller
{
    /**
     * @var \EBloodBank\Models\District
     * @since 1.0
     */
    protected $district;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($id)
    {
        if (EBB\isValidID($id)) {
            $districtRepository = main()->getEntityManager()->getRepository('Entities:District');
            $this->district = $districtRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (EBB\isCurrentUserCan('edit_district')) {
            $district = $this->getQueriedDistrict();
            if (! empty($district)) {
                $this->doActions();
                $this->addNotices();
                $view = View::forge('edit-district', [
                    'district' => $district,
                ]);
            } else {
                $view = View::forge('error-404');
            }
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
        if (filter_has_var(INPUT_GET, 'flag-edited')) {
            Notices::addNotice('edited', __('District edited.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (EBB\isCurrentUserCan('edit_district')) {
            try {
                $session = main()->getSession();
                $sessionToken = $session->getCsrfToken();
                $actionToken = filter_input(INPUT_POST, 'token');

                $district = $this->getQueriedDistrict();
                $districtID = $this->getQueriedDistrictID();

                if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                    return;
                }

                // Set the district name.
                $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

                // Set the district city ID.
                $district->set('city', filter_input(INPUT_POST, 'district_city_id'), true);

                $em = main()->getEntityManager();
                $em->flush($district);

                EBB\redirect(
                    EBB\addQueryArgs(
                        EBB\getEditDistrictURL($districtID),
                        array('flag-edited' => true)
                    )
                );
            } catch (InvalidArgumentException $ex) {
                Notices::addNotice('invalid_district_argument', $ex->getMessage());
            }
        }
    }

    /**
     * @return \EBloodBank\Models\District
     * @since 1.0
     */
    protected function getQueriedDistrict()
    {
        return $this->district;
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getQueriedDistrictID()
    {
        $district = $this->getQueriedDistrict();
        return ($district) ? (int) $district->get('id') : 0;
    }
}
