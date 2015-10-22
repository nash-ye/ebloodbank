<?php
/**
 * Add user page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Add New User')]);

$view->displayView('form-user', ['user' => $view->get('user')]);

$view->displayView('footer');
