<?php
/**
 * Edit District Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', ['title' => __('Edit District')]);

View::display('form-district', ['district' => $view->get('district')]);

View::display('footer');
