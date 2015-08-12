<?php
/**
 * Add City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Add City') ));

View::display('form-city');

View::display('footer');
