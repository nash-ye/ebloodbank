<?php
/**
 * Edit donor page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => __('Edit Donor')]);

$view->displayView('form-donor', ['donor' => $view->get('donor')]);

$view->displayView('footer');
