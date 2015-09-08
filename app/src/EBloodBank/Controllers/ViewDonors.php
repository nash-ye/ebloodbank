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
        $total = (int) ceil($this->countAllDonors() / $limit);
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

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $donorRepository->findBy(array(), array(), $limit, $offset);
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
