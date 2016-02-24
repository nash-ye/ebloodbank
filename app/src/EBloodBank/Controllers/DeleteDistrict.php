<?php
/**
 * Delete district page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container, $id)
    {
        parent::__construct($container);
        if (EBB\isValidID($id)) {
            $districtRepository = $container->get('entity_manager')->getRepository('Entities:District');
            $this->district = $districtRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canDeleteDistricts()) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedDistrictExists()) {
            View::display('error-404');
            return;
        }

        $district = $this->getQueriedDistrict();

        if (! $currentUser->canDeleteDistrict($district)) {
            View::display('error-403');
            return;
        }

        $this->doActions();
        View::display('delete-district', [
            'district' => $district,
        ]);
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
        $session = $this->getContainer()->get('session');
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $currentUser = EBB\getCurrentUser();
        $district = $this->getQueriedDistrict();

        if (! $currentUser || ! $currentUser->canDeleteDistrict($district)) {
            return;
        }

        $em = $this->getContainer()->get('entity_manager');
        $donorRepository = $em->getRepository('Entities:Donor');
        $donorsCount = $donorRepository->countBy(['district' => $district]);

        if ($donorsCount > 0) {
            Notices::addNotice('linked_donors_exists', __('At first, delete any linked donors with this district.'));
            return;
        }

        $em->remove($district);
        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDistrictsURL(),
                ['flag-deleted' => 1]
            )
        );
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
     * @return bool
     * @since 1.2
     */
    protected function isQueriedDistrictExists()
    {
        $district = $this->getQueriedDistrict();
        return ($district && $district->isExists());
    }
}
