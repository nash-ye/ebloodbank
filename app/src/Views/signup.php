<?php
/**
 * Sign-up Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Sign Up') ));

View::display('form-signup');

View::display('footer');
