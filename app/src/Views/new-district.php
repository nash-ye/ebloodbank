<?php
/**
 * New District Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('New District') ));
$header();

$footer = new View('form-district');
$footer();

$footer = new View('footer');
$footer();
