<?php
/**
 * Edit user page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Edit User')]);

$view->displayView('form-user', ['user' => $view->get('user')]);

$view->displayView('footer');
