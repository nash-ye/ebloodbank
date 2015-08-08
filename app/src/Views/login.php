<?php
/**
 * Log-in Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Login') ));
$header();

$form = new View('form-login');
$form();

$footer = new View('footer');
$footer();
