<?php
/**
 * View donor page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

/**
 * View donor page controller class
 *
 * @since 1.1
 */
class ViewDonor extends Controller
{
    /**
     * @var \EBloodBank\Models\Donor
     * @since 1.1
     */
    protected $donor;

    /**
     * @return void
     * @since 1.1
     */
    public function __construct(ContainerInterface $container, $id)
    {
        parent::__construct($container);
        if (EBB\isValidID($id)) {
            $donorRepository = $container->get('entity_manager')->getRepository('Entities:Donor');
            $this->donor = $donorRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));

        if (! $isSitePublic && (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'Donor', 'read'))) {
            View::display('error-403');
            return;
        }

        if (! $this->isQueriedDonorExists()) {
            View::display('error-404');
            return;
        }

        $donor = $this->getQueriedDonor();

        if ($currentUser && ! $this->getAcl()->canReadEntity($currentUser, $donor)) {
            View::display('error-403');
            return;
        }

        $this->addNotices();
        View::display('view-donor', [
            'donor' => $donor,
        ]);
    }

    /**
     * @return void
     * @since 1.2
     */
    protected function addNotices()
    {
        if ($this->isQueriedDonorExists() && $this->getQueriedDonor()->isPending()) {
            Notices::addNotice('pending', __('This donor is pendng moderation.'), 'warning');
        }
    }

    /**
     * @return \EBloodBank\Models\Donor
     * @since 1.1
     */
    protected function getQueriedDonor()
    {
        return $this->donor;
    }

    /**
     * @return bool
     * @since 1.2
     */
    protected function isQueriedDonorExists()
    {
        $donor = $this->getQueriedDonor();
        return ($donor && $donor->isExists());
    }
}
