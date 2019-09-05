<?php
/**
 * View users page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Options;

/**
 * View users page controller class
 *
 * @since 1.0
 */
class ViewUsers extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'User', 'read')) {
            $this->viewFactory->displayView('error-403');
        } else {
            $this->viewFactory->displayView('view-users', [
                'users'              => $this->getQueriedUsers(),
                'pagination.total'   => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
            ]);
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public function getPagesTotal()
    {
        $total = 1;
        $limit = (int) Options::getOption('entities_per_page');
        if ($limit >= 1) {
            $total = (int) ceil($this->countAllUsers() / $limit);
        }
        return $total;
    }

    /**
     * @return int
     * @since 1.0
     */
    public function getCurrentPage()
    {
        return max((int) filter_input(INPUT_GET, 'page'), 1);
    }

    /**
     * @return \EBloodBank\Models\User[]
     * @since 1.0
     */
    public function getAllUsers()
    {
        return $this->getUserRepository()->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllUsers()
    {
        return $this->getUserRepository()->countAll();
    }

    /**
     * @return \EBloodBank\Models\User[]
     * @since 1.0
     */
    public function getQueriedUsers()
    {
        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        $criteria = [];
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
            $criteria['status'] = 'activated';
        }

        return $this->getUserRepository()->findBy($criteria, ['created_at' => 'DESC'], $limit, $offset);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countQueriedUsers()
    {
        return count($this->getQueriedUsers());
    }
}
