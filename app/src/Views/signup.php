<?php
/**
 * Sign-up Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Sign up') ));
$header();

$form = new View('form-signup');
$form();

$footer = new View('footer');
$footer();
