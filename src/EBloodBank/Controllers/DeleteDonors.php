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
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

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
    protected $donors;

    /**
     * @return void
     * @since 1.1
     */
    public function __construct(ContainerInterface $container)
    {
        $this->donors = [];
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
        $currentUser = EBB\getCurrentUser();
        if (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'Donor', 'delete')) {
            $view = View::forge('error-403');
        } else {
            $this->doActions();
            $view = View::forge('delete-donors', [
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
        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'Donor', 'delete')) {
            return;
        }

        $session = $this->getContainer()->get('session');
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $donors = $this->getQueriedDonors();

        if (! $donors || ! is_array($donors)) {
            return;
        }

        $deletedDonorsCount = 0;

        foreach ($donors as $donor) {
            if ($this->getAcl()->canDeleteEntity($currentUser, $donor)) {
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

    /**
     * @return \EBloodBank\Models\Donor[]
     * @since 1.1
     */
    protected function getQueriedDonors()
    {
        return $this->donors;
    }
}
