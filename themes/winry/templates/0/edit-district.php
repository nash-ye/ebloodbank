<?php
/**
 * Edit district page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Edit District')]);

$view->displayView('form-district', ['district' => $view->get('district')]);

$view->displayView('footer');
