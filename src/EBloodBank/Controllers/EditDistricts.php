<?php
/**
 * Edit districts page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;

/**
 * Edit districts page controller class
 *
 * @since 1.0
 */
class EditDistricts extends ViewDistricts
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if ($this->hasAuthenticatedUser() && $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'edit')) {
            $this->doActions();
            $this->addNotices();
            $view = $this->viewFactory->forgeView('edit-districts', [
                'districts' => $this->getQueriedDistricts(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
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
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            $deleted = (int) filter_input(INPUT_GET, 'flag-deleted');
            Notices::addNotice('deleted', sprintf(n__('%d district permanently deleted.', '%d districts permanently deleted.', $deleted), $deleted), 'success');
        }
    }
}
