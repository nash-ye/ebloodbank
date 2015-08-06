<?php
namespace EBloodBank\Controllers;

use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class FrontPage extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! empty($_GET['page'])) {

            switch ($_GET['page']) {

                case 'login':
                    $controller = new Login();
                    break;

                case 'signup':
                    $controller = new Signup();
                    break;


                case 'new-user':
                    $controller = new NewUser();
                    break;

                case 'edit-user':
                    $controller = new EditUser();
                    break;

                case 'view-users':
                    $controller = new ViewUsers();
                    break;

                case 'manage-users':
                    $controller = new ManageUsers();
                    break;


                case 'new-city':
                    $controller = new NewCity();
                    break;

                case 'edit-city':
                    $controller = new EditCity();
                    break;

                case 'view-cities':
                    $controller = new ViewCities();
                    break;

                case 'manage-cities':
                    $controller = new ManageCities();
                    break;


                case 'new-district':
                    $controller = new NewDistrict();
                    break;

                case 'edit-district':
                    $controller = new EditDistrict();
                    break;

                case 'view-districts':
                    $controller = new ViewDistricts();
                    break;

                case 'manage-districts':
                    $controller = new ManageDistricts();
                    break;


                case 'new-donor':
                    $controller = new NewDonor();
                    break;

                case 'edit-donor':
                    $controller = new EditDonor();
                    break;

                case 'view-donors':
                    $controller = new ViewDonors();
                    break;

                case 'manage-donors':
                    $controller = new ManageDonors();
                    break;


                default:
                    $view = new View('error-404');
                    $view();
                    break;

            }

            if (isset($controller)) {
                $controller();
            }

        } else {

            $view = new View('frontpage');
            $view();

        }
    }
}
