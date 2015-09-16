<?php
/**
 * Edit City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', ['title' => __('Edit City')]);

View::display('form-city', ['city' => $view->get('city')]);

View::display('footer');
