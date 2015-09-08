<?php
/**
 * Add District Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Add District') ));

View::display('form-district');

View::display('footer');
