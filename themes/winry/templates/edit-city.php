<?php
/**
 * Edit city page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Edit City')]);

$view->displayView('form-city', ['city' => $view->get('city')]);

$view->displayView('footer');
