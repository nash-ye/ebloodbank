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
use EBloodBank\Views\View;

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
        $currentUser = EBB\getCurrentUser();
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));
        if (! $isSitePublic && (! $currentUser || ! $this->getAcl()->isUserAllowed($currentUser, 'District', 'read'))) {
            View::display('error-403');
        } else {
            View::display('view-districts', [
                'districts' => $this->getQueriedDistricts(),
                'pagination.total' => $this->getPagesTotal(),
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
        $districtRepository = $this->getEntityManager()->getRepository('Entities:District');

        return $districtRepository->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllDistricts()
    {
        $districtRepository = $this->getEntityManager()->getRepository('Entities:District');

        return $districtRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\District[]
     * @since 1.0
     */
    public function getQueriedDistricts()
    {
        $districtRepository = $this->getEntityManager()->getRepository('Entities:District');

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $districtRepository->findBy([], ['created_at' => 'DESC'], $limit, $offset);
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
