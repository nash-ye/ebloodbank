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
use Psr\Container\ContainerInterface;

/**
 * Delete city page controller class
 *
 * @since 1.0
 */
class DeleteDistrict extends Controller
{
    /**
     * @var   int
     * @since 1.6
     */
    protected $districtId = 0;

    /**
     * @var \EBloodBank\Models\District|null
     * @since 1.0
     */
    protected $district;

    /**
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $districtId)
    {
        parent::__construct($container);
        if (EBB\isValidID($districtId)) {
            $this->districtId = (int) $districtId;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'delete')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->districtId) {
            $this->district = $this->getDistrictRepository()->find($this->districtId);
        }

        if (! $this->district) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $district = $this->district;

        if (! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $district)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-district',
            [
                'district' => $district,
            ]
        );
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
        $actionToken = filter_input(INPUT_POST, 'token');
        $sessionToken = $this->getSession()->getCsrfToken();

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $district = $this->district;

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $district)) {
            return;
        }

        $donorsCount = $this->getDonorRepository()->countBy(['district' => $district]);

        if ($donorsCount > 0) {
            Notices::addNotice('linked_donors_exists', __('At first, delete any linked donors with this district.'));
            return;
        }

        $this->getEntityManager()->remove($district);
        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDistrictsURL(),
                ['flag-deleted' => 1]
            )
        );
    }
}
