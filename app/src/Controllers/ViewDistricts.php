<?php
namespace EBloodBank\Controllers;

use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ViewDistricts extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('view_districts')) {
            $view = new View('view-districts');
        } else {
            $view = new View('error-401');
        }
        $view();
    }
}
