<?php
/**
 * Log-in Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */

$view->displayView('header', ['title' => __('Log In')]);

$view->displayView('form-login');

$view->displayView('footer');
