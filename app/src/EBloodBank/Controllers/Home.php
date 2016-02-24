<?php
/**
 * Home page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Views\View;

/**
 * Home page controller class
 *
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
        $view = View::forge('home');
        $view();
    }
}
