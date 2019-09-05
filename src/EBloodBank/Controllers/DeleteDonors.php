<?php
/**
 * Delete donors page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;

/**
 * Delete donors page controller class
 *
 * @since 1.1
 */
class DeleteDonors extends Controller
{
    /**
     * @var \EBloodBank\Models\Donor[]
     * @since 1.1
     */
    protected $donors = [];

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'delete')) {
            $view = $this->viewFactory->forgeView('error-403');
        }

        if (filter_has_var(INPUT_POST, 'donors')) {
            $donorsIDs = filter_input(INPUT_POST, 'donors', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($donorsIDs) && is_array($donorsIDs)) {
                $this->donors = $this->getDonorRepository()->findBy(['id' => $donorsIDs]);
            }
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-donors',
            [
                'donors' => $this->donors,
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
            case 'delete_donors':
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
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'delete')) {
            return;
        }

        $actionToken = filter_input(INPUT_POST, 'token');
        $sessionToken = $this->getSession()->getCsrfToken();

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $donors = $this->donors;

        if (! $donors || ! is_array($donors)) {
            return;
        }

        $deletedDonorsCount = 0;

        foreach ($donors as $donor) {
            if ($this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $donor)) {
                $this->getEntityManager()->remove($donor);
                $deletedDonorsCount++;
            }
        }

        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDonorsURL(),
                ['flag-deleted' => $deletedDonorsCount]
            )
        );
    }
}
