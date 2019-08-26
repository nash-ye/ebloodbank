<?php
/**
 * Edit donor page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

$view->displayView('header', ['title' => d__('winry', 'Edit Donor')]);

$view->displayView('form-donor', ['donor' => $view->get('donor')]);

$view->displayView('footer');
