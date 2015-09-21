<?php
/**
 * View Donors Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Views\View;

/**
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
        if (EBB\isCurrentUserCan('view_donors')) {
            $view = View::forge('view-donors', array(
                'donors' => $this->getQueriedDonors(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
                'filter.criteria' => $this->getFilterCriteria(),
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

        return $criteria;
    }

    /**
     * @return \EBloodBank\Models\Donor[]
     * @since 1.0
     */
    public function getAllDonors()
    {
        $em = main()->getEntityManager();
        $donorRepository = $em->getRepository('Entities:Donor');

        return $donorRepository->findAll();
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllDonors()
    {
        $em = main()->getEntityManager();
        $donorRepository = $em->getRepository('Entities:Donor');

        return $donorRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\Donor[]
     * @since 1.0
     */
    public function getQueriedDonors()
    {
        $em = main()->getEntityManager();
        $donorRepository = $em->getRepository('Entities:Donor');

        $criteria = $this->getFilterCriteria();

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $donorRepository->findBy($criteria, array(), $limit, $offset);
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
