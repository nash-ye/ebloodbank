<?php
/**
 * Edit district page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Edit District')]);

$view->displayView('form-district', ['district' => $view->get('district')]);

$view->displayView('footer');
