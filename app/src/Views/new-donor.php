<?php
/**
 * New Donor
 *
 * @package    EBloodBank
 * @subpackage Views
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('New Donor') ));
$header();

$form = new View('form-donor');
$form();

$footer = new View('footer');
$footer();
