<?php
/**
 * Edit Donor Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', ['title' => __('Edit Donor')]);

View::display('form-donor', ['donor' => $view->get('donor')]);

View::display('footer');
