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
    protected $districts;

    /**
     * @return void
     * @since 1.1
     */
    public function __construct()
    {
        $this->districts = [];
        if (filter_has_var(INPUT_POST, 'districts')) {
            $districtsIDs = filter_input(INPUT_POST, 'districts', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($districtsIDs) && is_array($districtsIDs)) {
                $districtRepository = main()->getEntityManager()->getRepository('Entities:District');
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
        $currentUser = EBB\getCurrentUser();
        if ($currentUser && $currentUser->canDeleteDistricts()) {
            $this->doActions();
            $view = View::forge('delete-districts', [
                'districts' => $this->getQueriedDistricts(),
            ]);
        } else {
            $view = View::forge('error-403');
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
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canDeleteDistricts()) {
            return;
        }

        $session = main()->getSession();
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $districts = $this->getQueriedDistricts();

        if (empty($districts)) {
            return;
        }

        $deletedDistrictsCount = 0;
        $em = main()->getEntityManager();
        $donorRepository = $em->getRepository('Entities:Donor');

        foreach($districts as $district) {
            $donorsCount = $donorRepository->countBy(array('district' => $districts));

            if ($donorsCount > 0) {
                Notices::addNotice('linked_donors_exists', sprintf(__('At first, delete any linked donors with district "%s".'), $district->get('id')));
                return;
            }

            $em->remove($district);
            $deletedDistrictsCount++;
        }

        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDistrictsURL(),
                array('flag-deleted' => $deletedDistrictsCount)
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
