<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ManageDonors extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_GET['action'])) {
            $donor_id = (int) $_GET['id'];

            if (empty($donor_id)) {
                die(-1);
            }

            if ('delete_donor' === $_GET['action']) {
                if (isCurrentUserCan('delete_donor')) {
                    $deleted = Models\Donors::delete($donor_id);

                    redirect(getSiteURL(array(
                        'page' => 'donors',
                        'flag-deleted' => $deleted,
                    )));
                }
            } elseif ('approve_donor' === $_GET['action']) {
                if (isCurrentUserCan('approve_donor')) {
                    $donor = Models\Donors::fetchByID($donor_id);

                    if (! empty($donor) && $donor->isPending()) {
                        $approved = Models\Donors::update($donor_id, array(
                            'donor_status' => 'approved',
                        ));

                        redirect(getSiteURL(array(
                            'page' => 'manage-donors',
                            'flag-approved' => $approved,
                        )));
                    }
                }
            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
		$filter_args = array();
        $view = new View('manage-donors');

        if (isCurrentUserCan('approve_donor')) {
            $filter_args['status']  = 'all';
        } else {
            $filter_args['status']  = 'approved';
        }

        if (! empty($_POST['name'])) {
            $filter_args['name'] = strip_tags($_POST['name']);
        }

        if (! empty($_POST['distr_id'])) {
            $filter_args['distr_id'] = (int) $_POST['distr_id'];
        }

        if (! empty($_POST['city_id'])) {
            $filter_args['city_id']  = (int) $_POST['city_id'];
        }

        if (! empty($_POST['blood_group'])) {
            $filter_args['blood_group'] = strip_tags($_POST['blood_group']);
        }

        $view(array( 'filter_args' => $filter_args ));
    }
}
