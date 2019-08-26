<?php
/**
 * Edit cities page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * Edit cities page controller class
 *
 * @since 1.0
 */
class EditCities extends ViewCities
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        if ($currentUser && $currentUser->canEditCities()) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('edit-cities', [
                'cities' => $this->getQueriedCities(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
            ]);
        } else {
            $view = View::forge('error-403');
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
            Notices::addNotice('deleted', sprintf(n__('%d city permanently deleted.', '%d cities permanently deleted.', $deleted), $deleted), 'success');
        }
    }
}
