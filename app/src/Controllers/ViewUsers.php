<?php
namespace EBloodBank\Controllers;

use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ViewUsers extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('view_users')) {
            $view = new View('view-users');
        } else {
            $view = new View('error-401');
        }
        $view();
    }
}
