<?php
/**
 * Edit User Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */

$view->displayView('header', ['title' => __('Edit User')]);

$view->displayView('form-user', ['user' => $view->get('user')]);

$view->displayView('footer');
