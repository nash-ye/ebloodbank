<?php
/**
 * Edit Donor Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Exceptions;
use EBloodBank\EntityManager;
use EBloodBank\RouterManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class EditDonor extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'submit_donor':
                $this->doSubmitAction();
                break;
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    protected function getTargetID()
    {
        $targetID = 0;
        $route = RouterManager::getMatchedRoute();

        if ($route && isset($route->params['id'])) {
            if (isVaildID($route->params['id'])) {
                $targetID = (int) $route->params['id'];
            }
        }

        return $targetID;
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        if (isCurrentUserCan('edit_donor')) {

            try {

                $donorID = $this->getTargetID();
                $donor = EntityManager::getDonorReference($donorID);

                // Set the donor name.
                $donor->set('name', filter_input(INPUT_POST, 'donor_name'), true);

                // Set the donor gender.
                $donor->set('gender', filter_input(INPUT_POST, 'donor_gender'), true);

                // Set the donor weight.
                $donor->set('weight', filter_input(INPUT_POST, 'donor_weight'), true);

                // Set the donor birthdate.
                $donor->set('birthdate', filter_input(INPUT_POST, 'donor_birthdate'), true);

                // Set the donor blood group.
                $donor->set('blood_group', filter_input(INPUT_POST, 'donor_blood_group'), true);

                // Set the donor phone number.
                $donor->set('phone', filter_input(INPUT_POST, 'donor_phone'), true);

                // Set the donor email address.
                $donor->set('email', filter_input(INPUT_POST, 'donor_email'), true);

                // Set the donor address.
                $donor->set('address', filter_input(INPUT_POST, 'donor_address'), true);

                // Set the donor district ID.
                $donor->set('district', filter_input(INPUT_POST, 'donor_distr_id'), true);

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditDonorURL($donorID),
                        array('flag-submitted' => true)
                    )
                );

            } catch (Exceptions\InvaildArgument $ex) {
                Notices::addNotice($ex->getSlug(), $ex->getMessage(), 'warning');
            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $donorID = $this->getTargetID();

        if (! $donorID) {
            redirect(getHomeURL());
        }

        if (isCurrentUserCan('edit_donor')) {
            $this->doActions();
            $donorRepository = EntityManager::getDonorRepository();
            $donor = $donorRepository->find($donorID);
            if (! empty($donor)) {
                $view = View::instance('edit-donor', array( 'donor' => $donor ));
            } else {
                $view = View::instance('error-404');
            }
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
