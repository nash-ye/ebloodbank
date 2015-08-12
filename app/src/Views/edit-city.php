<?php
/**
 * Edit City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Edit City') ));

View::display('form-city', array( 'city' => $this->get('city') ));

View::display('footer');
