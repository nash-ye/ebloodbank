<?php
/**
 * New City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('New User') ));
$header();

$form = new View('form-user');
$form();

$footer = new View('footer');
$footer();
