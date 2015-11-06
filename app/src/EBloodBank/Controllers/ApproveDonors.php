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
use Aura\Di\ContainerInterface;

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
                $donorRepository = $container->get('entity_manager')->getRepository('Entities:Donor');
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
        if ($currentUser && $currentUser->canApproveDonors()) {
            $this->doActions();
            $view = View::forge('approve-donors', [
                'donors' => $this->getQueriedDonors(),
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
        $session = $this->getContainer()->get('session');
        $sessionToken = $session->getCsrfToken();
        $actionToken = filter_input(INPUT_POST, 'token');

        if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
            return;
        }

        $currentUser = EBB\getCurrentUser();

        if (! $currentUser || ! $currentUser->canApproveDonors()) {
            return;
        }

        $donors = $this->getQueriedDonors();

        if (empty($donors)) {
            return;
        }

        $approvedDonorsCount = 0;
        $em = $this->getContainer()->get('entity_manager');

        foreach($donors as $donor) {
            if (empty($donor) || ! $donor->isPending()) {
                continue;
            }

            $donor->set('status', 'approved');
            $approvedDonorsCount++;
        }

        $em->flush();

        EBB\redirect(
            EBB\addQueryArgs(
                EBB\getEditDonorsURL(),
                array('flag-approved' => $approvedDonorsCount)
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
