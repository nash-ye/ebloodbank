<?php
/**
 * Add district page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Add New District')]);

$view->displayView('form-district', ['district' => $view->get('district')]);

$view->displayView('footer');
