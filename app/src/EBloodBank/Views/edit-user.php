<?php
/**
 * Edit User Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Edit User') ));

View::display('form-user', array( 'user' => $this->get('user') ));

View::display('footer');
