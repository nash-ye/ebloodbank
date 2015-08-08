<?php
/**
 * Edit City Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Edit City') ));
$header();

$form = new View('form-city', array( 'city' => $this->get('city') ));
$form();

$footer = new View('footer');
$footer();
