<?php
/**
 * View donor page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.1
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Views\View;

/**
 * View donor page controller class
 *
 * @since 1.1
 */
class ViewDonor extends Controller
{
    /**
     * @var \EBloodBank\Models\Donor
     * @since 1.1
     */
    protected $donor;

    /**
     * @return void
     * @since 1.1
     */
    public function __construct($id)
    {
        if (EBB\isValidID($id)) {
            $donorRepository = main()->getEntityManager()->getRepository('Entities:Donor');
            $this->donor = $donorRepository->find($id);
        }
    }

    /**
     * @return void
     * @since 1.1
     */
    public function __invoke()
    {
        if ('on' === EBB\Options::getOption('site_publication') || EBB\isCurrentUserCan('view_donors')) {
            $donor = $this->getQueriedDonor();
            if (! empty($donor)) {
                $this->doActions();
                $this->addNotices();
                $view = View::forge('view-donor', array(
                    'donor' => $donor,
                ));
            } else {
                $view = View::forge('error-404');
            }
        } else {
            $view = View::forge('error-403');
        }
        $view();
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function doActions()
    {
    }

    /**
     * @return void
     * @since 1.1
     */
    protected function addNotices()
    {
    }

    /**
     * @return \EBloodBank\Models\Donor
     * @since 1.1
     */
    protected function getQueriedDonor()
    {
        return $this->donor;
    }

    /**
     * @return int
     * @since 1.1
     */
    protected function getQueriedDonorID()
    {
        $donor = $this->getQueriedDonor();
        return ($donor) ? (int) $donor->get('id') : 0;
    }
}
