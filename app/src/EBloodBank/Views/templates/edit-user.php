<?php
/**
 * Edit User Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', ['title' => __('Edit User')]);

View::display('form-user', ['user' => $view->get('user')]);

View::display('footer');
