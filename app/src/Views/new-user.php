<?php
/**
 * New City
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('New User') ));

$form = new View('form-user');
$form();

$footer = new View('footer');
$footer();
