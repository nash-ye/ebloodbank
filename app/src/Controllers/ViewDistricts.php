<?php
/**
 * View Districts Controller
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
