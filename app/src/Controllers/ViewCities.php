<?php
/**
 * View Cities Controller
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
class ViewCities extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('view_cities')) {
            $view = View::forge('view-cities', array(
                'cities' => $this->getQueriedCities(),
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
        $total = (int) ceil($this->countAllCities() / $limit);
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
        $em = main()->getEntityManager();
        $cityRepository = $em->getRepository('Entities:City');

        return $cityRepository->findAll();
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllCities()
    {
        $em = main()->getEntityManager();
        $cityRepository = $em->getRepository('Entities:City');

        return $cityRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\City[]
     * @since 1.0
     */
    public function getQueriedCities()
    {
        $em = main()->getEntityManager();
        $cityRepository = $em->getRepository('Entities:City');

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $cityRepository->findBy(array(), array(), $limit, $offset);
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
