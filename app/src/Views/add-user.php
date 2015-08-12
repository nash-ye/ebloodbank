<?php
/**
 * Add City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Add User') ));

View::display('form-user');

View::display('footer');
