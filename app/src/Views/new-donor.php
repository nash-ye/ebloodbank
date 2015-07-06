<?php
/**
 * New Donor
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('New Donor') ));

$form = new View('form-donor');
$form();

$footer = new View('footer');
$footer();
