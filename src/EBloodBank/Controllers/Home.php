<?php
/**
 * Home page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

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
        $view = $this->viewFactory->forgeView('home');
        $view();
    }
}
