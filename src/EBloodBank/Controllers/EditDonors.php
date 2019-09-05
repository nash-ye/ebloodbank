<?php
/**
 * Edit donors page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;

/**
 * Edit donors page controller class
 *
 * @since 1.0
 */
class EditDonors extends ViewDonors
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if ($this->hasAuthenticatedUser() && $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'edit')) {
            $this->doActions();
            $this->addNotices();
            $view = $this->viewFactory->forgeView('edit-donors', [
                'donors'             => $this->getQueriedDonors(),
                'pagination.total'   => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
                'filter.criteria'    => $this->getFilterCriteria(),
                'cityRepository'     => $this->getEntityManager()->getRepository('Entities:City'),
                'districtRepository' => $this->getEntityManager()->getRepository('Entities:District'),
            ]);
        } else {
            $view = $this->viewFactory->forgeView('error-403');
        }
        $view();
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-approved')) {
            $approved = (int) filter_input(INPUT_GET, 'flag-approved');
            Notices::addNotice('approved', sprintf(n__('%d donor approved.', '%d donors approved.', $approved), $approved), 'success');
        }
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            $deleted = (int) filter_input(INPUT_GET, 'flag-deleted');
            Notices::addNotice('deleted', sprintf(n__('%d donor permanently deleted.', '%d donors permanently deleted.', $deleted), $deleted), 'success');
        }
    }
}
