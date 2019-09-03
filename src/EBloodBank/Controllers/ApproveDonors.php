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
use EBloodBank\Views\View;
use Psr\Container\ContainerInterface;

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
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        if (filter_has_var(INPUT_POST, 'donors')) {
            $donorsIDs = filter_input(INPUT_POST, 'donors', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            if (! empty($donorsIDs) && is_array($donorsIDs)) {
                $donorRepository = $this->getEntityManager()->getRepository('Entities:Donor');
                $this->donors = $donorRepository->findBy(['id' => $donorsIDs]);
            }
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
            $view = View::forge('error-403');
        } else {
            $this->doActions();
            $view = View::forge('approve-donors', [
                'donors' => $this->getQueriedDonors(),
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

        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $donors = $this->getQueriedDonors();

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

    /**
     * @return \EBloodBank\Models\Donor[]
     * @since 1.1
     */
    protected function getQueriedDonors()
    {
        return $this->donors;
    }
}
