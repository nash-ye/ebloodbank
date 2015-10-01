<?php
/**
 * Sign-up page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Sign Up')]);

$view->displayView('form-signup');

$view->displayView('footer');
