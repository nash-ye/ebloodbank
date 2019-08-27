<?php
/**
 * Log-in page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Log In')]);

$view->displayView('form-login');

$view->displayView('footer');
