<?php
/**
 * Log-in Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Log In') ));

View::display('form-login');

View::display('footer');
