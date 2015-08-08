<?php
/**
 * View Cities Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Views\View;

/**
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
        if (isCurrentUserCan('view_cities')) {
            $view = new View('view-cities');
        } else {
            $view = new View('error-401');
        }
        $view();
    }
}
