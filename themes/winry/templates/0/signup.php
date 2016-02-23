<?php
/**
 * Sign-up page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Sign Up')]);

$view->displayView('form-signup');

$view->displayView('footer');
