<?php
/**
 * New City
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('New City') ));

$form = new View('form-city');
$form();

$footer = new View('footer');
$footer();
