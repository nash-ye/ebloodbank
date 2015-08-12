<?php
/**
 * Edit District Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Edit District') ));

View::display('form-district', array( 'district' => $this->get('district') ));

View::display('footer');
