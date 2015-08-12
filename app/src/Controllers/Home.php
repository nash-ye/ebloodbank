<?php
/**
 * Home Page Controller
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
class Home extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $view = View::instance('home');
        $view();
    }
}
