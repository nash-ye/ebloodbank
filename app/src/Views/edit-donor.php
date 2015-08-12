<?php
/**
 * Edit Donor Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Edit Donor') ));

View::display('form-donor', array( 'donor' => $this->get('donor') ));

View::display('footer');
