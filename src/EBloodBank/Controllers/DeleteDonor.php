<?php
/**
 * Delete donor page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use Psr\Container\ContainerInterface;

/**
 * Delete donor page controller class
 *
 * @since 1.0
 */
class DeleteDonor extends Controller
{
    /**
     * @var   int
     * @since 1.6
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
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'delete')) {
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

        if (! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $donor)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->viewFactory->displayView(
            'delete-donor',
            [
                'donor' => $donor,
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
            case 'delete_donor':
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
        $sessionToken = $this->getSession()->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $donor = $this->donor;

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canDeleteEntity($this->getAuthenticatedUser(), $donor)) {
            return;
        }

        $this->getEntityManager()->remove($donor);
        $this->getEntityManager()->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDonorsURL(),
                ['flag-deleted' => 1]
            )
        );
    }
}
