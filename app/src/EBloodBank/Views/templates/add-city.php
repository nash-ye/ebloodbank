<?php
/**
 * Add city page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Add New City')]);

$view->displayView('form-city');

$view->displayView('footer');
