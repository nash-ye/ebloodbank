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
use EBloodBank\Views\View;
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
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        if (filter_has_var(INPUT_POST, 'districts')) {
            $districtsIDs = filter_input(INPUT_POST, 'districts', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($districtsIDs) && is_array($districtsIDs)) {
                $districtRepository = $this->getEntityManager()->getRepository('Entities:District');
                $this->districts = $districtRepository->findBy(['id' => $districtsIDs]);
            }
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'delete')) {
            $view = View::forge('error-403');
        } else {
            $this->doActions();
            $view = View::forge('delete-districts', [
                'districts' => $this->getQueriedDistricts(),
            ]);
        }
        $view();
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

        $districts = $this->getQueriedDistricts();

        if (! $districts || ! is_array($districts)) {
            return;
        }

        $deletedDistrictsCount = 0;
        $donorRepository = $this->getEntityManager()->getRepository('Entities:Donor');

        foreach ($districts as $district) {
            if ($this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $district)) {
                $donorsCount = $donorRepository->countBy(['district' => $districts]);

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

    /**
     * @return \EBloodBank\Models\District[]
     * @since 1.1
     */
    protected function getQueriedDistricts()
    {
        return $this->districts;
    }
}
