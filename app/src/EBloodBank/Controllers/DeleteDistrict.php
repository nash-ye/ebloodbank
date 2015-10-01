<?php
/**
 * Delete district page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Views\View;

/**
 * Delete city page controller class
 *
 * @since 1.0
 */
class DeleteDistrict extends Controller
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
        if (EBB\isCurrentUserCan('delete_district')) {
            $this->doActions();
            $view = View::forge('delete-district', [
                'district' => $this->getQueriedDistrict(),
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
            case 'delete_district':
                $this->doDeleteAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        if (EBB\isCurrentUserCan('delete_district')) {
            $session = main()->getSession();
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $em = main()->getEntityManager();
            $district = $this->getQueriedDistrict();

            $donorRepository = $em->getRepository('Entities:Donor');
            $donorsCount = $donorRepository->countBy(array('district' => $district));

            if ($donorsCount > 0) {
                Notices::addNotice('linked_donors_exists', __('At first, delete any linked donors with this district.'));
                return;
            }

            $em->remove($district);
            $em->flush();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditDistrictsURL(),
                    array('flag-deleted' => 1)
                )
            );
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
