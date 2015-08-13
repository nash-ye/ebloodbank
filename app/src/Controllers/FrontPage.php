<?php
/**
 * Front-page Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\RouterManager;
use EBloodBank\Views\View;

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
        $route = RouterManager::getMatchedRoute();

        $pageName = $route ? $route->name : '';

        switch ($pageName) {

            case 'home':
                $controller = new Home();
                break;

            case 'login':
                $controller = new Login();
                break;

            case 'signup':
                $controller = new Signup();
                break;


            case 'view-users':
                $controller = new ViewUsers();
                break;
            case 'view-donors':
                $controller = new ViewDonors();
                break;
            case 'view-cities':
                $controller = new ViewCities();
                break;
            case 'view-districts':
                $controller = new ViewDistricts();
                break;


            case 'add-user':
                $controller = new AddUser();
                break;
            case 'add-donor':
                $controller = new AddDonor();
                break;
            case 'add-city':
                $controller = new AddCity();
                break;
            case 'add-district':
                $controller = new AddDistrict();
                break;


            case 'edit-user':
                $controller = new EditUser();
                break;
            case 'edit-donor':
                $controller = new EditDonor();
                break;
            case 'edit-city':
                $controller = new EditCity();
                break;
            case 'edit-district':
                $controller = new EditDistrict();
                break;


            case 'edit-users':
                $controller = new EditUsers();
                break;
            case 'edit-donors':
                $controller = new EditDonors();
                break;
            case 'edit-cities':
                $controller = new EditCities();
                break;
            case 'edit-districts':
                $controller = new EditDistricts();
                break;


            default:
                $controller = View::instance('error-404');
                break;

        }

        $controller();
    }
}
