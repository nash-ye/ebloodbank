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
use Psr\Container\ContainerInterface;

/**
 * View donor page controller class
 *
 * @since 1.1
 */
class ViewDonor extends Controller
{
    /**
     * @var int
     * @since 1.6
     */
    protected $donorId = 0;

    /**
     * @var \EBloodBank\Models\Donor|nul
     * @since 1.1
     */
    protected $donor;

    /**
     * @since 1.1
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
     * @since 1.1
     */
    public function __invoke()
    {
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));

        if (! $isSitePublic && (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'read'))) {
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

        if ($this->hasAuthenticatedUser() && ! $this->getAcl()->canReadEntity($this->getAuthenticatedUser(), $donor)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->addNotices();
        $this->viewFactory->displayView(
            'view-donor',
            [
                'donor' => $donor,
            ]
        );
    }

    /**
     * @return void
     * @since 1.2
     */
    protected function addNotices()
    {
        if ($this->donor && $this->donor->isPending()) {
            Notices::addNotice('pending', __('This donor is pendng moderation.'), 'warning');
        }
    }
}
