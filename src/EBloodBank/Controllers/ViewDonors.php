<?php
/**
 * View donors page controller class file
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
 * View donors page controller class
 *
 * @since 1.0
 */
class ViewDonors extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $isSitePublic = ('on' === EBB\Options::getOption('site_publication'));
        if (! $isSitePublic && (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'read'))) {
            View::display('error-403');
        } else {
            View::display('view-donors', [
                'donors'             => $this->getQueriedDonors(),
                'pagination.total'   => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
                'filter.criteria'    => $this->getFilterCriteria(),
                'cityRepository'     => $this->getEntityManager()->getRepository('Entities:City'),
                'districtRepository' => $this->getEntityManager()->getRepository('Entities:District'),
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
            $total = (int) ceil($this->countAllDonors() / $limit);
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
     * @return array
     * @since 1.0
     */
    public function getFilterCriteria()
    {
        $criteria = [];

        if (filter_has_var(INPUT_POST, 'city_id')) {
            $criteria['city'] = filter_input(INPUT_POST, 'city_id');
        }

        if (filter_has_var(INPUT_POST, 'district_id')) {
            $criteria['district'] = filter_input(INPUT_POST, 'district_id');
        }

        if (filter_has_var(INPUT_POST, 'blood_group')) {
            $criteria['blood_group'] = filter_input(INPUT_POST, 'blood_group');
        }

        if (filter_has_var(INPUT_POST, 'blood_group_alternatives')) {
            $criteria['blood_group_alternatives'] = (filter_input(INPUT_POST, 'blood_group_alternatives') === 'on');
        }

        return $criteria;
    }

    /**
     * @return \EBloodBank\Models\Donor[]
     * @since 1.0
     */
    public function getAllDonors()
    {
        $donorRepository = $this->getEntityManager()->getRepository('Entities:Donor');

        return $donorRepository->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllDonors()
    {
        $donorRepository = $this->getEntityManager()->getRepository('Entities:Donor');

        return $donorRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\Donor[]
     * @since 1.0
     */
    public function getQueriedDonors()
    {
        $donorRepository = $this->getEntityManager()->getRepository('Entities:Donor');

        $criteria = $this->getFilterCriteria();

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'Donor', 'approve')) {
            $criteria['status'] = 'approved';
        }

        return $donorRepository->findBy($criteria, ['created_at' => 'DESC'], $limit, $offset);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countQueriedDonors()
    {
        return count($this->getQueriedDonors());
    }
}
