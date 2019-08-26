<?php
/**
 * View cities page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Views\View;

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
        $currentUser = EBB\getCurrentUser();
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));
        if (! $isSitePublic && (! $currentUser || ! $currentUser->canViewCities())) {
            View::display('error-403');
        } else {
            View::display('view-cities', [
                'cities' => $this->getQueriedCities(),
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
        $entityManager = $this->getContainer()->get('entity_manager');
        $cityRepository = $entityManager->getRepository('Entities:City');

        return $cityRepository->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllCities()
    {
        $entityManager = $this->getContainer()->get('entity_manager');
        $cityRepository = $entityManager->getRepository('Entities:City');

        return $cityRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\City[]
     * @since 1.0
     */
    public function getQueriedCities()
    {
        $entityManager = $this->getContainer()->get('entity_manager');
        $cityRepository = $entityManager->getRepository('Entities:City');

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $cityRepository->findBy([], ['created_at' => 'DESC'], $limit, $offset);
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
