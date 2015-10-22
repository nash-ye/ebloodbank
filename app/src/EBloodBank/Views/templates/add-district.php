<?php
/**
 * Add district page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Add New District')]);

$view->displayView('form-district', ['district' => $view->get('district')]);

$view->displayView('footer');
