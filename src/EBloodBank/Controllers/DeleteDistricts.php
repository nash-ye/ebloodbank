<?php
/**
 * Delete districts page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use Psr\Container\ContainerInterface;

/**
 * Delete districts page controller class
 *
 * @since 1.1
 */
class DeleteDistricts extends Controller
{
    /**
     * @var \EBloodBank\Models\District[]
     * @since 1.1
     */
    protected $districts = [];

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'delete')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if (filter_has_var(INPUT_POST, 'districts')) {
            $districtsIDs = filter_input(INPUT_POST, 'districts', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($districtsIDs) && is_array($districtsIDs)) {
                $this->districts = $this->getDistrictRepository()->findBy(['id' => $districtsIDs]);
            }
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-districts',
            [
                'districts' => $this->districts,
            ]
        );
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'delete_districts':
                $this->doDeleteAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doDeleteAction()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'delete')) {
            return;
        }

        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $districts = $this->districts;

        if (! $districts || ! is_array($districts)) {
            return;
        }

        $deletedDistrictsCount = 0;

        foreach ($districts as $district) {
            if ($this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $district)) {
                $donorsCount = $this->getDonorRepository()->countBy(['district' => $districts]);

                if ($donorsCount > 0) {
                    Notices::addNotice('linked_donors_exists', sprintf(__('At first, delete any linked donors with district "%s".'), $district->get('id')));
                    return;
                }

                $this->getEntityManager()->remove($district);
                $deletedDistrictsCount++;
            }
        }

        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDistrictsURL(),
                ['flag-deleted' => $deletedDistrictsCount]
            )
        );
    }
}
