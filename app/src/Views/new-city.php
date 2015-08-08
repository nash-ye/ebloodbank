<?php
/**
 * New City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('New City') ));
$header();

$form = new View('form-city');
$form();

$footer = new View('footer');
$footer();
