<?php
/**
 * Edit City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */

$view->displayView('header', ['title' => __('Edit City')]);

$view->displayView('form-city', ['city' => $view->get('city')]);

$view->displayView('footer');
