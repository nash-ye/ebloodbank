<?php
namespace EBloodBank\Controllers;

use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;

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
