<?php
/**
 * Edit User Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Edit User') ));
$header();

$form = new View('form-user', array( 'user' => $this->get('user') ));
$form();

$footer = new View('footer');
$footer();
