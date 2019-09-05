<?php
/**
 * View districts page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Options;

/**
 * View districts page controller class
 *
 * @since 1.0
 */
class ViewDistricts extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));
        if (! $isSitePublic && (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'read'))) {
            $this->viewFactory->displayView('error-403');
        } else {
            $this->viewFactory->displayView('view-districts', [
                'districts'          => $this->getQueriedDistricts(),
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
            $total = (int) ceil($this->countAllDistricts() / $limit);
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
     * @return \EBloodBank\Models\District[]
     * @since 1.0
     */
    public function getAllDistricts()
    {
        return $this->getDistrictRepository()->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllDistricts()
    {
        return $this->getDistrictRepository()->countAll();
    }

    /**
     * @return \EBloodBank\Models\District[]
     * @since 1.0
     */
    public function getQueriedDistricts()
    {
        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $this->getDistrictRepository()->findBy([], ['created_at' => 'DESC'], $limit, $offset);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countQueriedDistricts()
    {
        return count($this->getQueriedDistricts());
    }
}
