<?php
/**
 * Edit users page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;

/**
 * Edit users page controller class
 *
 * @since 1.0
 */
class EditUsers extends ViewUsers
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if ($this->hasAuthenticatedUser() && $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'edit')) {
            $this->doActions();
            $this->addNotices();
            $view = $this->viewFactory->forgeView('edit-users', [
                'users'              => $this->getQueriedUsers(),
                'pagination.total'   => $this->getPagesTotal(),
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
        if (filter_has_var(INPUT_GET, 'flag-activated')) {
            $activated = (int) filter_input(INPUT_GET, 'flag-activated');
            Notices::addNotice('activated', sprintf(n__('%d user activated.', '%d users activated.', $activated), $activated), 'success');
        }
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            $deleted = (int) filter_input(INPUT_GET, 'flag-deleted');
            Notices::addNotice('deleted', sprintf(n__('%d user permanently deleted.', '%d users permanently deleted.', $deleted), $deleted), 'success');
        }
    }
}
