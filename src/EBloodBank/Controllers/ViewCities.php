<?php
/**
 * View cities page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Options;

/**
 * View cities page controller class
 *
 * @since 1.0
 */
class ViewCities extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));
        if (! $isSitePublic && (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'City', 'read'))) {
            $this->viewFactory->displayView('error-403');
        } else {
            $this->viewFactory->displayView('view-cities', [
                'cities'             => $this->getQueriedCities(),
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
            $total = (int) ceil($this->countAllCities() / $limit);
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
     * @return \EBloodBank\Models\City[]
     * @since 1.0
     */
    public function getAllCities()
    {
        return $this->getCityRepository()->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllCities()
    {
        return $this->getCityRepository()->countAll();
    }

    /**
     * @return \EBloodBank\Models\City[]
     * @since 1.0
     */
    public function getQueriedCities()
    {
        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $this->getCityRepository()->findBy([], ['created_at' => 'DESC'], $limit, $offset);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countQueriedCities()
    {
        return count($this->getQueriedCities());
    }
}
