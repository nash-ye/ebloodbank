<?php
/**
 * View Districts Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Options;
use EBloodBank\Views\View;

/**
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
        if (isCurrentUserCan('view_districts')) {
            $view = View::forge('view-districts', array(
                'districts' => $this->getQueriedDistricts(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
            ));
        } else {
            $view = View::forge('error-403');
        }
        $view();
    }

    /**
     * @return int
     * @since 1.0
     */
    public function getPagesTotal()
    {
        $limit = (int) Options::getOption('entities_per_page');
        $total = (int) ceil($this->countAllDistricts() / $limit);
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
        $em = main()->getEntityManager();
        $districtRepository = $em->getRepository('Entities:District');

        return $districtRepository->findAll();
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllDistricts()
    {
        $em = main()->getEntityManager();
        $districtRepository = $em->getRepository('Entities:District');

        return $districtRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\District[]
     * @since 1.0
     */
    public function getQueriedDistricts()
    {
        $em = main()->getEntityManager();
        $districtRepository = $em->getRepository('Entities:District');

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $districtRepository->findBy(array(), array(), $limit, $offset);
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