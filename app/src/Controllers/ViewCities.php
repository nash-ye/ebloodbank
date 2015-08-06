<?php
namespace EBloodBank\Controllers;

use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;

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
