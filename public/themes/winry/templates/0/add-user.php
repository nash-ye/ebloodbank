<?php
/**
 * Add user page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Add New User')]);

$view->displayView('form-user', ['user' => $view->get('user')]);

$view->displayView('footer');
