<?php
/**
 * View Donors Controller
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
class ViewDonors extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('view_donors')) {
            $view = new View('view-donors');
        } else {
            $view = new View('error-401');
        }
        $view();
    }
}
