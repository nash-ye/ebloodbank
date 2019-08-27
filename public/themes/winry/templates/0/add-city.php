<?php
/**
 * Add city page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Add New City')]);

$view->displayView('form-city', ['city' => $view->get('city')]);

$view->displayView('footer');
