<?php
/**
 * Add donor page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Add New Donor')]);

$view->displayView('form-donor', ['donor' => $view->get('donor')]);

$view->displayView('footer');
