<?php
/**
 * Approve donors page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;

/**
 * Approve donors page controller class
 *
 * @since 1.1
 */
class ApproveDonors extends Controller
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
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if (filter_has_var(INPUT_POST, 'donors')) {
            $donorsIDs = filter_input(INPUT_POST, 'donors', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($donorsIDs) && is_array($donorsIDs)) {
                $this->donors = $this->getDonorRepository()->findBy(['id' => $donorsIDs]);
            }
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'approve-donors',
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
            case 'approve_donors':
                $this->doApproveAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doApproveAction()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
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

        $approvedDonorsCount = 0;

        foreach ($donors as $donor) {
            if (! $donor->isPending()) {
                continue;
            }
            if ($this->getAcl()->canApproveDonor($this->getAuthenticatedUser(), $donor)) {
                $donor->set('status', 'approved');
                $approvedDonorsCount++;
            }
        }

        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDonorsURL(),
                ['flag-approved' => $approvedDonorsCount]
            )
        );
    }
}
