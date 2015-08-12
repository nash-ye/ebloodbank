<?php
/**
 * Add Donor
 *
 * @package    EBloodBank
 * @subpackage Views
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Add Donor') ));

View::display('form-donor');

View::display('footer');
