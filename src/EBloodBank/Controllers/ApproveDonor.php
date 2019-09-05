<?php
/**
 * Approve donor page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use Psr\Container\ContainerInterface;

/**
 * Approve donor page controller class
 *
 * @since 1.0
 */
class ApproveDonor extends Controller
{
    /**
     * @var   int
     * @since 1.0
     */
    protected $donorId = 0;

    /**
     * @var   \EBloodBank\Models\Donor|null
     * @since 1.0
     */
    protected $donor;

    /**
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $donorId)
    {
        parent::__construct($container);
        if (EBB\isValidID($donorId)) {
            $this->donorId = (int) $donorId;
        }
    }

    /**
     * @return void
     * @since  1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->donorId) {
            $this->donor = $this->getDonorRepository()->find($this->donorId);
        }

        if (! $this->donor) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $donor = $this->donor;

        if (! $this->getAcl()->canApproveDonor($this->getAuthenticatedUser(), $donor)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->viewFactory->displayView('approve-donor', [
            'donor' => $donor,
        ]);
    }

    /**
     * @return void
     * @since  1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'approve_donor':
                $this->doApproveAction();
                break;
        }
    }

    /**
     * @return void
     * @since  1.0
     */
    protected function doApproveAction()
    {
        $actionToken = filter_input(INPUT_POST, 'token');
        $sessionToken = $this->getSession()->getCsrfToken();

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $donor = $this->donor;

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canApproveDonor($this->getAuthenticatedUser(), $donor)) {
            return;
        }

        if (! $donor->isPending()) {
            return;
        }

        $donor->set('status', 'approved');
        $this->getEntityManager()->flush($donor);

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDonorsURL(),
                ['flag-approved' => 1]
            )
        );
    }
}
