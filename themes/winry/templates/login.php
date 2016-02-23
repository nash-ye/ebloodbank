<?php
/**
 * Log-in page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Log In')]);

$view->displayView('form-login');

$view->displayView('footer');
