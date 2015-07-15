<?php
namespace eBloodBank\Controllers;

use eBloodBank\SessionManage;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class FrontPage extends Controller
{
    /**
     * @var string
     * @since 1.0
     */
    protected $page;

    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_GET['action']) && 'signout' === $_GET['action']) {
            if (SessionManage::signout()) {
                redirect(getSiteURL());
            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (! empty($_GET['page'])) {

            switch ($_GET['page']) {

            case 'signin':
                new Signin();
                break;

            case 'new-user':
                new NewUser();
                break;

            case 'edit-user':
                new EditUser();
                break;

            case 'manage-users':
                new ManageUsers();
                break;

            case 'new-city':
                new NewCity();
                break;

            case 'edit-city':
                new EditCity();
                break;

            case 'manage-cites':
                new ManageCites();
                break;

            case 'new-distr':
                new NewDistrict();
                break;

            case 'edit-distr':
                new EditDistrict();
                break;

            case 'manage-distrs':
                new ManageDistricts();
                break;

            case 'new-donor':
                new NewDonor();
                break;

            case 'edit-donor':
                new EditDonor();
                break;

            case 'manage-donors':
                new ManageDonors();
                break;

            default:
                $view = new View('error-404');
                $view();
                break;

            }

        } else {

            $view = new View('frontpage');
            $view();

        }
    }
}
